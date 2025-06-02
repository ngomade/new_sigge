import React, { useEffect, useState } from 'react';
import { LuArrowLeft } from 'react-icons/lu';
import { useDispatch, useSelector } from 'react-redux';
import { toast } from 'react-toastify';
import { initStep, prevStep } from '../../app/modules/stepper';
import CustomCheckbox from '../CustomCheckbox';
import { createCandidate } from '../../api/routes/candidate';
import { useNavigate } from 'react-router-dom';
import { getDossier } from '../../api/routes/concours';

function Confirmation({ setLoadingState }) {
    const [formData, setFormData] = useState({
        /* birth_certificate: false,
        diploma: false,
        school_attendance: false,
        medical_certificate: false,
        bank_receipt: false,
        envelope: false */
    });
    const [dossiers, setDossier] = useState([]);
    const { finish, ...userData } = useSelector((state) => state.candidate.candidate_state);
    const sessionData = sessionStorage.getItem("session");
    const { id } = sessionData ? JSON.parse(sessionData) : {};

    function onChange(e) {
        const { name, checked } = e.target;
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
    const navigate = useNavigate();

    function onSubmit(e) {
        e.preventDefault();
        const allChecked = Object.values(formData).every(value => value);
        if (allChecked) {
            setLoadingState(true);
            createCandidate({ ...userData, id })
                .then(async res => {
                    if (res.status === 201) {
                        setLoadingState(false);
                        dispatch(initStep());
                        const data = await res.json()
                        console.log(data)
                        sessionStorage.setItem("candidat",JSON.stringify(data))
                        window.location.href ='/success'
                    } else {
                        setLoadingState(false);
                        const data = await res.json()
                        console.log(JSON.stringify(data));
                        return toast.error(data[0], {autoClose: 5000});
                    }
                })
                .catch(error => {
                    setLoadingState(false);
                    console.error('Error creating candidate:', error);
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
                            <CustomCheckbox checked={formData?.[d.label_el]} onChange={onChange} id={d.code_el} name={d.label_el} />
                            <label htmlFor={d.code_el} dangerouslySetInnerHTML={{__html:d.label_el}}></label>
                        </div>
                    ))}
                </div>
                <div className="flex items-center justify-between">
                    <button type='button' onClick={() => dispatch(prevStep())} className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                        <LuArrowLeft /> Précédent
                    </button>
                    <button type='submit' className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                        S'inscrire Maintenant
                    </button>
                </div>
            </form>
        </div>
    );
}

export default Confirmation;
