import React from 'react'
import {LuFileDown, LuUserCheck} from 'react-icons/lu'
import {Link} from 'react-router-dom'

function SuccessPage() {
    //const ca_store = JSON.stringify(sessionStorage.getItem("user"))
    const candidat = localStorage.getItem('candidat');
    return (
        <div className='flex items-center py-12 justify-center flex-col'>
            {
                (candidat !== "null") ? (
                        <div className="text-center mb-3 p-5 text-xl font-bold rounded-lg bg-green-400/50">
                            Félicitations !!! Votre inscription s'est déroulée avec success. Cliquez sur le bouton
                            ci-dessous pour télécharger votre fiche d'inscription.
                        </div>
                    ) :
                    (
                        <div className="text-center mb-3 p-5 text-xl font-bold rounded-lg bg-amber-400/50">
                            Félicitations !!! Votre compte a été crée avec success. Cliquez sur le bouton ci-dessous
                            pour complèter votre candidature.
                        </div>
                    )
            }
            <hr/>
            {/* <h1 className='text-2xl mb-3'>
            En cliquant sur le bouton suivant, vous téléchargez votre fiche d'inscription.
        </h1> */}
            <div className='mb-3'>
                <h2 className='text-lg font-bold mb-3'>
                    Consignes:
                </h2>
                <ol className=' list-decimal leading-10'>
                    <li>
                        Bien vouloir imprimer imprimer la fiche d'inscription en couleur sous peine d'un rejet de
                        candidature;
                    </li>
                    <li>
                        Faire timbrer la fiche et la signer avant tout dépôt;
                    </li>
                    <li>
                        Bien vouloir enregistrer et les conserver de manière confidentielle;
                    </li>
                    <li>
                        En cas de perte de cette fiche bien vouloir vous connecter sur cette plateforme avec vos
                        identifiants;
                    </li>
                </ol>
            </div>
            {
                (candidat !== "null") ? (
                    <Link to="/affiche-data" className='p-3 bg-teal-500 text-white rounded-md flex items-center gap-2'>Télécharger
                        votre fiche <LuFileDown size={20}/> </Link>
                ) : (
                    <Link to="/candidate"
                          className='p-3 bg-amber-600 text-white rounded-md flex items-center gap-2'><LuUserCheck
                        size={20}/> Complèter votre candidature</Link>
                )
            }
        </div>
    )
}

export default SuccessPage