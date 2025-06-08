import React, {useEffect, useState} from 'react';
import {LuArrowLeft} from 'react-icons/lu';
import {useDispatch, useSelector} from 'react-redux';
import {toast} from 'react-toastify';
import {initStep, prevStep} from '../../app/modules/stepper';
import CustomCheckbox from '../CustomCheckbox';
import {createCandidate} from '../../api/routes/candidate';
import {getDossier} from '../../api/routes/concours';

function Confirmation({setLoadingState}) {
    const [formData, setFormData] = useState({});
    const [dossiers, setDossier] = useState([]);
    const {finish, ...userData} = useSelector((state) => state.candidate.candidate_state);
    const sessionData = sessionStorage.getItem("session");
    const {id} = sessionData ? JSON.parse(sessionData) : {};

    function onChange(e) {
        const {name, checked} = e.target;
        setFormData(prevData => ({
            ...prevData,
            [name]: checked
        }));
    }

    function fetchDossier() {
        getDossier().then(res => {
            if (res.status === 200) {
                res.json().then(data => setDossier(data));
            }
        }).catch(error => console.error('Error fetching dossier:', error));
    }

    useEffect(() => {
        fetchDossier();
    }, []);

    const dispatch = useDispatch();

    function onSubmit(e) {
        e.preventDefault();
        // Récupération des données du localStorage (clé candidate_step_data)
        const localData = localStorage.getItem('candidate_step_data');
        let allData = userData;
        if (localData) {
            try {
                allData = {...allData, ...JSON.parse(localData)};
            } catch (err) {
                // ignore parse error
            }
        }
        // Debug : afficher toutes les données envoyées
        console.log('userData envoyé à l\'API:', allData);
        console.log('id envoyé à l\'API:', id);
        // Vérification des champs obligatoires
        const requiredFields = [
            'id', 'filiere_code', 'code_site', 'ca_nom', 'ca_prenom', 'ca_sexe', 'ca_date_naiss', 'ca_lieu_naiss', 'ca_statut_mat', 'ca_telephone', 'ca_num_cni', 'ca_email', 'ca_premiere_lang', 'ca_nationalite', 'ca_region_origine', 'ca_depart_origine', 'ca_diplome_admission', 'ca_annee_diplome', 'ca_serie_diplome', 'ca_mention_diplome', 'ca_etab_diplome', 'ca_pays_diplome', 'ca_centre_examen', 'ca_centre_depot', 'ca_nom_pere', 'ca_telephone_pere', 'ca_nom_mere', 'ca_telephone_mere', 'ca_deliv_cni', 'ca_num_recu', 'ca_recu'
        ];
        const missing = requiredFields.filter(f => (f === 'id' ? !id : !allData[f]));
        if (missing.length > 0) {
            toast.error('Champs manquants : ' + missing.join(', '));
            return;
        }
        const allChecked = Object.values(formData).every(value => value);
        if (allChecked) {
            setLoadingState(true);
            createCandidate({...allData, id})
                .then(async res => {
                    if (res.ok) {
                        setLoadingState(false);
                        dispatch(initStep());
                        localStorage.removeItem('candidate_step_data');
                        const {data} = await res.json()
                        sessionStorage.setItem("candidat", JSON.stringify(data))
                        window.location.href = '/success'
                    } else {
                        const {erreur} = res.json();
                        setLoadingState(false);
                        return toast.error(JSON.stringify(erreur), {autoClose: 5000});
                    }
                })
                .catch(error => {
                    setLoadingState(false);
                    return toast.error(error);
                });
        } else {
            return toast.error("Veuillez cocher tous les champs requis...");
        }
    }
    return (
        <div className='mt-12'>
            <h1 className='text-3xl font-bold text-teal-500'>
                Confirmation des autres pièces du dossier
            </h1>
            <form id='form_docs' onSubmit={onSubmit}>
                <div className="grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    {dossiers.map((d, k) => (
                        <div className='flex items-center gap-3 mb-3' key={k}>
                            <CustomCheckbox checked={formData?.[d.label_el]} onChange={onChange} id={d.code_el}
                                            name={d.label_el}/>
                            <label htmlFor={d.code_el} dangerouslySetInnerHTML={{__html: d.label_el}}></label>
                        </div>
                    ))}
                </div>
                <div className="flex items-center justify-between">
                    <button type='button' onClick={() => dispatch(prevStep())}
                            className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                        <LuArrowLeft/> Précédent
                    </button>
                    <button type='submit'
                            className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                        S'inscrire Maintenant
                    </button>
                </div>
            </form>
        </div>
    );
}

export default Confirmation;
