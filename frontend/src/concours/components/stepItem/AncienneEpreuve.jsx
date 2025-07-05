function AncienneEpreuvePage() {
    return (
        <div className="flex items-center justify-center flex-col mt-5">
            <div className='w-50 shadow shadow-green-800 rounded p-6 mb-5'>
                <h1 className="text-2xl font-bold text-center mb-5">Nos Anciennes épreuves</h1>
                <h3 className="text-2xl text-center mb-5">Veuillez télécharger nos anciennes épreuves afin de mieux vous
                    préparer au concours qui aura lieu le 27 Septembre pour les premières années et </h3>

                <div id="accordion-open" data-accordion="collapse">
                    <h2 id="accordion-open-heading-1">
                        <button type="button"
                                className="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 hover:bg-gray-100"
                                data-accordion-target="#accordion-open-body-1" aria-expanded="true"
                                aria-controls="accordion-open-body-1">
                                <span className="flex text-lg font-bold items-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                           viewBox="0 0 24 24" fill="none" stroke="currentColor"

                                           strokeWidth="2" strokeLinecap="round" stroke-linejoin="round"
                                           className="lucide lucide-file-text-icon lucide-file-text me-2"><path
                                          d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path
                                          d="M14 2v4a2 2 0 0 0 2 2h4"/><path
                                          d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                      Epreuves de 2024
                                </span>
                            <svg data-accordion-icon="" className="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-open-body-1" className="hidden" aria-labelledby="accordion-open-heading-1">
                        <div className="p-5 border border-b-0 border-gray-200">
                            <h2 className="text-center">Aucun diplome pour le moment disponible pour cette année !</h2>
                        </div>
                    </div>

                    <h2 id="accordion-open-heading-2">
                        <button type="button"
                                className="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 hover:bg-gray-100"
                                data-accordion-target="#accordion-open-body-2" aria-expanded="true"
                                aria-controls="accordion-open-body-2">
              <span className="flex text-lg font-bold items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                       viewBox="0 0 24 24" fill="none" stroke="currentColor"

                       strokeWidth="2" strokeLinecap="round" stroke-linejoin="round"
                       className="lucide lucide-file-text-icon lucide-file-text me-2"><path
                      d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path
                      d="M14 2v4a2 2 0 0 0 2 2h4"/><path
                      d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                  Epreuves de 2023</span>
                            <svg data-accordion-icon="" className="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-open-body-2" className="hidden" aria-labelledby="accordion-open-heading-2">
                        <div className="p-5 border border-b-0 border-gray-200">
                            <div className='grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-5'>
                                <div className='shadow shadow-green-300 rounded m-auto p-1'>
                                    <a className="px-4  py-3 rounded-lg text-black"
                                       href='/concours/epreuves/Epreuve_de_Maths_EBTTL.pdf'
                                       target='_blank'
                                       rel="noopener noreferrer"
                                    >
                                        <img src={require('../../img/pdf.jpg')} alt="Mathématiques TTL"
                                             className='w-20 rounded m-auto'/>
                                        <div className='block mt-5'> Epreuve de Mathématiques TTL 2023</div>
                                    </a>
                                </div>
                                <div className='shadow shadow-green-300 rounded m-auto p-1'>
                                    <a className="px-4  py-3 rounded-lg text-black"
                                       href='/concours/epreuves/Epreuve_Specialite_EBTTL.pdf'
                                       target='_blank'
                                       rel="noopener noreferrer">
                                        <img src={require('../../img/pdf.jpg')} alt="Epreuve de Spécialité TTL"
                                             className='w-20 rounded m-auto'/>
                                        <div className='block mt-5'>Epreuve de Spécialité TTL 2023</div>
                                    </a>
                                </div>
                                <div className='shadow shadow-green-300 rounded m-auto p-1'>
                                    <a className="px-4  py-3 rounded-lg text-black"
                                       href='/concours/epreuves/Epreuve_de_Culture_Generale.pdf' target='_blank'
                                       rel="noopener noreferrer">
                                        <img src={require('../../img/pdf.jpg')} alt="Epreuve de Culture Générale"
                                             className='w-20 rounded m-auto'/>
                                        <div className='block mt-5'>Epreuve de Culture Générale 2023</div>
                                    </a>
                                </div>
                                <div className='shadow shadow-green-300 rounded m-auto p-1'>
                                    <a className="px-4  py-3 rounded-lg text-black"
                                       href='/concours/epreuves/Epreuve_de_Maths_GLTCO.pdf'
                                       target='_blank'
                                       rel="noopener noreferrer">
                                        <img src={require('../../img/pdf.jpg')}
                                             alt="Epreuve de Mathématiques GLTCO 2023"
                                             className='w-20 rounded m-auto'/>
                                        <div className='block mt-5'>Epreuve de Mathématiques GLTCO 2023</div>
                                    </a>
                                </div>
                                <div className='shadow shadow-green-300 rounded m-auto p-1'
                                     rel="noopener noreferrer">
                                    <a className="px-4  py-3 rounded-lg text-black"
                                       href='/concours/epreuves/Epreuve_Specialite_GLTCO.pdf'
                                       target='_blank'>
                                        <img src={require('../../img/pdf.jpg')} alt="Epreuve de Specialite GLTCO 2023"
                                             className='w-20 rounded m-auto'/>
                                        <div className='block mt-5'>Epreuve de Specialite GLTCO 2023</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default AncienneEpreuvePage;
