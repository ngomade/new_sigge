import React, { useState } from 'react';
import { LuArrowRight } from 'react-icons/lu';
import { LuArrowLeft } from 'react-icons/lu';
import { useDispatch } from 'react-redux';
import { toast } from 'react-toastify';

function Confirmation({ onClose, isLoad, setLoadingState }) {
    const [formData, setFormData] = useState({
        birth_certificate: false,
        diploma: false,
        school_attendance: false,
        medical_certificate: false,
        bank_receipt: false,
        envelope: false
    });

    function onChange(e) {
        const { name, checked } = e.target;
        setFormData(prevData => ({
            ...prevData,
            [name]: checked
        }));
    }

    const dispatch = useDispatch();

    function onSubmit(e) {
        try {
            e.preventDefault();
            const allChecked = Object.values(formData).every(value => value);
            if (allChecked) {
                // Form processing logic
                setLoadingState(true);
                dispatch((formData));
                setTimeout(() => {
                    setLoadingState(false);
                    onClose();
                }, 2000);
            } else {
                // Handle empty form error
                toast.error("Veuillez cocher tous les champs requis...");
            }
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div className='mt-12'>
            <form id='form_docs' onSubmit={onSubmit}>
                <div className="grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div className='flex items-center gap-3 mb-3'>
                        <input type="checkbox" id='birth_certificate' name="birth_certificate" onChange={onChange} />
                        <label htmlFor="birth_certificate">Une photocopie certifiée de l'acte de naissance datant de moins de trois (03) mois</label>
                    </div>
                    <div className='flex items-center gap-3 mb-3'>
                        <input type="checkbox" id='diploma' name="diploma" onChange={onChange} />
                        <label htmlFor="diploma">Une photocopie certifiée conforme du diplôme/attestation de réussite du Baccalauréat ou du GCE A/L ou du diplôme équivalent</label>
                    </div>
                    <div className='flex items-center gap-3 mb-3'>
                        <input type="checkbox" id='school_attendance' name="school_attendance" onChange={onChange} />
                        <label htmlFor="school_attendance">Un certificat de scolarité de la classe de terminale/upper sixth</label>
                    </div>
                    <div className='flex items-center gap-3 mb-3'>
                        <input type="checkbox" id='medical_certificate' name="medical_certificate" onChange={onChange} />
                        <label htmlFor="medical_certificate">Un certificat médical délivré par un médecin fonctionnaire, datant de moins de trois (03) mois et certifiant que le candidat est apte à poursuivre des études supérieures</label>
                    </div>
                    <div className='flex items-center gap-3 mb-3'>
                        <input type="checkbox" id='bank_receipt' name="bank_receipt" onChange={onChange} />
                        <label htmlFor="bank_receipt">Un reçu de versement bancaire d'un montant de vingt mille (20 000) francs CFA représentant les droits d'inscription au concours de l'ESTLC de l'Université d'Ebolowa, à verser au compte SCB Cameroun, N° 10002.00059.90001487491.26 dans toutes les agences du Cameroun</label>
                    </div>
                    <div className='flex items-center gap-3 mb-3'>
                        <input type="checkbox" id='envelope' name="envelope" onChange={onChange} />
                        <label htmlFor="envelope">Une enveloppe A4 timbrée au tarif réglementaire et portant l'adresse exacte du candidat</label>
                    </div>
                </div>
                      <div className="flex items-center justify-between">
                        <button type='submit' className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                            <LuArrowLeft /> Précédent
                        </button>
                        <button type='submit' className='flex items-center justify-center gap-2 p-2 text-white bg-teal-600 rounded-md'>
                            Enregistrer <LuArrowRight />
                        </button>
                </div>
        </form>
        </div>
    );
}

export default Confirmation;
