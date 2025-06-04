import React from 'react';
import { IoDownload } from 'react-icons/io5';
import { Slide } from 'react-slideshow-image';
import 'react-slideshow-image/dist/styles.css';

const divStyle = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundSize: 'cover',
    height: '400px'
}
function HomeHeader() {
    const sliderImg = [
        {
            img: require("../img/sliders/report.png"),
            caption: "Report du concours de l'ESTLC pour le 17 octobre 2024. Date limite de dépôt des dossiers le 16 Octobre 2024"
        },
        {
            img: require("../img/sliders/report.png"),
            caption: "Report du concours de l'ESTLC pour le 17 Octobre 2024. Date limite de dépôt des dossiers le 16 Octobre 2024"
        },
        {
            img: require("../img/sliders/img.jpeg"),
            caption: "L'ESTLC à la quête de l'excellence pour une formation intégrale de l'étudiant."
        },
        {
            img: require("../img/sliders/img2.jpeg"),
            caption: "Des compétences techniques pour un monde meilleur. La technologie en action, votre avenir en marche"
        },
        {
            img: require("../img/sliders/img3.jpeg"),
            caption: "Devenez l'ingénieur de demain. Des ingénieurs d'exception pour un avenir durable"
        },
        {
            img: require("../img/sliders/img4.jpeg"),
            caption: "Innover aujourd'hui, construire demain. Façonnez le futur avec nous"
        },
        {
            img: require("../img/sliders/img5.jpeg"),
            caption: "L'ingénierie au service de vos rêves.Transformez vos idées en réalités"
        },
        {
            img: require("../img/sliders/img6.jpeg"),
            caption: "L'excellence en ingénierie commence ici. Créez, concevez, changez le monde"
        },
        {
            img: require("../img/sliders/img7.jpeg"),
            caption: "Votre passion, notre mission Osez l'innovation, intégrez notre école"
        },
        {
            img: require("../img/sliders/img8.jpeg"),
            caption: "Un monde d'opportunités s'ouvre à vous"
        },
        {
            img: require("../img/sliders/img9.jpeg"),
            caption: "Révélez votre potentiel d'ingénieur"
        }
    ];
    
    React.useEffect(() => {

        const handleScroll = () => {
            const header = document.querySelector("#header");
            if (header) {
                if (window.pageYOffset > 100) {
                    header.classList.add("duration-500");
                } else {
                    header.classList.remove("duration-500");
                }
            }
        };

        window.addEventListener("scroll", handleScroll);

        return () => {
            window.removeEventListener("scroll", handleScroll);
        };
    }, []);
    return (
        <div id='header' className="flex justify-center items-center p-5 transition ">
            <div className="w-full md:w-[90%]">
                <div className="grid grid-cols-1 md:grid-cols-2 items-center gap-10 p-5">
                    <div className="flex flex-col p-2 gap-5">
                        <h1 style={{ lineHeight: '3.5rem' }} className="text-3xl  tracking-wide  md:text-4xl font-bold text-center md:text-left">
                            Bienvenue sur notre plateforme d'inscription de <span className="text-teal-500 text-5xl underline">ESTLC</span> !
                        </h1>
                        <p className="text-center md:text-left">
                            {/* Nous vous recommandons de télécharge l'arrêté de lancement du concours qui pourra vous être utile plus tard. Faites-le en cliquant sur le bouton suivant en fonction de votre préférence. */}
                            LES ÉPREUVES ÉCRITES DU CONCOURS D'ENTREE À L'ESTLC INITIALEMENT PREVUES LE 26 SEPTEMBRE 2024 SONT REPORTÉES AU JEUDI, 17 OCTOBRE 2024. 
                            LA DATE LIMITE DE DOSSIER QUANT À ELLE EST PROROGÉE AU MERCREDI 16 OCTOBRE À 15H30.
                        </p>
                        <div className="flex flex-col md:flex-row items-center justify-between gap-3">
                            <a className="px-4 py-3 rounded-lg bg-teal-500 flex items-center gap-3 text-white" href='/concours/arrete_ESTLC_EN_2024.pdf' target='_blank'
                            rel="noopener noreferrer">
                                Version Anglaise <IoDownload size={25} />
                            </a>
                            <a className="px-4 py-3 rounded-lg bg-teal-500 flex items-center gap-3 text-white" href='/concours/arrete_ESTLC_FR_2024.pdf' target='_blank'
                            rel="noopener noreferrer">
                                Version Française <IoDownload size={25} />
                            </a>
                        </div>
                        <p className="text-sm text-center md:text-left">
                            Veuillez remplir toutes les informations nécessaires pour le concours d'entrée à notre école. Si vous avez des requêtes, veuillez les indiquer ici pour que nous puissions y répondre au mieux.
                        </p>
                        <p className="text-lg text-center md:text-justify shadow-lg rounded-xl py-6">
                            <div className='p-5 text-center'>
                            Pour tout problème d'inscription, veuillez contacter par WhatsApp les numeros suivant: <span className='text-blue-500 underline'><a href="https://api.whatsapp.com/send?phone=694915442" target='_blank' rel="noreferrer">694915442</a></span>, <span  className='text-blue-500'><a href="https://api.whatsapp.com/send?phone=695021036" className='underline' target='_blank' rel="noreferrer">695021036</a></span> ou ecrire à l'adresse  <a href="mailto:
                            estlc@estlc.unv-ebolowa.cm"  className='text-blue-500 underline'>estlc@estlc.unv-ebolowa.cm</a>
                            </div>
                        </p>
                    </div>
                    <div className="w-full md:w-auto">
                        <Slide>
                            {sliderImg.map((slideImage, index) => (
                                <div key={index}>
                                    <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.img})` }} className='relative'>
                                        <span className='absolute bottom-0 w-full bg-teal-200/50 p-3 text-xl font-bold'>{slideImage?.caption}</span>
                                    </div>
                                </div>
                            ))}
                        </Slide>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default HomeHeader;
