import React, {useState} from 'react';
import {LuArrowRight} from 'react-icons/lu';
import {LuArrowLeft} from 'react-icons/lu';
import {fieldSet, notEmpty} from '../../utils/validation';
import {push_candidate_info} from '../../app/modules/candidate';
import {useDispatch, useSelector} from 'react-redux';
import {toast} from 'react-toastify';
import {nextStep, prevStep} from '../../app/modules/stepper';
import {getFiliere} from '../../api/routes/filiere';
import data from '../../../data.json'
import {getSeries} from '../../api/routes/serie';
import {getDiplome} from '../../api/routes/candidate';

function AcademiqueInfo({setLoadingState}) {
    const step = useSelector((state) => state.stepper.step);
    const [filieres, setFiliere] = useState([])
    const [Series, setSeries] = useState([])
    const [diplome, setDiplome] = useState([])
    const totalSteps = useSelector((state) => state.stepper.totalSteps);
    const [formData, setFormData] = useState({});
    const {finish, ...userData} = useSelector((state) => state.candidate.candidate_state)

    function fetchFiliere() {
        getFiliere().then(async (res) => {
            if (res.status === 200) {
                const data = await res.json()
                return setFiliere(data)
            }
        })
    }

    function fetchDiplome(data) {
        setLoadingState(true)
        getDiplome({filiere_code: data}).then(async (res) => {
            if (res.status === 200) {

                const data = await res.json()
                setLoadingState(false)
                return setDiplome(data)

            } else {
                // console.log(await res.json())
                setLoadingState(false)
            }
        })
    }

    function fetchSerie({filiere_code, code_dip}) {
        setLoadingState(true)
        getSeries({filiere_code, code_dip}).then(async (res) => {
            if (res.status === 200) {
                const data = await res.json()
                setLoadingState(false)
                return setSeries(data)
            } else {
                setLoadingState(false)
            }
        })
    }

    React.useEffect(() => {
        // Initialisation depuis localStorage si dispo
        const localData = localStorage.getItem('candidate_step_data');
        if (localData) {
            setFormData(JSON.parse(localData));
        } else if (userData && Object.keys(userData).length > 0) {
            setFormData(userData);
        } else {
            fieldSet('#form_aca', setFormData, {});
        }
        fetchFiliere();
        return () => {
            setLoadingState(false); 
        };
    }, [setLoadingState, userData]);

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
                toast.error("Veuillez remplir tous les champs...")
            }
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div className='mt-12'>
            <h1 className='text-3xl font-bold text-teal-500'>
                Vos informations académiques
            </h1>
            <form action="" method="post" id='form_aca' onSubmit={onSubmit}>
                <div className="grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="filiere_code">Filière admission <sup className='text-red-600'>*</sup></label>
                        <select name="filiere_code" onChange={function (e) {
                            onChange(e)
                            setTimeout(() => {
                                fetchDiplome(e.target.value)
                            }, 20);
                        }} value={formData.filiere_code || ""} id="filiere_code"
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'>
                            <option value="">Selectionner</option>
                            {filieres.map((f, k) => (
                                <option value={f.code_filiere} key={k}>{f.label_filiere}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="ca_diplome_admission">Diplôme <sup className='text-red-600'>*</sup></label>
                        <select name="ca_diplome_admission" value={formData.ca_diplome_admission || ""}
                                onChange={function (e) {
                                    onChange(e)
                                    fetchSerie({
                                        filiere_code: formData.filiere_code,
                                        code_dip: e.target.value
                                    })
                                }} id="filiere_admission"
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'>
                            <option value="">Selectionner</option>
                            {diplome.map((f, k) => (
                                <option value={f.code_dip} key={k}>{f.label_dip}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="serie">Série /spécialité<sup className='text-red-600'>*</sup></label>
                        <select id='serie' name="ca_serie_diplome" value={formData.ca_serie_diplome || ""}
                                placeholder='INFORMATIQUE'
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                                onChange={onChange}>
                            <option value="">Selectionner votre serie / spécialité </option>
                            {Series.map((s) => (
                                <option value={s.code_serie}>{s.label_serie}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="annee_diplome">Année diplôme <sup className='text-red-600'>*</sup></label>
                        <select id='annee_diplome' name="ca_annee_diplome" value={formData.ca_annee_diplome || ""}
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15'
                                onChange={onChange}>
                            <option value="">Veuillez selectionner</option>
                            {Array.from({length: 20}, (_, i) => new Date().getFullYear() - i).map(year => (
                                <option key={year} value={year}>{year}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="mention">Mention<sup className='text-red-600'>*</sup></label>
                        <select id='mention' name="ca_mention_diplome" value={formData.ca_mention_diplome || ""}
                                className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                                onChange={onChange}>
                            <option value="">Sélectionner une mention</option>
                            <option value="TRÈS BIEN">TRÈS BIEN</option>
                            <option value="BIEN">BIEN</option>
                            <option value="ASSEZ BIEN">ASSEZ BIEN</option>
                            <option value="PASSABLE">PASSABLE</option>
                        </select>
                    </div>

                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="etablissement">Etablissement <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='etablissement' value={formData.ca_etab_diplome || ""}
                               name="ca_etab_diplome" placeholder='Lycée Technique de Mbalmayo'
                               className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                               onChange={onChange}/>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="pays_diplome">Pays de diplome <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='pays_diplome' value={formData.ca_pays_diplome || ""}
                               name="ca_pays_diplome" placeholder='CAMEROUN' list='list_pays'
                               className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                               onChange={onChange}/>
                        <datalist id='list_pays'>
                            {data.map((d) => d.country).map((c, k) => (
                                <option value={c} key={k}>{c}</option>
                            ))}
                        </datalist>
                    </div>
                </div>
                <div className="flex justify-between">
                    <button
                        type='button'
                        onClick={() => dispatch(prevStep())}
                        className={`px-4 flex items-center gap-4 py-2 rounded ${step === 1 ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === 1}
                    >
                        <LuArrowLeft/> Précédent
                    </button>
                    <button
                        type='submit'

                        className={`px-4 flex items-center gap-4 py-2 rounded ${step === totalSteps ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === totalSteps}
                    >
                        Suivant <LuArrowRight/>
                    </button>
                </div>
            </form>
        </div>
    );
}

export default AcademiqueInfo;
