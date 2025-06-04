import React, { useState } from 'react';
import { fieldSet, notEmpty } from '../../utils/validation';
import { LuArrowRight } from 'react-icons/lu';
import { LuArrowLeft} from 'react-icons/lu';
import { useDispatch } from 'react-redux';
import { push_candidate_info } from '../../app/modules/candidate';
import { toast } from 'react-toastify';

function OtherInfo({onClose,isLoad,setLoadingState}) {
  
  const [formData, setFormData] = useState({});
  
  function onChange(e) {
    setFormData(prevData => ({
      ...prevData,
      [e.target?.name]: e.target?.value
    }));
  }
  React.useEffect(()=>{
    fieldSet('#form_other_info',setFormData,{})
    return () => {
        fieldSet('#form_other_info',setFormData,{})
      };
},[])
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
      <form action="" method="post" onSubmit={onSubmit} id='form_other_info'>
        <div className="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="name">Nom complet du père / Tuteur <sup className='text-red-600'>*</sup></label>
            <input type="text" id='name' name="ca_nom_pere" placeholder='Exp NONO MARTIN' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
          </div>
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="num">Numero de téléphone du père / Tuteur<sup className='text-red-600'>*</sup></label>
            <input type="tel" id='num' name="ca_telephone_pere" placeholder='Exp 697123455 ou +237697123455'className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
          </div>
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="email">Email</label>
            <input type="text" id='email' name="ca_email_pere" placeholder='Exp Nonomartin2004@gmail.com'className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
          </div>
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="mum_name">Nom complet de la mère / Tuteur<sup className='text-red-600'>*</sup></label>
            <input type="text" id='mum_name' name="ca_nom_mere" placeholder='Exp Mamouchantou@gmail.com' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
          </div>
          <div className='flex flex-col gap-3 mb-3'>
            <label htmlFor="mum_num">Numero de téléphone de la mère<sup className='text-red-600'>*</sup></label>
            <input type="tel" id='mum_num' name="ca_telephone_mere" placeholder='Exp 697123455 ou +237697123455' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
          </div>
          
          {/* <div className='col-span-1 mb-5 md:col-span-2 lg:col-span-3'>
            <p className='text-red-500'>
              NB : Photo en fond Blanc !!. Les formats autorisés sont .jpg .png .jpeg Taille Max 2Mo. Dimension maximale 400x400.
            </p>
          </div> */}
          {/* <div className='flex flex-col gap-3 mb-3 relative w-full md:w-[200px] md:h-[200px] bg-teal-50 cursor-pointer border border-teal-200 rounded-lg'>
            <input type="file" id='carte_photo' onChange={onFileSelected} accept='.jpg,.png,.jpeg' className='z-10 w-full h-full p-2 border opacity-0 appearance-none file:cursor-pointer file:appearance-none file:bg-transparent file:border bg-teal-50' />
            <div className='flex items-center justify-center'>
              <span className='absolute text-5xl text-center text-teal-500 top-1/3'>
                <LuFileUp />
              </span>
            </div>
          </div> */}
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

export default OtherInfo;