import React, { useEffect, useState } from 'react'
import { getCandidateStat } from '../../../api/routes/candidate';
import { toast } from 'react-toastify';



function Dashboard() {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    getCandidateStat()
      .then((response) => {
        if (!response.ok) {
          return toast.error("Nous rencontrons des problèmes veuillez contacter l'administrateur")
        }
        return response.json();
      })
      .then((data) => {
        setData(data);
        console.log(data.candidats[1].filiere_code === data.filiere[0].code_filiere)
        setLoading(false);
      })
      .catch((error) => {
        setError(error);
        setLoading(false);
        return toast.error(error)
      });
  }, []); 
  if (loading) {
    return <div>Loading...</div>;
  }
  if (error) {
    return <div>Error: {error}</div>;
  }
  return (
   <div>
    <div className='grid items-top grid-cols-6 gap-3 md:grid-cols-7 lg:grid-cols-7'>
    <h1 className='text-xl text-center text-teal-600 mb-1 font-bold col-span-5'>APERCU GENERALE DES STATISTIQUES</h1>
    <div className='items-center text-center'>
          <button className='bg-teal-600  rounded-lg text-white mb-1 p-2 w-50 text-rigth'>Exporter en Excel</button>
        </div>
    </div>
     <div className='grid items-top grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-5'>
     <div className="flex flex-col items-center justify-center  rounded-3xl mr-5 border-2 border-gray-300 p-1">
        <div className="text-lg text-gray-700 mb-2">Nombre Total de Candidat </div>
        <div className='items-center'>
          <div className='text-2xl shadow-green-800 font-bold text-green-800'>{data['total']}</div>
        </div>
      </div>
      <div className='col-span-4'>
          <div className="flex flex-col items-center justify-center flex-initial w-30  mr-5 border-2 border-gray-300 p-1">
            <div className="text-lg text-gray-700 mb-1">Candidat - ESTLC</div>
            <div>
              <div className='text-xl shadow-green-800 font-bold text-green-800'>{data['t_estlc']} <span className='text-sm'>({((data['t_estlc']/data["total"])*100).toFixed(2)}%)</span></div>
            </div>
          </div>
        <div className='grid items-center grid-cols-2 gap-2 md:grid-cols-2 lg:grid-cols-2 text-center'>
          <div className='rounded-3xl mr-3 border-2 border-gray-300 w-30 p-1 mt-1 items-center justify-center flex-initial'>
            <div className="text-lg text-gray-700 mb-1 shadow-green-800 font-bold text-green-800">GLTCO : <span>{data['total_gltco_estlc']}
            <span className='text-sm mr-6'>({((data['total_gltco_estlc']/data["t_estlc"])*100).toFixed(2)}%)</span></span></div>
            <div>
              <div  className='text-xl shadow-green-800 font-bold text-green-800'>
                <span className='text-sm'>{data['total_f_gltco_estlc']} F</span>
                <span className='text-xs mr-3'>({((data['total_f_gltco_estlc']/data["total_gltco_estlc"])*100).toFixed(1)}%)</span>
                <span className='text-sm'>{data['total_g_gltco_estlc']} G</span>
                <span className='text-xs'>({((data['total_g_gltco_estlc']/data["total_gltco_estlc"])*100).toFixed(1)}%)</span>
              </div>
            </div>
          </div>
          <div className='rounded-3xl mr-3 border-2 border-gray-300 w-30 p-1 mt-1 items-center justify-center flex-initial'>
            <div className="text-lg text-gray-700 mb-1 text-xl shadow-green-800 font-bold text-green-800">TTL : {data['total_ttl_estlc']} <span className='text-sm'>({((data['total_ttl_estlc']/data["t_estlc"])*100).toFixed(2)}%)</span></div>
            <div>
              <div className='text-xl shadow-green-800 font-bold text-green-800'>
              <span className='text-sm'>{data['total_f_ttl_estlc']} F</span>
                <span className='text-xs mr-3'>({((data['total_f_ttl_estlc']/data["total_ttl_estlc"])*100).toFixed(1)}%)</span>
                <span className='text-sm'>{data['total_g_ttl_estlc']} G</span>
                <span className='text-xs'>({((data['total_g_ttl_estlc']/data["total_ttl_estlc"])*100).toFixed(1)}%)</span>
               </div>
            </div>
          </div>
        </div>
      </div>
      {/* <div className='col-span-2'>
          <div className="flex flex-col items-center justify-center flex-initial w-30  mr-5 border-2 border-gray-300 p-1">
            <div className="text-lg text-gray-700 mb-1">Candidat - ISLAPE</div>
            <div>
              <div className='text-xl shadow-green-800 font-bold text-green-800'>{data['t_islape']} <span className='text-sm'>({((data['t_islape']/data["total"])*100).toFixed(2)}%)</span></div>
            </div>
          </div>
        <div className='grid items-center grid-cols-2 gap-2 md:grid-cols-2 lg:grid-cols-2 text-center'>
          <div className='rounded-3xl mr-3 border-2 border-gray-300 w-30 p-1 mt-2 items-center justify-center flex-initial'>
            <div className="text-lg text-gray-700 mb-1 text-xl shadow-green-800 font-bold text-green-800">GLTCO : {data['total_gltco_islape']} <span className='text-sm'>({((data['total_gltco_islape']/data["t_islape"])*100).toFixed(2)}%)</span>

            </div>
            <div>
              <div className='text-xl shadow-green-800 font-bold text-green-800'>
               <span className='text-sm'>{data['total_f_gltco_islape']} F</span>
                <span className='text-xs mr-3'>({((data['total_f_gltco_islape']/data["total_gltco_islape"])*100).toFixed(1)}%)</span>
                <span className='text-sm'>{data['total_g_gltco_islape']} G</span>
                <span className='text-xs'>({((data['total_g_gltco_islape']/data["total_gltco_islape"])*100).toFixed(1)}%)</span>
              </div>
            </div>
          </div>
          <div className='rounded-3xl mr-3 border-2 border-gray-300 w-30 p-1 mt-1 items-center justify-center flex-initial'>
            <div className="text-lg text-gray-700 mb-1 text-xl shadow-green-800 font-bold text-green-800">TTL : {data['total_ttl_islape']} <span className='text-sm'>({((data['total_ttl_islape']/data["t_islape"])*100).toFixed(2)}%)</span></div>
            <div>
              <div className='text-xl shadow-green-800 font-bold text-green-800'>
                <span className='text-sm'>{data['total_f_ttl_islape']} F</span>
                <span className='text-xs mr-3'>({((data['total_f_ttl_islape']/data["total_ttl_islape"])*100).toFixed(1)}%)</span>
                <span className='text-sm'>{data['total_g_ttl_islape']} G</span>
                <span className='text-xs'>({((data['total_g_ttl_islape']/data["total_ttl_islape"])*100).toFixed(1)}%)</span>
              </div>
            </div>
          </div>
        </div>
      </div> */}
    </div>
    <hr />
    <div className='grid items-top grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-2 text-center mt-1 text-center'>
      <div className='flex-initial items-center justify-center text-center'>
        <h1 className='text-xl text-center text-gray-900 mb-5'>Répartition par Filière</h1>
        <table className="text-center border-collapse border border-slate-400 min-w-full overflow-x-auto">
        <thead className="text-xs text-gray-700 uppercase dark:text-gray-400">
          <tr>
            <th scope="col" className="px-3 py-3 border border-slate-300">N° </th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Filiere</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">F</th>
            <th scope="col" className="px-3 py-3 border border-slate-300"> G</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">T</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">%</th>
          </tr>
        </thead>
        <tbody>
        {
            data.filiere.map((fil, k) => (
              
              <tr key={k} className="bg-white border dark:bg-gray-800 dark:border-gray-700 text-xs">
                <td className="px-3 py-3 border border-slate-300" key={k}>{k+1}</td>
                <td className="px-3 py-3 border border-slate-300 text-left">{fil["code_filiere"]} - {fil["label_filiere"]} </td>
                <td className="px-3 py-3 border border-slate-300">{data.candidats.filter(candidat=>candidat.filiere_code === fil["code_filiere"] && candidat.ca_sexe ==="Féminin").length}</td>
                <td className="px-3 py-3 border border-slate-300">{data.candidats.filter(candidat=>candidat.filiere_code === fil["code_filiere"] && candidat.ca_sexe ==="Masculin").length}</td>
                <td className="px-3 py-3 border border-slate-300">{data.candidats.filter(candidat=>candidat.filiere_code === fil["code_filiere"]).length}</td>
                <td className="px-3 py-3 border border-slate-300">{(data.candidats.filter(candidat=>candidat.filiere_code === fil["code_filiere"]).length/data['total']*100).toFixed(2)}</td>
                
              </tr>
              
            ))
        }
        <tr>
          <td colSpan={2} className="px-3 py-3 border border-slate-300 font-bold text-xl">Total</td>
          <td>{data['nb_f_estlc'] + data['nb_f_islape']}</td>
          <td>{data['nb_g_estlc'] + data['nb_g_islape']}</td>
          <td>{data['total']}</td>
        </tr>
        </tbody>
      </table>
      </div>
      
      <div className='flex-initial items-center justify-center text-center'>
        <h1 className='text-xl text-center text-gray-900 mb-5'>Répartition par centre d'examen</h1>
        <table className="text-center border-collapse border border-slate-400 min-w-full overflow-x-auto">
        <thead className="text-xs text-gray-700 uppercase dark:text-gray-400">
          <tr>
            <th scope="col" className="px-3 py-3 border border-slate-300">N° </th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Centre</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">F</th>
            <th scope="col" className="px-3 py-3 border border-slate-300"> G</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">T</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">%</th>
          </tr>
        </thead>
        <tbody>
        {
            data.centre_examen.map((sE, k) => (
              
              <tr key={k} className="bg-white border dark:bg-gray-800 dark:border-gray-700 text-xs">
                <td className="px-3 py-3 border border-slate-300" key={k}>{k+1}</td>
                <td className="px-3 py-3 border border-slate-300 text-left">{sE["centre_exam_label"]}</td>
                <td className="px-3 py-3 border border-slate-300">{data.candidats.filter(candidat=>parseInt(candidat.ca_centre_examen) === sE["centre_exam_code"] && candidat.ca_sexe ==="Féminin").length}</td>
                <td className="px-3 py-3 border border-slate-300">{data["candidats"].filter(candidat=>parseInt(candidat.ca_centre_examen) === sE['centre_exam_code'] && candidat.ca_sexe ==="Masculin").length}</td>
                <td className="px-3 py-3 border border-slate-300">{data.candidats.filter(candidat=>parseInt(candidat.ca_centre_examen) === sE["centre_exam_code"]).length}</td>
                <td className="px-3 py-3 border border-slate-300">{(data.candidats.filter(candidat=>parseInt(candidat.ca_centre_examen) === sE["centre_exam_code"]).length/data['total']*100).toFixed(2)}</td>
                
              </tr>
              
            ))
        }
        <tr>
          <td colSpan={2} className="px-3 py-3 border border-slate-300 font-bold text-xl">Total</td>
          <td>{data['nb_f_estlc'] + data['nb_f_islape']}</td>
          <td>{data['nb_g_estlc'] + data['nb_g_islape']}</td>
          <td>{data['total']}</td>
        </tr>
        </tbody>
      </table>
      </div>
      
      <div className='flex-initial items-center justify-center text-center'>
        <h1 className='text-xl text-center text-gray-900 mb-5'>Répartition par centre de dépot</h1>
        <table className="text-center border-collapse border border-slate-400 min-w-full">
        <thead className="text-xs text-gray-700 uppercase dark:text-gray-400">
          <tr>
            <th scope="col" className="px-3 py-3 border border-slate-300">N° </th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Centre</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">F</th>
            <th scope="col" className="px-3 py-3 border border-slate-300"> G</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">T</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">%</th>
          </tr>
        </thead>
        <tbody>
        {
            data.centre_depot.map((sE, k) => (
              
              <tr key={k} className="bg-white border dark:bg-gray-800 dark:border-gray-700 text-xs">
                <td className="px-3 py-3 border border-slate-300" key={k}>{k+1}</td>
                <td className="px-3 py-3 border border-slate-300 text-left">{sE["centre_depot_label"]}</td>
                <td className="px-3 py-3 border border-slate-300">{data.candidats.filter(candidat=>parseInt(candidat.ca_centre_depot) === sE["centre_depot_code"] && candidat.ca_sexe ==="Féminin").length}</td>
                <td className="px-3 py-3 border border-slate-300">{data["candidats"].filter(candidat=>parseInt(candidat.ca_centre_depot) === sE['centre_depot_code'] && candidat.ca_sexe ==="Masculin").length}</td>
                <td className="px-3 py-3 border border-slate-300">{data.candidats.filter(candidat=>parseInt(candidat.ca_centre_depot) === sE["centre_depot_code"]).length}</td>
                <td className="px-3 py-3 border border-slate-300">{(data.candidats.filter(candidat=>parseInt(candidat.ca_centre_depot) === sE["centre_depot_code"]).length/data['total']*100).toFixed(2)}</td>
                
              </tr>
              
            ))
        }
        <tr>
          <td colSpan={2} className="px-3 py-3 border border-slate-300 font-bold text-xl">Total</td>
          <td>{data['nb_f_estlc'] + data['nb_f_islape']}</td>
          <td>{data['nb_g_estlc'] + data['nb_g_islape']}</td>
          <td>{data['total']}</td>
        </tr>
        </tbody>
      </table>
      </div>

    </div>
   </div>
  )
}

export default Dashboard;
