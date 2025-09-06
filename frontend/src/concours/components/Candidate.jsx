import React, { useEffect, useRef } from 'react';
import { LuArrowBigDown, LuClipboardCheck, LuCoins, LuFileQuestion, LuGraduationCap, LuSchool, LuUser } from 'react-icons/lu';
import CandidateStep from './CandidateStep';
import PaymentInfo from './stepModal/PaymentInfo';
import PersonnalInfo from './stepModal/PersonnalInfo';
import AcademiqueInfo from './stepModal/AcademiqueInfo';
import ConcourInfo from './stepModal/ConcourInfo';
import OtherInfo from './stepModal/OtherInfo';
import Confirmation from './stepItem/Confirmation';
import Modal from './Modal';
import { useNavigate } from 'react-router-dom';
import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';
import { LuFileDown} from 'react-icons/lu'
import { Link } from 'react-router-dom'

function Candidate({ isLoad, setLoadingState }) {
    const [showModal, setModalShow] = React.useState(false);
    const token = localStorage.getItem('token');
    const candidat = localStorage.getItem('candidat');
    const navigate = useNavigate();
    const viewerRef = useRef(null);
    const startRef = useRef(null);
    const driverObjRef = useRef(null);

    function onClick() {
        if (token) {
            return navigate('/candidate');
        } else {
            return setModalShow(true);
        }
    }

    useEffect(() => {
        if (!sessionStorage.getItem('tutorial')) {
            sessionStorage.setItem('tutorial', true);
        }
        if (viewerRef.current && !sessionStorage.getItem('tutorialCompleted')) {
            driverObjRef.current = driver({
                showProgress: true,
                smoothScroll: true,
                onDestroyStarted: function () {
                    sessionStorage.setItem('tutorialCompleted', true);
                    if (driverObjRef.current && !driverObjRef.current.hasNextStep()) {
                        driverObjRef.current.destroy();
                    }
                },
                showButtons: ['next', 'previous', 'close'],
                steps: [
                    {
                        element: viewerRef.current,
                        popover: {
                            title: "INSCRIPTION",
                            description: "Pour commencer l'inscription, veuillez cliquer sur ce bouton"
                        }
                    },
                    {
                        element: startRef.current,
                        popover: {
                            title: "INSCRIPTION",
                            description: "Veuillez commencer"
                        }
                    }
                ]
            });
            driverObjRef.current.drive(0);
        }

        return () => {
            if (driverObjRef.current) {
                driverObjRef.current.destroy();
            }
        };
    }, []);

    function onClose() {
        setModalShow(false);
    }

    return (
        <div className='flex flex-col justify-center items-center py-8 px-4 md:px-8' ref={viewerRef}>
            
            {(candidat === null || candidat === 'null') ?<div className="w-full md:w-3/4 flex flex-col gap-10 items-center justify-center">
                <div className=' text-center md:text-left'>
                    <h2 className='text-3xl font-bold uppercase text-teal-600 mb-5'>
                        Candidater au concours
                    </h2>
                    <p className='text-center'>
                        Pour candidater, il va falloir suivre ces étapes
                    </p>
                </div>
                <div className='flex flex-col gap-2 items-center'>
                    <LuArrowBigDown size={80} className='animate-bounce duration-300 text-teal-500' />
                    <p>Cliquez sur le bouton pour commencer</p>
                    <button ref={startRef} onClick={onClick} className='p-3  bg-teal-500 border border-teal-500 text-white rounded-md'>
                        Commencer l'inscription
                    </button>
                </div>
                <div className='grid items-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10'>
                    <CandidateStep Icon={LuCoins} stepNumber={'1'} title={'Informations de paiement'} children={<PaymentInfo />} isLoad={isLoad} setLoadingState={setLoadingState} />
                    <CandidateStep Icon={LuUser} stepNumber={'2'} title={'Informations personnelles'} children={<PersonnalInfo />} isLoad={isLoad} setLoadingState={setLoadingState} />
                    <CandidateStep Icon={LuSchool} stepNumber={'3'} title={'Informations académiques'} children={<AcademiqueInfo />} isLoad={isLoad} setLoadingState={setLoadingState} />
                    <CandidateStep Icon={LuGraduationCap} stepNumber={'4'} title={'Informations liées au concours'} children={<ConcourInfo />} isLoad={isLoad} setLoadingState={setLoadingState} />
                    <CandidateStep Icon={LuFileQuestion} stepNumber={'5'} title={'Autres informations'} children={<OtherInfo />} isLoad={isLoad} setLoadingState={setLoadingState} />
                    <CandidateStep Icon={LuClipboardCheck} stepNumber={'6'} title={'Confirmation des pièces exigées'} children={<Confirmation />} isLoad={isLoad} setLoadingState={setLoadingState} />
                </div>
                <Modal show={showModal} isLoad={isLoad} setLoadingState={setLoadingState} onClose={onClose} title={'Informations de paiement'} children={<PaymentInfo />} />
            </div>
            : <div className='flex items-center py-12 justify-center flex-col'>
                <div className="text-center mb-3 p-5 text-xl font-bold rounded-lg bg-green-400/50">
                        Félicitations !!! Votre inscription s'est déroulée avec success. Cliquez sur le bouton ci-dessous pour télécharger votre fiche d'inscription.
                </div>
                <Link to="/affiche-data" className='p-3 bg-teal-500 text-white rounded-md flex items-center gap-2'>Télécharger votre fiche <LuFileDown size={20} /> </Link>
            </div> }
        </div>
    )
}

export default Candidate;
