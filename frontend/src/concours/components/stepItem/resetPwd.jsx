import React, {useState} from 'react';
import {resetPwdAPIWithToken} from '../../api/routes/auth';
import {toast} from 'react-toastify';

function PwdReset() {
    const [formData, setFormData] = useState({
        token: '',
        password: '',
        confirmPassword: ''
    });
    const [isLoading, setIsLoading] = useState(false);

    function onChange(e) {
        setFormData({...formData, [e.target.name]: e.target.value});
    }

    async function onSubmit(e) {
        e.preventDefault();
        if (formData.password !== formData.confirmPassword) {
            toast.error("Les mots de passe ne correspondent pas");
            return;
        }
        setIsLoading(true);
        try {
            const res = await resetPwdAPIWithToken({
                token: formData.token,
                password: formData.password
            });
            const data = await res.json();
            if (res.status === 200) {
                setFormData(
                    {token: '', password: '', confirmPassword: ''} // Reset form after success
                )
                toast.success(data.message || "Mot de passe réinitialisé avec succès");
            } else {
                toast.error(data.message || "Erreur lors de la réinitialisation");
            }
        } catch (err) {
            toast.error("Erreur réseau");
        }
        setIsLoading(false);
    }

    return (
        <div className="w-full h-[90vh] flex justify-center items-center">
            <div className="shadow shadow-green-800 mb-10 mt-10 rounded">
                <div className="card">
                    <div className="border-2 border-gray-300 text-center text-lg mb-5">
                        Réinitialisation du mot de passe
                    </div>
                    <div className="card-body p-10">
                        <form onSubmit={onSubmit}>
                            <div className="flex flex-col gap-3 mb-3">
                                <label>Code de vérification reçu par mail</label>
                                <input
                                    type="text"
                                    name="token"
                                    minLength={5}
                                    maxLength={5}
                                    required
                                    className="p-2 border border-teal-600 rounded-md"
                                    onChange={onChange}
                                />
                                <label>Nouveau mot de passe</label>
                                <input
                                    type="password"
                                    name="password"
                                    required
                                    className="p-2 border border-teal-600 rounded-md"
                                    onChange={onChange}
                                />
                                <label>Confirmer le mot de passe</label>
                                <input
                                    type="password"
                                    name="confirmPassword"
                                    required
                                    className="p-2 border border-teal-600 rounded-md"
                                    onChange={onChange}
                                />
                            </div>
                            <button
                                type="submit"
                                className="w-1/2 p-2 text-white bg-teal-600 rounded-md"
                                disabled={isLoading}
                            >
                                {isLoading ? "Traitement..." : "Réinitialiser"}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default PwdReset;