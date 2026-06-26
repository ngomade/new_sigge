import React, { useState } from 'react';
import { LuArrowRight } from 'react-icons/lu';
import { LuArrowLeft } from 'react-icons/lu';
import { fieldSet, notEmpty } from '../../utils/validation';
import { push_candidate_info } from '../../app/modules/candidate';
import { useDispatch } from 'react-redux';
import { toast } from 'react-toastify';

function AcademiqueInfo({onClose,isLoad,setLoadingState}) {
    const [formData, setFormData] = useState({});
    React.useEffect(()=>{
        fieldSet('#form_aca',setFormData,{})
        return () => {
            fieldSet('#form_aca',setFormData,{})
          };
    },[])
    function onChange(e) {
        setFormData(prevData => ({
            ...prevData,
            [e.target?.name]: e.target?.value
        }));
    }
    const dispatch = useDispatch()
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
          toast.error("Veuillez remplir tous les champs...")
      }
  } catch (error) {
      console.error(error);
  }
}

    return (
        <div className='mt-12'>
            <form action="" method="post" id='form_aca' onSubmit={onSubmit}>
                <div className="grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="filiere_admission">Filière admission <sup className='text-red-600'>*</sup></label>
                        <select name="ca_diplome_admission" id="filiere_admission" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1'>
                            <option value="">Selectionner</option>
                        </select>
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="serie">Série <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='serie' name="ca_serie_diplome" placeholder='INFORMATIQUE' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="annee_diplome">Année diplôme <sup className='text-red-600'>*</sup></label>
                            <select id='ca_annee_diplome' name="ca_annee_diplome" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15'>
                                  {Array.from({ length: 20 }, (_, i) => new Date().getFullYear() - i).map(year => (
                           <option key={year} value={year}>{year}</option>
        ))}
    </select>
</div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="mention">Mention<sup className='text-red-600'>*</sup></label>
                                <select id='mention' name="ca_mention_diplome" className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
                                     <option value="">Sélectionner une mention</option>
                                     <option value="TRÈS BIEN">TRÈS BIEN</option>
                                     <option value="BIEN">BIEN</option>
                                     <option value="ASSEZ BIEN">ASSEZ BIEN</option>
                                     <option value="PASSABLE">PASSABLE</option>
    </select>
</div>

                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="etablissement">Etablissement <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='etablissement' name="ca_etab_diplome" placeholder='ESTLC' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="pays_diplome">Pays de diplome <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='pays_diplome' name="ca_pays_diplome" placeholder='CAMEROUN' list='list_pays' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                        <datalist id='list_pays'>
                            <option value="Togo">Togo</option>
                            <option value="Cameroon">Cameroon</option>
                        </datalist>
                    </div>
                </div>
    <div className="flex items-center justify-between">
                        <button type='submit' className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                            <LuArrowLeft /> Précédent
                        </button>
                        <button type='submit' className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                            Suivant <LuArrowRight />
                        </button>
                </div>
            </form>
        </div>
    );
}

export default AcademiqueInfo;
