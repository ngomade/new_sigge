import React, {useState, useEffect} from 'react';
import { Eye, EyeOff, FileUp, Loader2, ScanLine } from 'lucide-react';
import {fieldSet, notEmpty} from '../../utils/validation';
import {toast} from 'react-toastify';
import {createCompte} from '../../api/routes/compte';
import {useDispatch} from 'react-redux';
import {push_candidate_info} from '../../app/modules/candidate';

function PaymentInfo({onClose, isLoad, setLoadingState}) {
    const [, setImageObject] = useState("");
    const [formData, setFormData] = useState({
        ca_nom: '',
        ca_prenom: '',
        ca_email: '',
        ca_num_recu: '',
        ca_pwd: '',
        ca_confirm_pwd: '',
        ca_recu: null
    });
    const [showPassword, setPasswordShow] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    //const [isExtracting, setIsExtracting] = useState(false);
    const [selectedFile, setSelectedFile] = useState(null);
    //const [ocrData, setOcrData] = useState(null);

    const dispatch = useDispatch();

    useEffect(() => {
        fieldSet('#form_pay', setFormData, {});
        return () => {
            fieldSet('#form_pay', setFormData, {});
        };
    }, []);

    /* Fonction pour extraire les données avec OCR
    async function extractDataWithOCR(file) {
        setIsExtracting(true);
        const formData = new FormData();
        formData.append('receipt', file);

        try {
            const response = await fetch('http://localhost:8000/api/concours/comptes/extract-receipt', {
                method: 'POST',
                // headers: {
                //     'Authorization': Bearer ${localStorage.getItem('token')},
                // },
                body: formData
            });

            const result = await response.json();
            console.log('Réponse reçue:', result);
            
            if (result.success && result.data) {
                setOcrData(result.data);
                
                // Pré-remplir le formulaire avec les données extraites
                setFormData(prevData => ({
                    ...prevData,
                    ca_nom: result.data.ca_nom || '',
                    ca_prenom: result.data.ca_prenom || '',
                    ca_email: result.data.ca_email || '',
                    ca_num_recu: result.data.ca_num_recu || '',
                    ca_recu: file
                }));

                toast.success("Données extraites avec succès ! Vérifiez et complétez si nécessaire.");
            } else {
                toast.warning("Impossible d'extraire toutes les données. Veuillez les saisir manuellement.");
            }
        } catch (error) {
            console.error('Erreur OCR:', error);
            toast.error("Erreur lors de l'extraction des données");
            
        } finally {
            setIsExtracting(false);
        }
    }*/

    function onChange(e) {
        setFormData(prevData => ({
            ...prevData,
            [e.target.name]: e.target.value,
        }));
    }

    async function onSubmit(e) {
        e.preventDefault();
        if (notEmpty(formData)) {
            const {ca_pwd, ca_confirm_pwd} = formData;
            if (ca_confirm_pwd !== ca_pwd) {
                return toast.error("Les mots de passe ne sont pas identiques", {autoClose: 5000});
            }
            setLoadingState(true);
            setIsSubmitting(true);
            const {ca_recu, ...data} = formData;
            try {
                const response = await createCompte(data, ca_recu);
                if (response.ok) {
                    setLoadingState(false);
                    setIsSubmitting(false);
                    onClose();
                    const data = await response.json()
                    const {access_token, compte, user, user_type} = data
                    localStorage.setItem('token', access_token)
                    localStorage.setItem('user', JSON.stringify(user))
                    localStorage.setItem('user_type', user_type)
                    setFormData({
                        ca_nom: '',
                        ca_prenom: '',
                        ca_email: '',
                        ca_num_recu: '',
                        ca_pwd: '',
                        ca_confirm_pwd: '',
                        ca_recu: null
                    });
                    dispatch(push_candidate_info(compte));
                    toast.success("Les informations ont été soumises avec succès");
                    window.location.href = "/candidate";
                } else {
                    const error = await response.json()
                    const {erreur} = error
                    setLoadingState(false);
                    setIsSubmitting(false);
                    return toast.error(erreur, {autoClose: 5000});
                }
            } catch (error) {
                setIsSubmitting(false);
                setLoadingState(false);
                toast.error("Une erreur s'est produite lors de la soumission des informations", {autoClose: 5000});
                console.error(error);
            }
        } else {
            toast.error("Veuillez remplir tous les champs...");
        }
    }

    function showPwd() {
        setPasswordShow(!showPassword);
    }

    async function onFileSelected(e) {
        try {
            const file = e.target.files[0];
            if (!file) return;

            const src = URL.createObjectURL(file);
            setFormData(prevData => ({
                ...prevData,
                [e.target.name]: file,
            }));
            setImageObject(src);
            setSelectedFile(file);

            // Lancer automatiquement l'extraction OCR
            //await extractDataWithOCR(file);
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div className="flex flex-col gap-5 lg:flex-row" id="form_pay">
            <div className="flex-1">
                <form onSubmit={onSubmit}>
                    {/* Afficher l'état de l'extraction OCR */}
                    {(
                        <div className="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md flex items-center gap-2">
                            <Loader2 className="animate-spin text-blue-600" size={20} />
                            <span className="text-blue-700">Extraction des données en cours...</span>
                        </div>
                    )}

                    {(
                        <div className="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                            <div className="flex items-center gap-2 mb-2">
                                <ScanLine className="text-green-600" size={20} />
                                <span className="text-green-700 font-semibold">Données extraites automatiquement</span>
                            </div>
                            <p className="text-sm text-green-600">Vérifiez et complétez les informations si nécessaire.</p>
                        </div>
                    )}

                    <div className="flex flex-col gap-3 mb-3">
                        <div className="relative flex flex-col gap-3 mb-3">
                            <label htmlFor="recu_image">
                                Votre reçu<sup className="text-red-600">*</sup>
                                {selectedFile && <span className="text-sm text-green-600 ml-2">✓ Fichier sélectionné</span>}
                            </label>
                            <input
                                type="file"
                                id="recu_image"
                                name="ca_recu"
                                onChange={onFileSelected}
                                accept=".pdf,.jpg,.jpeg,.png"
                                className="z-10 p-2 border opacity-0 appearance-none file:appearance-none file:bg-transparent file:border"
                            />
                            <span className="absolute text-5xl text-teal-500 top-9 left-3">
                                <FileUp/>
                            </span>
                            <p className="text-red-500">
                                Reçu scanné en PDF, ou en Image( jpg, png, jpeg ) ne dépassant pas 2 Mo
                            </p>
                        </div>

                        <label htmlFor="ca_nom">
                            Nom<sup className="text-red-600">*</sup> <i>(En Majuscule)</i>
                        </label>
                        <input
                            type="text"
                            id="ca_nom"
                            name="ca_nom"
                            placeholder="Exp MAHOP MAHOP"
                            className="p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
                            onChange={onChange}
                            value={formData.ca_nom || ''}
                        />
                    </div>
                    <div className="flex flex-col gap-3 mb-3">
                        <label htmlFor="ca_prenom">
                            Prenom<sup className="text-red-600">*</sup> <i>(En Majuscule)</i>
                        </label>
                        <input
                            type="text"
                            id="ca_prenom"
                            name="ca_prenom"
                            placeholder="Exp BORIS JUNIOR"
                            className="p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
                            onChange={onChange}
                            value={formData.ca_prenom || ''}
                        />
                    </div>
                    <div className="flex flex-col gap-3 mb-3">
                        <label htmlFor="ca_email">
                            Email<sup className="text-red-600">*</sup> <i>(Veuillez renseigner une adresse valide)</i>
                        </label>
                        <input
                            type="email"
                            id="ca_email"
                            name="ca_email"
                            placeholder="Exp cicsoestlc@gmail.com"
                            className="p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
                            onChange={onChange}
                            value={formData.ca_email || ''}
                        />
                    </div>
                    <div className="flex flex-col gap-3 mb-3">
                        <label htmlFor="ca_num_recu">
                            N° de reçu<sup className="text-red-600">*</sup> <i>(exactement 6 chiffres)</i>
                        </label>
                        <input
                            type="text"
                            id="ca_num_recu"
                            name="ca_num_recu"
                            placeholder="056210"
                            className="p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
                            onChange={onChange}
                            value={formData.ca_num_recu || ''}
                            // maxLength={6}
                            // minLength={6}
                        />
                    </div>
                    <div className="flex flex-col gap-3 mb-3">
                        <label htmlFor="password">
                            Votre mot de passe<sup className="text-red-600">*</sup> <i>(minimum 8 caractères)</i>
                        </label>
                        <div className="relative w-full">
                            <input
                                type={showPassword ? "text" : "password"}
                                id="password"
                                name="ca_pwd"
                                placeholder="869532@erdsd12"
                                className="p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1 w-full"
                                onChange={onChange}
                                value={formData.ca_pwd || ''}
                                minLength={8}
                            />
                            <div className="absolute top-2 flex text-teal-500 cursor-pointer right-2" onClick={showPwd}>
                                {showPassword ? <EyeOff size={25}/> : <Eye size={25}/>}
                            </div>
                        </div>
                    </div>
                    <div className="flex flex-col gap-3 mb-3">
                        <label htmlFor="confirm_password">
                            Confirmer votre mot de passe<sup className="text-red-600">*</sup>
                        </label>
                        <div className="relative w-full">
                            <input
                                type={showPassword ? "text" : "password"}
                                id="confirm_password"
                                name="ca_confirm_pwd"
                                placeholder="869532@erdsd12"
                                className="p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1 w-full"
                                onChange={onChange}
                                value={formData.ca_confirm_pwd || ''}
                            />
                            <div className="absolute top-2 flex text-teal-500 cursor-pointer right-2" onClick={showPwd}>
                                {showPassword ? <EyeOff size={25}/> : <Eye size={25}/>}
                            </div>
                        </div>
                    </div>
                    <div className="w-full flex items-center justify-center">
                        <button
                            type="submit"
                            className="w-1/2 p-2 text-white bg-teal-600 rounded-md"
                            disabled={isSubmitting }
                        >
                            Soumettre
                        </button>
                    </div>
                </form>
            </div>
            <div id="preview" className="flex-1">
                <div className="bg-gray-100 p-4 rounded-md shadow-md">
                    <h2 className="text-lg font-bold mb-2">Extraction automatique des données</h2>
                    <ul className="list-disc list-inside text-sm text-gray-700 leading-7">
                        <li>Téléchargez votre reçu et l'IA extraira automatiquement les informations</li>
                        <li>Vérifiez que les données extraites sont correctes</li>
                        <li>Complétez les champs manquants si nécessaire</li>
                        <li>Les images seront automatiquement converties en PDF lors de l'enregistrement</li>
                    </ul>
                </div>
                <div className="bg-gray-100 p-4 rounded-md shadow-md mt-5">
                    <h2 className="text-lg font-bold mb-2">Notes :</h2>
                    <ul className="list-disc list-inside text-l text-gray-800 leading-10">
                        <li>Remplissez tous les champs obligatoires marqués d'un astérisque (*).</li>
                        <li>Assurez-vous que les mots de passe sont identiques.</li>
                        <li>Téléchargez une image de votre reçu en format PDF, ne dépassant pas 2 Mo.</li>
                        <li>Cliquez sur "Soumettre" pour envoyer vos informations.</li>
                        <li>En cas de succès, vous serez invité à continuer votre inscription .</li>
                    </ul>
                </div>
                <div className="text-lg text-center md:text-justify shadow-inner shadow-green-800 rounded p-6 animate-bounce">
                    <p>Apres la création de votre compte, vous recevrez par mail vos informations de connexion. veuillez
                        vérifier vos spams également.</p>
                </div>
            </div>
        </div>
    );
}

export default PaymentInfo;