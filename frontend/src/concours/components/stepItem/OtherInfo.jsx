import React, { useState } from 'react';
import { fieldSet, notEmpty } from '../../utils/validation';
import { LuArrowRight } from 'react-icons/lu';
import { LuArrowLeft } from 'react-icons/lu';
import { useDispatch, useSelector } from 'react-redux';
import { push_candidate_info } from '../../app/modules/candidate';
import { toast } from 'react-toastify';
import { nextStep, prevStep } from '../../app/modules/stepper';

function OtherInfo({ setLoadingState }) {
    const step = useSelector((state) => state.stepper.step);
    const totalSteps = useSelector((state) => state.stepper.totalSteps);
    const [formData, setFormData] = useState({});
    const { finish, ...userData } = useSelector((state) => state.candidate.candidate_state);

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

    React.useEffect(() => {
        // Initialisation depuis localStorage si dispo
        const localData = localStorage.getItem('candidate_step_data');
        if (localData) {
            setFormData(JSON.parse(localData));
        } else if (userData && Object.keys(userData).length > 0) {
            setFormData(userData);
        } else {
            fieldSet('#form_other_info', setFormData, {})
        }
        return () => {
            setLoadingState(false)
            fieldSet('#form_other_info', setFormData, {})
        };
    }, [setLoadingState, userData]);

    const dispatch = useDispatch();

    function onSubmit(e) {
        try {
            e.preventDefault();
            if (notEmpty(formData, ["ca_email_pere"])) {
                setLoadingState(true);
                // Merge des anciennes données avec les nouvelles
                dispatch(push_candidate_info({ ...userData, ...formData }));
                setTimeout(() => {
                    setLoadingState(false);
                    dispatch(nextStep());
                }, 2000);
            } else {
                // Handle empty form error
                toast.error("Veuillew remplir tous les champs...");
            }
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div>
            <h1 className='text-3xl font-bold text-teal-500'>
                Informations complémentaires
            </h1>
            <form action="" method="post" onSubmit={onSubmit} id='form_other_info'>
                <div className="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-2">
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="name">Nom complet du père / Tuteur <sup className='text-red-600'>*</sup></label>
                        <input type="text" id='name' value={formData.ca_nom_pere} name="ca_nom_pere" placeholder='Exp NONO MARTIN' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="num">Numero de téléphone du père / Tuteur<sup className='text-red-600'>*</sup></label>
                        <input type="tel" id='num' name="ca_telephone_pere" value={formData.ca_telephone_pere} placeholder='Exp 697123455' minLength={9} maxLength={9} className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="mum_name">Nom complet de la mère / Tuteur<sup className='text-red-600'>*</sup></label>
                        <input type="text" id='mum_name' name="ca_nom_mere" value={formData.ca_nom_mere} placeholder='Exp MAMOU chantou' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                    <div className='flex flex-col gap-3 mb-3'>
                        <label htmlFor="mum_num">Numero de téléphone de la mère<sup className='text-red-600'>*</sup></label>
                        <input type="tel" id='mum_num' name="ca_telephone_mere" value={formData.ca_telephone_mere} placeholder='Exp 697123455' minLength={9} maxLength={9} className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange} />
                    </div>
                </div>
                <div className="flex justify-between">
                    <button
                        type='button'
                        onClick={() => dispatch(prevStep())}
                        className={`px-4 flex items-center gap-4 py-2 rounded ${step === 1 ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === 1}
                    >
                        <LuArrowLeft />  Précédent
                    </button>
                    <button
                        type='submit'
                        className={`px-4 flex items-center gap-4 py-2 rounded ${step === totalSteps ? 'bg-gray-300 cursor-not-allowed' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                        disabled={step === totalSteps}
                    >
                        Suivant <LuArrowRight />
                    </button>
                </div>
            </form>
        </div>
    );
}

export default OtherInfo;

