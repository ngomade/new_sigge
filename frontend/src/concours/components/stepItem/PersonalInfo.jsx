import React, { useState } from 'react';
import { fieldSet, notEmpty } from '../../utils/validation';
import data from '../../../data.json'
import { useDispatch, useSelector } from "react-redux"
import { toast } from 'react-toastify';
import { push_candidate_info } from '../../app/modules/candidate';
import { nextStep, prevStep } from '../../app/modules/stepper';
import { LuArrowLeft, LuArrowRight } from 'react-icons/lu';
import CustomCheckbox from '../CustomCheckbox';
function PersonnalInfo({  setLoadingState }) {
    const [formData, setFormData] = useState({});
    const [hasDisability, setHasDisability] = useState(false);
    const [disabilityDescription, setDisabilityDescription] = useState('');
    const step = useSelector((state) => state.stepper.step);
    const totalSteps = useSelector((state) => state.stepper.totalSteps);
    const dispatch = useDispatch()
    const {finish,...userData} = useSelector((state)=>state.candidate.candidate_state)
    function onChange(e) {
        setFormData(prevData => ({
            ...prevData,
            [e.target?.name]: e.target?.value
        }));
    }
    const user = JSON.parse(sessionStorage.getItem("user"))
    React.useEffect(() => {
        
        if(user||userData){
            fieldSet('#form', setFormData, { ca_statut_mat: 'Célibataire',ca_nom:user?.ca_nom,ca_prenom:user?.ca_prenom ,ca_handicap:"Non",})
        }else{
            fieldSet('#form', setFormData, { ca_statut_mat: 'Célibataire',ca_handicap:"Non" })
        }
        return () => {
            setLoadingState(false)
            if(user||userData){
                fieldSet('#form', setFormData, { ca_statut_mat: 'Célibataire',ca_nom:user?.ca_nom,ca_prenom:user?.ca_prenom ,ca_handicap:"Non",})
            }else{
                fieldSet('#form', setFormData, { ca_statut_mat: 'Célibataire' ,ca_handicap:"Non"})
            }
        };
    }, [])
    function onDisabilityChange(e) {
        setHasDisability(e.target.checked);
    }

    function onDisabilityDescriptionChange(e) {
        setDisabilityDescription(e.target.value);
    }
    function onSubmit(e) {
        try {
            e.preventDefault();
            if (notEmpty(formData)) {
                setLoadingState(true)
                dispatch(push_candidate_info({...formData,ca_recu:user?.ca_recu,ca_num_recu:user?.ca_num_recu}))
                setTimeout(() => {
                    setLoadingState(false)
                    dispatch(nextStep())
                }, 1500);
            } else {
                toast.error("Veuillez remplir tous les champs...")
            }
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div>
             <h1 className='text-3xl font-bold text-teal-500'>
                Informations personnelles
            </h1>
            <form action="" method="post" onSubmit={onSubmit} id='form'>
                <div className="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="name">Nom <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='name' value={formData?.ca_nom} name="ca_nom" placeholder='Exp MAHOP MAHOP' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="lastname">Prénom</label>
                        <input type="text" id='lastname' value={formData?.ca_prenom} name="ca_prenom" placeholder='Exp BORIS JUNIOR' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="sexe">Votre sexe <sup className='text-red-600'>*</sup></label>
                        <select id="sexe" name="ca_sexe" value={formData?.ca_sexe} className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            <option value="Masculin">Masculin</option>
                            <option value="Féminin">Féminin</option>
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="birthdate">Date de naissance <sup className='text-red-600'>*</sup></label>
                        <input type="date" id='birthdate' max={`${new Date().getFullYear()-14}-01-01`} min={`${new Date().getFullYear()-45}-01-01`} name="ca_date_naiss" value={formData?.ca_date_naiss} placeholder='Exp 27/08/2000' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="birthplace">Lieu de naissance <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='birthplace' value={formData?.ca_lieu_naiss} name="ca_lieu_naiss" placeholder='Exp EDEA' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="contact">Numéro de Téléphone <sup className='text-red-600'>*</sup></label>
                        <input type="tel" id='contact' name="ca_telephone" minLength={9} maxLength={9} value={formData?.ca_telephone} placeholder='Exp 698123456' pattern='[0-9]{9}' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="cni">N° CNI <sup className='text-red-600'>*</sup> (<i>pas de numéro KITXXX</i>)</label>
                        <input type="text" id='cni' name="ca_num_cni" value={formData?.ca_num_cni}  placeholder='SU05126I5J71MSCY9XD1 ou 68123456 ' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="ca_deliv_cni">Date de délivrance de votre CNI <sup className='text-red-600'>*</sup></label>
                        <input type="date" id='ca_deliv_cni' name="ca_deliv_cni" value={formData?.ca_deliv_cni} max={`${new Date().getFullYear()}-09-26`} min={`${new Date().getFullYear()-10}-01-01`}  placeholder='' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="residence">Lieu de résidence <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='ca_adress' name="ca_adresse" value={formData?.ca_resid} placeholder='Exp Maroua' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="ca_email">Email <sup className='text-red-600'>*</sup></label>
                        <input type="email" id='ca_email' name="ca_email" value={formData?.ca_email} placeholder='Exp nonomartin2004@gmail.com' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="status_matrimonial">Status matrimonial <sup className='text-red-600'>*</sup></label>
                        <select defaultValue={'Célibataire'} id="status_matrimonial" name="ca_statut_mat" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            <option value="Marié">Marié</option>
                            <option value="Célibataire" >Célibataire</option>
                            <option value="Veuf" >Veuf</option>
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="first_language">1ère langue <sup className='text-red-600'>*</sup></label>
                        <select id="first_language" value={formData?.ca_premiere_lang} name="ca_premiere_lang" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            <option value="Anglais">Anglais</option>
                            <option value="Français">Français</option>
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="nationalite">Nationalité <sup className='text-red-600'>*</sup></label>
                        <select id="nationalite" value={formData?.ca_nationalite} name="ca_nationalite" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            {data.map((d) => d.country).map((c, k) => (
                                <option value={c} key={k}>{c}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="region_origin">Région d'origine <sup className='text-red-600'>*</sup></label>
                        <select id="region_origin" name="ca_region_origine" value={formData?.ca_region_origine}  className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            {data.filter((d) => d.country === formData?.ca_nationalite)?.at(0)?.regions?.map((c, k) => (
                                <option value={c.region} key={k}>{c.region}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="department_origin">Département d'origine <sup className='text-red-600'>*</sup></label>
                        <select id="department_origin" name="ca_depart_origine" value={formData?.ca_depart_origin} className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            {formData?.ca_region_origine!==''&&data.filter((d) => d.country === formData?.ca_nationalite)?.at(0)?.regions?.filter((r) => r.region ===formData?.ca_region_origine).at(0).departments?.map((c, k) => (
                                
                                <option value={c} key={k}>{c}</option>
                               
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-5'>
                        <CustomCheckbox checked={hasDisability} label={"Cochez ici si vous souffrez d'un handicap"} onChange={onDisabilityChange}/>
                    </div>
                    <div className='flex flex-col gap-3 mb-5'>
                        
                       {/*  <label htmlFor="has_disability">
                            <input
                                type="checkbox"
                                id="has_disability"
                                name="has_disability"
                                checked={hasDisability}
                                
                                onChange={onDisabilityChange}
                            />
                            Souffre d'un handicap
                        </label> */}
                        {hasDisability && (
                        <input
                            type="text"
                            id="disability_description"
                            name="ca_handicap"
                            placeholder="Description du handicap"
                            className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
                            value={disabilityDescription}
                            onChange={onDisabilityDescriptionChange}
                        />
                        )}
                    </div>
                </div>
                <div className="flex justify-between">
                    <button
                        onClick={() => dispatch(prevStep())}
                        className={`px-4 flex items-center gap-4 py-2 rounded ${step === 1 ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === 1}
                    >
                    <LuArrowLeft />    Précédent
                    </button>
                    <button
                        className={`px-4 py-2 flex items-center gap-4 rounded ${step === totalSteps ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === totalSteps}
                    >
                        Suivant <LuArrowRight />
                    </button>
                </div>
            </form>
        </div>
    );
}

export default PersonnalInfo;
