import React, {useState} from 'react';
import {loginAPI} from '../api/routes/auth';
import {useNavigate} from 'react-router-dom';
import Loading from '../components/stepModal/Loading';
import {toast} from 'react-toastify';

function LoginPage() {
    const [formData, setFormData] = useState({
        login: '',
        password: '',
    });
    const [isLoad, setLoadingState] = useState(false)
    const navigate = useNavigate()

    function onChange(e) {
        setFormData({...formData, [e.target.name]: e.target.value});
    }

    function onSubmit(e) {
        e.preventDefault();
        // Handle form submission
        setLoadingState(true)
        try {
            loginAPI(formData).then(async (res) => {
                if (res.status === 200) {
                    setLoadingState(false)
                    const data = await res.json()
                    const {access_token, token_type, user, user_type, candidat} = data
                    sessionStorage.setItem('token', access_token)
                    sessionStorage.setItem('type_token', token_type)
                    sessionStorage.setItem('user', JSON.stringify(user))
                    sessionStorage.setItem('user_type', user_type)
                    if (user_type === 'candidat') {
                        sessionStorage.setItem("candidat", candidat)
                        window.location.href = '/success'
                        // return navigate('/success')
                    } else {
                        if (user_type === 'admin') {
                            window.location.href = '/admin'
                        } else {
                            //return navigate('/candidate')
                            window.location.href = '/candidate'
                        }
                    }
                } else if (res.status === 401) {
                    setLoadingState(false)
                    return toast.error("Vous etes déjà connecté, veuillez vous déconnecter d'abord...")
                } else {
                    setLoadingState(false)
                    return toast.error("Informations de connexion incorrect...")
                }
            })
        } catch (error) {
            setLoadingState(false)
            return toast.error("Informations de connexion incorrect...")
        }
    }

    return (
        <div className='w-full h-[90vh] flex justify-center  items-center'>
            <div
                className="w-full max-w-4xl p-8 bg-white shadow-lg rounded-md flex-col md:flex-row flex justify-between items-center">
                <div className='md:w-1/2 w-full'>
                    <h2 className='text-5xl font-bold text-teal-500'>
                        Veuillez vous connectez
                    </h2>
                    <form onSubmit={onSubmit} className='space-y-6'>
                        <div className='flex flex-col gap-3 mb-3'>
                            <label htmlFor="ca_prenom">Login (<i>N° reçu de paiement </i>)<sup
                                className='text-red-600'>*</sup></label>
                            <input
                                type="text"
                                id='ca_prenom'
                                name='login'
                                placeholder='Exp: 89632168'
                                className='p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1'
                                onChange={onChange}
                                value={formData.login}
                                required
                            />
                        </div>
                        <div className='flex flex-col gap-3 mb-3'>
                            <label htmlFor="password">Mot de passe<sup className='text-red-600'>*</sup></label>
                            <input
                                type="password"
                                id='password'
                                name='password'
                                placeholder='********'
                                className='p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1'
                                onChange={onChange}
                                value={formData.password}
                                required
                            />
                        </div>
                        <div className='flex flex-col gap-3 mb-3 text-center italic'>
                            <a href={'/pwd-recover'} className='text-sky-800'>Cliquez ici si vous avez oublié votre mot
                                de passe</a>
                        </div>
                        <button type="submit"
                                className='w-full py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700'>
                            Connectez-vous
                        </button>
                    </form>
                </div>
                <div className='w-1/2 flex justify-center items-center'>
                    <img className='w-3/4' src={require("../img/student2.png")} alt="Student"/>
                </div>
            </div>
            {isLoad && <Loading/>}
        </div>
    );
}

export default LoginPage;
