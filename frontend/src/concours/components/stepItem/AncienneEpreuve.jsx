function AncienneEpreuvePage() {
  return (
    <div className="flex items-center justify-center flex-col mt-5">
      <div className='w-50 shadow shadow-green-800 rounded p-6 mb-5'>
        <h1 className="text-2xl font-bold text-center mb-5">Nos Anciennes épreuves</h1>
        <h3 className="text-2xl text-center mb-5">Veuillez télécharger nos anciennes épreuves afin de mieux vous préparer au concours qui aura lieu le 28 Septembre</h3>
          <div>
            <div className='grid items-center grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-5'>
              <div className='shadow shadow-green-300 rounded m-auto p-1'>
                <a className="px-4  py-3 rounded-lg text-black" href='/concours/epreuves/Epreuve_de_Maths_EBTTL.pdf' target='_blank'
                   rel="noopener noreferrer"
                >
                  <img src={require('../../img/pdf.jpg')} alt="Mathématiques TTL" className='w-20 rounded m-auto' />
                  <div className='block mt-5'> Epreuve de Mathématiques TTL 2023</div>
                </a>                
              </div>
              <div className='shadow shadow-green-300 rounded m-auto p-1'>
                <a className="px-4  py-3 rounded-lg text-black" href='/concours/epreuves/Epreuve_Specialite_EBTTL.pdf' target='_blank'
                rel="noopener noreferrer">
                  <img src={require('../../img/pdf.jpg')} alt="Epreuve de Spécialité TTL" className='w-20 rounded m-auto'/>
                  <div className='block mt-5'>Epreuve de Spécialité TTL 2023</div>
                </a>                
              </div>
              <div className='shadow shadow-green-300 rounded m-auto p-1'>
                <a className="px-4  py-3 rounded-lg text-black" href='/concours/epreuves/Epreuve_de_Culture_Generale.pdf' target='_blank'
                   rel="noopener noreferrer">
                  <img src={require('../../img/pdf.jpg')} alt="Epreuve de Culture Générale" className='w-20 rounded m-auto'/>
                  <div className='block mt-5'>Epreuve de Culture Générale 2023</div>
                </a>                
              </div>
              <div className='shadow shadow-green-300 rounded m-auto p-1'>
                <a className="px-4  py-3 rounded-lg text-black" href='/concours/epreuves/Epreuve_de_Maths_GLTCO.pdf' target='_blank'
                   rel="noopener noreferrer">
                  <img src={require('../../img/pdf.jpg')} alt="Epreuve de Mathématiques GLTCO 2023" className='w-20 rounded m-auto'/>
                  <div className='block mt-5'>Epreuve de Mathématiques GLTCO 2023</div>
                </a>                
              </div>
              <div className='shadow shadow-green-300 rounded m-auto p-1'
                   rel="noopener noreferrer">
                <a className="px-4  py-3 rounded-lg text-black" href='/concours/epreuves/Epreuve_Specialite_GLTCO.pdf' target='_blank'>
                  <img src={require('../../img/pdf.jpg')} alt="Epreuve de Specialite GLTCO 2023" className='w-20 rounded m-auto'/>
                  <div className='block mt-5'>Epreuve de Specialite GLTCO 2023</div>
                </a>                
              </div>
            </div>
          </div>
      </div>
    </div>
  );
}

export default AncienneEpreuvePage;
