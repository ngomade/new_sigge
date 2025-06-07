import React, {useState} from 'react';
import {LuArrowRight} from 'react-icons/lu';
import {LuArrowLeft} from 'react-icons/lu';
import {fieldSet, notEmpty} from '../../utils/validation';
import {useDispatch, useSelector} from 'react-redux';
import {push_candidate_info} from '../../app/modules/candidate';
import {toast} from 'react-toastify';
import {nextStep, prevStep} from '../../app/modules/stepper';
import {getCentreDepot, getCentreExamen, getSiteFormation} from '../../api/routes/concours';

function ConcourInfo({setLoadingState}) {
    const [formData, setFormData] = useState({});
    const [siteCompoDescription, setSiteCompoDescription] = useState({});
    const [hasSiteCompoDescription, setHasSiteCompoDescription] = useState(false);
    const step = useSelector((state) => state.stepper.step);
    const totalSteps = useSelector((state) => state.stepper.totalSteps);
    const [centreDepot, setCentreDepot] = useState([])
    const [centreExamen, setCentreExamen] = useState([])
    const [siteFormation, setSiteFormation] = useState([])
    const {finish, ...userData} = useSelector((state) => state.candidate.candidate_state)

    function fetchCentreExamen() {
        getCentreExamen().then(async (res) => {
            if (res.status === 200) {
                const data = await res.json()
                return setCentreExamen(data)
            }
        })
    }

    function fetchCentreDepot() {
        getCentreDepot().then(async (res) => {
            if (res.status === 200) {
                const data = await res.json()
                return setCentreDepot(data)
            }
        })
    }

    function fetchSiteFormation() {
        getSiteFormation().then(async (res) => {
            if (res.status === 200) {
                const data = await res.json()
                return setSiteFormation(data)
            }
        })
    }

    function onSiteCompoDescription(e) {
        if (siteCompoDescription != null) {
            setSiteCompoDescription(e)
            setHasSiteCompoDescription(true)
        } else {
            setHasSiteCompoDescription(false)
        }
    }

    React.useEffect(() => {
        // Initialisation depuis localStorage si dispo
        const localData = localStorage.getItem('candidate_step_data');
        if (localData) {
            setFormData(JSON.parse(localData));
        } else if (userData && Object.keys(userData).length > 0) {
            setFormData(userData);
        } else {
            fieldSet('#form_concours', setFormData, {})
        }
        fetchCentreDepot();
        fetchCentreExamen();
        fetchSiteFormation();
        return () => {
            fieldSet('#form_concours', setFormData, {})
            fetchCentreDepot();
            fetchCentreExamen();
            fetchSiteFormation();
            setLoadingState(false)
        };
    }, []);

    function onChange(e) {
        setFormData(prevData => {
            const newData = {
                ...prevData,
                [e.target?.name]: e.target?.value
            };
            // Synchronisation Redux
            dispatch(push_candidate_info(newData));
            // Synchronisation localStorage
            localStorage.setItem('candidate_step_data', JSON.stringify({
                ...JSON.parse(localStorage.getItem('candidate_step_data') || '{}'),
                ...newData
            }));
            return newData;
        });
    }

    const dispatch = useDispatch()

    function onSubmit(e) {
        try {
            e.preventDefault();
            if (notEmpty(formData)) {
                setLoadingState(true)
                // Merge des anciennes données avec les nouvelles
                dispatch(push_candidate_info({...userData, ...formData}))
                setTimeout(() => {
                    setLoadingState(false)
                    dispatch(nextStep())
                }, 2000);
            } else {
                toast.error("Veuillew remplir tous les champs...")
            }
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div>
            <h1 className='text-3xl font-bold text-teal-500'>
                Informations liées au concours
            </h1>
            <form action="" method="post" id='form_concours' onSubmit={onSubmit}>
                <div className="grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="centre_exam">Centre d'examen <sup className='text-red-600'>*</sup></label>
                        <select id='centre_exam' value={formData.ca_centre_examen || ""} name="ca_centre_examen"
                                placeholder='Exp: Lycée Bilingue d Abam'
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                                onChange={onChange}>
                            <option value="">Selectionner un centre</option>
                            {centreExamen.map((cE, k) => (
                                <option value={cE.centre_exam_code} key={k}>{cE.centre_exam_label}</option>
                            ))}
                        </select>

                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="centre_depot">Centre de dépot <sup className='text-red-600'>*</sup></label>
                        <select id='centre_depot' value={formData.ca_centre_depot || ""} name="ca_centre_depot"
                                placeholder='Exp: Lycée Bilingue d Ambam'
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                                onChange={onChange}>
                            <option value="">Selectionner un centre</option>
                            {centreDepot.map((cD, k) => (
                                <option value={cD.centre_depot_code} key={k}>{cD.centre_depot_label}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="site_formation">Site de formation <sup className='text-red-600'>*</sup></label>
                        <select id='site_formation' name="code_site" value={formData.code_site || ""}
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                                onChange={function (e) {
                                    onChange(e)
                                    setTimeout(() => {
                                        let siteFor = siteFormation.find(site => site.code_site == e.target.value)
                                        siteFor != null ? onSiteCompoDescription(siteFor) : onSiteCompoDescription({})
                                    }, 50);
                                }}>
                            <option value=''>Selection votre site de formation</option>
                            {siteFormation.map((sF, k) => (
                                <option value={sF.code_site} key={k}>{sF.label_site}</option>

                            ))}
                        </select>
                    </div>
                </div>
                {hasSiteCompoDescription && (
                    <div
                        className='grid bg-white font-bold text-lg grid-cols-1 h-15 place-items-center mb-2 rounded   m-auto shadow-lg shadow-green-800 rounded p-6 '>
                        <div className='mb-5  text-justify p-3 text-l text-gray-800 leading-10'
                             dangerouslySetInnerHTML={{__html: siteCompoDescription.description_site}}/>
                    </div>
                )}
                <div className="flex justify-between">
                    <button
                        type='button'
                        onClick={() => dispatch(prevStep())}
                        className={`px-4 py-2 flex items-center gap-4 rounded ${step === 1 ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === 1}
                    >
                        <LuArrowLeft/> Précédent
                    </button>
                    <button
                        type='submit'

                        className={`px-4 py-2 flex items-center gap-4 rounded ${step === totalSteps ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === totalSteps}
                    >
                        Suivant <LuArrowRight/>
                    </button>
                </div>
            </form>
        </div>
    );
}

export default ConcourInfo;
