import React, { useState } from 'react';
import { LuArrowRight } from 'react-icons/lu';
import { LuArrowLeft } from 'react-icons/lu';
import { fieldSet, notEmpty } from '../../utils/validation';
import { useDispatch } from 'react-redux';
import { push_candidate_info } from '../../app/modules/candidate';
import { toast } from 'react-toastify';

function ConcourInfo({ onClose, isLoad, setLoadingState }) {
  const [formData, setFormData] = useState({});
  /*const [centreDepot,setCentreDepot] = useState({})
  const [centreExamen,setCentreExamen] = useState({})
  function fetchCentreExamen(){
    
  }*/
  React.useEffect(() => {
    fieldSet('#form_concours', setFormData, {})
    return () => {
      fieldSet('#form_concours', setFormData, {})
    };
  }, [])
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
        toast.error("Veuillew remplir tous les champs...")
      }
    } catch (error) {
      console.error(error);
    }
  }

  return (
    <div>
      <form action="" method="post" id='form_concours' onSubmit={onSubmit}>
        <div className="grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="centre_exam">Centre d'examen <sup className='text-red-600'>*</sup></label>
            <input type="text" id='centre_exam' list='exam_center' name="ca_centre_examen" placeholder='Exp: Lycée Bilingue d Abam' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
            <datalist id='exam_center'>
              <option value="Grand Bafousam"></option>
            </datalist>
          </div>
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="centre_depot">Centre de dépot <sup className='text-red-600'>*</sup></label>
            <input type="text" id='centre_depot' name="ca_centre_depot" placeholder='Exp: Lycée Bilingue d Abam' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
          </div>
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="site_formation">Site de formation <sup className='text-red-600'>*</sup></label>
            <input type="text" id='site_formation' name="ca_centre_examen" placeholder='DOUALA' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
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

export default ConcourInfo;
