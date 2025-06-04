import React, { useState } from 'react';
import { fieldSet, notEmpty } from '../../utils/validation';
import { LuArrowRight } from 'react-icons/lu';
import {  LuArrowLeft } from 'react-icons/lu';
import data from '../../../data.json'
import {useDispatch} from "react-redux"
import { toast } from 'react-toastify';
import { push_candidate_info } from '../../app/modules/candidate';

function PersonnalInfo({onClose,isLoad,setLoadingState}) {
    const [formData, setFormData] = useState({});
    const [hasDisability, setHasDisability] = useState(false);
    const [disabilityDescription, setDisabilityDescription] = useState('');
    
    const dispatch = useDispatch()
    function onChange(e) {
        setFormData(prevData => ({
            ...prevData,
            [e.target?.name]: e.target?.value
        }));
    }
    React.useEffect(()=>{
        fieldSet('#form',setFormData,{ca_statut_mat:'Célibataire'})
        return () => {
            fieldSet('#form',setFormData,{ca_statut_mat:'Célibataire'})
          };
    },[])
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
                // Form processing logic
                setLoadingState(true)
                dispatch(push_candidate_info(formData))
                setTimeout(() => {
                    setLoadingState(false)
                    onClose()
                }, 2000);
            } else {
                // Handle empty form error
                toast.error("Veuillew remplir tous les champs...")
            }
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div>
            <form action="" method="post" onSubmit={onSubmit} id='form'>
                <div className="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="name">Nom <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='name' name="ca_nom" placeholder='Exp MAHOP MAHOP' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="lastname">Prénom <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='lastname' name="ca_prenom" placeholder='Exp BORIS JUNIOR' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="sexe">Votre sexe <sup className='text-red-600'>*</sup></label>
                        <select id="sexe" name="ca_sexe" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="birthdate">Date de naissance <sup className='text-red-600'>*</sup></label>
                        <input type="date" id='birthdate' name="ca_date_naiss" placeholder='Exp 27/08/2003' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="birthplace">Lieu de naissance <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='birthplace' name="ca_lieu_naiss" placeholder='Exp EDEA' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="contact">Contact <sup className='text-red-600'>*</sup></label>
                        <input type="tel" id='contact' name="ca_telephone" placeholder='Exp 698123456 ou +237698123456' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="cni">N° CNI <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='cni' name="ca_num_cni" placeholder='69812345612' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="residence">Résidence <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='residence' name="ca_resid" placeholder='Exp EDEA'className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="status_matrimonial">Status matrimonial <sup className='text-red-600'>*</sup></label>
                        <select defaultValue={'Célibataire'} id="status_matrimonial" name="ca_statut_mat" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            <option value="Marié">Marié</option>
                            <option value="Célibataire" >Célibataire</option>
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="first_language">1ère langue <sup className='text-red-600'>*</sup></label>
                        <select id="first_language" name="ca_premiere_lang"  className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            <option value="Anglais">Anglais</option>
                            <option value="Français">Français</option>
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="nationalite">Nationalité <sup className='text-red-600'>*</sup></label>
                        <select id="nationalite" name="ca_nationalite" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            {data.map((d)=>d.country).map((c,k)=>(
                                <option value={c} key={k}>{c}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="region_origin">Région d'origine <sup className='text-red-600'>*</sup></label>
                        <select id="region_origin" name="ca_region_origine" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            {data.filter((d)=>d.country ===formData?.ca_nationalite)?.at(0)?.regions?.map((c,k)=>(
                                <option value={c.region} key={k}>{c.region}</option>
                            ))}
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="department_origin">Département d'origine <sup className='text-red-600'>*</sup></label>
                        <select id="department_origin" name="ca_depart_origin" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                            <option value="">Selectionner </option>
                            {data.filter((d)=>d.country ===formData?.ca_nationalite)?.at(0)?.regions?.map((r)=>r.departments).at(0).map((c,k)=>(
                                <option value={c} key={k}>{c}</option>
                            ))}
                        </select>
                    </div>
        <div className='flex flex-col gap-3 mb-3'>
          <label htmlFor="has_disability">
            <input
              type="checkbox"
              id="has_disability"
              name="has_disability"
              checked={hasDisability}
              onChange={onDisabilityChange}
            />
            Souffre d'un handicap
          </label>
          {hasDisability && (
            <input
              type="text"
              id="disability_description"
              name="disability_description"
              placeholder="Description du handicap"
              className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'
              value={disabilityDescription}
              onChange={onDisabilityDescriptionChange}
            />
          )}
        </div>
                </div>
<div className="flex items-center justify-between">
                        <button type='submit' className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                            <LuArrowLeft /> Précédent
                        </button>
                        <button type='submit' className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                            Suivant <LuArrowRight />
                        </button>
                </div>            </form>
        </div>
    );
}

export default PersonnalInfo;
