import React, {useState} from 'react';
import {Link} from 'react-router-dom';
import {createPortal} from 'react-dom';
import {persistor} from '../app/store';
import {useDispatch} from 'react-redux';
import {logout} from '../app/modules/candidate';
import {LuMenu} from 'react-icons/lu';
import {logoutAPI} from "../api/routes/auth";
import {toast} from "react-toastify";

function Navbar() {
    //const [isOpen, setIsOpen] = React.useState(false);

    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [hasToken, setToken] = useState('')
    const [isLoggingOut, setIsLoggingOut] = useState(false);

    const candidat = sessionStorage.getItem('candidat');

    const toggleMobileMenu = () => {
        setIsMobileMenuOpen(!isMobileMenuOpen);
    };
    const dispatch = useDispatch()

    function logoutAction() {
        setIsLoggingOut(true);
        try {
            logoutAPI().then((r) => {
                if (r.status === 200) {
                    dispatch(logout())
                    persistor.purge()
                    sessionStorage.removeItem('candidat');
                    sessionStorage.removeItem('user_type');
                    sessionStorage.removeItem('type_token');
                    window.location.href = '/'
                    toast.success('Déconnexion réussie');
                }else {
                    setIsLoggingOut(false);
                    toast.error("Erreur lors de la déconnexion");
                }
            })
        } catch (e) {
            toast.error("Erreur lors de la déconnexion")
            setIsLoggingOut(false);
        }
    }

    React.useEffect(() => {
        const token = sessionStorage.getItem("token")
        setToken(token)
        const handleScroll = () => {
            if (window.pageYOffset > 100) {
                document.querySelector("nav").classList.add("fixed", "top-0", "transition", "duration-300", "w-full", "bg-white", "shadow-lg");
            } else {
                document.querySelector("nav").classList.remove("fixed", "top-0", "transition", "duration-300", "w-full", "bg-white", "shadow-lg");
            }
        };

        window.addEventListener("scroll", handleScroll);

        return () => {
            window.removeEventListener("scroll", handleScroll);
        };
    }, []);

    return (
        <div>
            <div className='bg-teal-400 w-full p-2 flex items-center justify-center'>
                <h1 className='text-white text-center'>
                    Inscription en cours {(candidat === 'null' || candidat === null) ?
                    <Link to={'/candidate'}>Veuillez vous inscrire </Link> : ""}
                </h1>
            </div>
            <nav className='shadow flex justify-between items-center z-[500] px-8 py-5 '>
                <div>
                    <Link to={''}>
                        <img className='w-24 h-12' src={require("../img/logo.png")} alt=""/>
                    </Link>
                </div>
                <ul className='hidden md:flex items-center gap-8'>
                    <li>
                        <Link to={'/'}>Accueil</Link>
                    </li>
                    <li>
                        <Link to={'/site-exam'}>Nos sites</Link>
                    </li>
                    <li>
                        <Link to={'/ancienne-epreuve'}>Nos Anciennes épreuves</Link>
                    </li>
                    <li>
                        <Link to={'/faq'}>FaQ</Link>
                    </li>
                </ul>
                <div className='hidden md:block'>
                    {
                        hasToken !== null ? (<div className='flex gap-2'>
                            <Link to={'/success'} className='p-2 text-white bg-slate-400 rounded-md'>Mon Compte</Link>
                            <button className='p-2 text-white bg-red-600 rounded-md'
                                    onClick={logoutAction} disabled={isLoggingOut}
                            >        {isLoggingOut ? "Déconnexion..." : "Déconnexion"}
                            </button>
                        </div>) : <Link to={'/login'} className='p-2 text-white bg-teal-400 rounded-md'>Connexion</Link>
                    }
                </div>
                <button
                    className='md:hidden'
                    onClick={toggleMobileMenu}
                >
                    <LuMenu/>
                </button>
            </nav>

            {isMobileMenuOpen && createPortal(
                <div className='fixed inset-0 bg-gray-800 bg-opacity-75 flex flex-col items-center justify-center z-50'>
                    <button
                        className='absolute top-4 right-4 text-white'
                        onClick={toggleMobileMenu}
                    >
                        ✕
                    </button>
                    <ul className='flex flex-col items-center gap-8 text-white'>
                        <li>
                            <Link to={'/'} onClick={toggleMobileMenu}>Acceuil</Link>
                        </li>
                        <li>
                            <Link to={'/site-exam'} onClick={toggleMobileMenu}>Nos sites</Link>
                        </li>
                        <li>
                            <Link to={'/ancienne-epreuve'} onClick={toggleMobileMenu}>Anciennes épreuves</Link>
                        </li>

                        <li>
                            {
                                hasToken !== null ? (<div className='flex gap-2'>
                                    <Link to={'/success'} className='p-2 text-white bg-slate-400 rounded-md'>Mon
                                        Compte</Link>
                                    <Link to={'/logout'} className='p-2 text-white bg-red-600 rounded-md'
                                          onClick={logoutAction}>Déconnexion</Link>
                                </div>) : <Link to={'/login'} className='p-2 text-white bg-teal-400 rounded-md'
                                                onClick={toggleMobileMenu}>Connexion</Link>
                            }
                        </li>
                    </ul>
                </div>,
                document.body
            )}
        </div>
    );
}

export default Navbar;
