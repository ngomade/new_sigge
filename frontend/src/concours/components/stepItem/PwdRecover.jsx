import React, { useState } from 'react'
import { resetPwdAPI } from '../../api/routes/auth';
import { toast } from 'react-toastify';
import Loading from "../stepModal/Loading";

function PwdRecover() {
    const [formData, setFormData] = useState({
        login: ''
      });
      const [isLoad,setLoadingState] = useState(false)
      const [isVisible, setIsVisible] = useState(false);
      const [email, setEmail] = useState("");
      
      const hideElement = () => {
        setIsVisible(true);
      };
    
      const [notif,setNotifState] = useState("")
    function onChange(e) {
        setFormData({ ...formData, [e.target.name]: e.target.value });
      }
      function showLoading(){
        setIsVisible(true);
        setNotifState("<i style='color:green;'>Veuillez patientez nous effectuons des recherche....</i>")
      }

    function onSubmit(e) {
        e.preventDefault();
        setLoadingState(true)
        try {
            resetPwdAPI(formData).then(async(res)=>{
            if(res.status===200){
                setLoadingState(false)
                const data = await res.json()
                const {message, email} = data
                setEmail(email)
                hideElement()
                setNotifState(message)
            }else{
                const data = await res.json()
                const {message} = data
                setLoadingState(false)
                hideElement()
                setNotifState("<i style='color:red;'>"+message+"</i>")
                return toast.error(message)
            }
            })
        } catch (error) {
            hideElement()
            setLoadingState(false)
            setNotifState("<i style='color:red;'>Informations de connexion incorrect...</i>")
            return toast.error("Informations de connexion incorrect...")
        }
    }

    return(
        <div className="w-full h-[90vh] flex justify-center  items-center">
            <div className="col-start-3 col-span-2 shadow shadow-green-800 mb-10 mt-10 rounded">
                <div className="card">
                    <div className="border-2 border-gray-300 text-center text-lg mb-5" >Reinitialisation de mot de passe</div>
                    <div className="card-body p-10">
                        <div className='text-center text-sm italic mb-5'>Veuillez entrer le numéro de reçu afin que nous puissons vous reinitialiser votre motre de passe</div>
                        {/*<div className='text-center text-sm italic mb-5'>*/}
                        {/*    <Link to={"/reset-pwd"} className={"underline text-blue-500"}>je possede un code de réinitialisation !</Link>*/}
                        {/*</div>*/}
                        <form  onSubmit={onSubmit}>
                            <div className="flex flex-col gap-3 mb-3">
                                <label htmlFor="ca_nom">
                                N° de reçu<sup className="text-red-600">*</sup> <i>(exactement 6 chiffres)</i>
                                </label>
                                <input
                                type="text"
                                id="login"
                                name="login"
                                placeholder="Exp 040206"
                                minLength={6}
                                maxLength={6}
                                required
                                className="p-2 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
                                onChange={onChange}
                                />
                            </div>
                            <div className="w-full flex items-center justify-center">
                            <button type="submit" className="w-1/2 p-2 text-white bg-teal-600 rounded-md" onClick={showLoading}>
                                Envoyer
                            </button>
                                {isLoad && <Loading/>}
                        </div>
                        </form>
                        {isVisible &&
                            <div className='grid bg-white font-bold text-lg grid-cols-1 h-15 w-85 place-items-center  m-auto shadow-lg shadow-green-800 rounded p-3 '>
                                <div className='text-justify p-3 text-l text-gray-800 leading-10' dangerouslySetInnerHTML={{__html:notif}}/>
                                { email && <p>Le mot de passe a été envoyé a l'adresse : {email}</p>}
                            </div>
                        }
                    </div>
                </div>
            </div>
        </div>
    )
}
export default PwdRecover;