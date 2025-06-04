import React, { useEffect, useState } from 'react'
import { getCandidatesByCentre, getCandidateStat } from '../../../api/routes/candidate';
import { toast } from 'react-toastify';
import {
  MdDelete,
  MdVisibility,
} from "react-icons/md";
import * as XLSX from 'xlsx';


function AdminCandidate() {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filter, setFilter] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 50;


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
        setLoading(false);
      })
      .catch((error) => {
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
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = data.candidats.slice(indexOfFirstItem, indexOfLastItem);


  const pageNumbers = [];
  for (let i = 1; i <= Math.ceil(data.candidats.length / itemsPerPage); i++) {
    console.log(i)
    pageNumbers.push(i);
  }

  const handleClickNext = (number) => {
    setCurrentPage(number);
  }
  function onChange(e) {
    getCandidatesByCentre(e.target?.value).then(async(res)=>{
        if (res.status ===200) {
            const new_candidats = await res.json()
            setData((prevData) => ({
              ...prevData, 
              candidats: new_candidats.candidats
            }));
        }
        setError(error);
        setLoading(false);
        return toast.error(error)
     })
    }

  const candidatsFiltrer = currentItems.filter(user =>
    user.ca_nom.toLowerCase().includes(filter.toLowerCase()) ||
    user.ca_prenom.toLowerCase().includes(filter.toLowerCase()) ||
    user.ca_sexe.toLowerCase().includes(filter.toLowerCase())||
    user.filiere_code.toLowerCase().includes(filter.toLowerCase())||
    user.ca_region_origine.toLowerCase().includes(filter.toLowerCase())||
    user.ca_depart_origine.toLowerCase().includes(filter.toLowerCase())||
    user.ca_telephone.toLowerCase().includes(filter.toLowerCase())||
    user.ca_num_recu.toLowerCase().includes(filter.toLowerCase())||
    user.ca_num_cni.toLowerCase().includes(filter.toLowerCase())
    );
    const exportToExcel = () => {
      const customHeaders = ["N°","Code","Nom", "Prenon", "Filière","CENTRE FORMATION", "Sexe","Date de Naissance","Lieu de Naissance", "Téléphone", "Région D'origine", "Departemant D'origine", "Diplome" ,
                            "Mention", "Année d'obtention", "Centre d'Examen", "Centre de Depôt", "Numéro de Reçu", "Numéro CNI", "Date de Délivrance", "Date d'inscription"];
      const worksheetData = [
        customHeaders,
        ...data.candidats.map((ca, k) => 
          [
            k+1, ca.ca_code, ca.ca_nom, ca.ca_prenom, ca.filiere_code,
            ca.code_site === 1?"ESTLC":"ISLAPE", ca.ca_sexe, 
            (new Date(ca.ca_date_naiss)).toLocaleDateString("fr-FR"), ca.ca_lieu_naiss, 
            ca.ca_telephone,
            ca.ca_region_origine, ca.ca_depart_origine, 
            ((data.diplomes.filter(diplome=>diplome.code_dip.toString() === ca["ca_diplome_admission"]))[0]["label_dip"]).substring(0, 4) + " "+ (data.series.filter(serie=>serie.code_serie.toString() === ca["ca_serie_diplome"]))[0]["label_serie"],
            ca.ca_mention_diplome, ca.ca_annee_diplome, 
            (data.centre_examen.filter(centre=>centre.centre_exam_code.toString() === ca["ca_centre_examen"]))[0]["centre_exam_label"],
            (data.centre_depot.filter(centre=>centre.centre_depot_code.toString() === ca["ca_centre_depot"]))[0]["centre_depot_label"],
            ca.ca_num_recu,
            ca.ca_num_cni,
            (new Date(ca.ca_deliv_cni)).toLocaleDateString("fr-FR"),
           (new Date( ca.created_at)).toLocaleDateString("fr-FR")
          ]),
      ];
      const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
      worksheet['!cols'] = [
        { wch: 3 }, { wch: 6 }, { wch: 23 } , { wch: 20 }, { wch: 7 }, { wch: 8 }, 
        { wch: 15 }, { wch: 30 }, { wch: 10 } , { wch: 15 }, { wch: 15 }, { wch: 8 }, 
        { wch: 10 }, { wch: 18 }, { wch: 16 } , { wch: 17 }, { wch: 15 }, { wch: 25 }, 
        { wch: 15 }, { wch: 15 } 
      ];
      worksheet['!rows'] = [
        { hpt: 30 }
      ];
      const date = (new Date()).toLocaleDateString('fr-FR')
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Candidats');
      XLSX.writeFile(workbook, 'ListeCandidate_'+date+'.xlsx');
    }
  return (
   <div>
      <div className='grid items-top grid-cols-1 md:grid-cols-4 lg:grid-cols-4'>
      <h1 className='text-3xl text-center text-teal-600 mb-1 font-bold'>Liste des candidats</h1>
      <div className=' text-center text-teal-600 mb-1'>
        <input
          type="text"
          placeholder="Rechercher par critère..."
          value={filter}
          onChange={e => setFilter(e.target.value)}
          className="p-2 border mb-4 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
        />
      </div>
      <div  className=' text-center text-teal-600 mb-1 grid items-center grid-cols-1 md:grid-cols-2 lg:grid-cols-2'>
          <label htmlFor="centre_exam">Filter par Centre <sup className='text-red-600'>*</sup></label>
          <select id='centre_exam'  name="ca_centre_examen" placeholder='Exp: Lycée Bilingue d Abam' className='p-2 border border-teal-600 rounded-md outline-none focus:outline-teal-600/15 indent-1' onChange={onChange}>
          <option value="0">Selectionner un centre</option>
          <option value="0">Tous les centres</option>
          {data.centre_examen.map((cE,k)=>(
              <option value={cE.centre_exam_code} key={k}>{cE.centre_exam_label}</option>
          ))}
          </select>
      </div>
        <div className='items-center text-center'>
          <button className='bg-teal-600  rounded-lg text-white mb-1 p-2 w-50 text-rigth' onClick={exportToExcel}>Exporter en Excel</button>
        </div>
      </div>
    <div className="overflow-x-auto">
        <table className="min-w-full text-center border-collapse border border-slate-400 ">
        <thead className="text-xs text-gray-700 uppercase dark:text-gray-400">
          <tr>
            <th scope="col" className="px-3 py-3 border border-slate-300">N°</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">N° Reçu</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Noms & Prénons</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Né le</th>
            <th scope="col" className="px-3 py-3 border border-slate-300"> à</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Sexe</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Téléphone</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">C. Depot</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">C. Examen</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Filiere</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Diplome</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Mention</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Région O</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Département O</th>
            <th scope="col" className="px-3 py-3 border border-slate-300">Actions</th>
          </tr>
        </thead>
        <tbody>
        {
            candidatsFiltrer.map((ca, k) => (
              
              <tr key={k} className="bg-white border dark:bg-gray-800 dark:border-gray-700 text-sm">
                <td className="px-3 py-3 border border-slate-300" key={k}>{k+1}</td>
                <td className="px-3 py-3 border border-slate-300" >{ca["ca_num_recu"]}</td>
                <td className="px-3 py-3 border border-slate-300 text-left">{ca["ca_nom"]+ " "+ ca["ca_prenom"]}</td>
                <td className="px-3 py-3 border border-slate-300">{new Date(ca['ca_date_naiss']).toLocaleDateString('fr-FR')}</td>
                <td className="px-3 py-3 border border-slate-300">{ca['ca_lieu_naiss']}</td>
                <td className="px-3 py-3 border border-slate-300">{ca['ca_sexe']}</td>
                <td className="px-3 py-3 border border-slate-300">{ca['ca_telephone']}</td>
                <td className="px-3 py-3 border border-slate-300">{(data.centre_depot.filter(centre=>centre.centre_depot_code.toString() === ca["ca_centre_depot"]))[0]["centre_depot_label"]}</td>
                <td className="px-3 py-3 border border-slate-300">{(data.centre_examen.filter(centre=>centre.centre_exam_code.toString() === ca["ca_centre_examen"]))[0]["centre_exam_label"]}</td>
                <td className="px-3 py-3 border border-slate-300">{ca['filiere_code']}</td>
                <td className="px-3 py-3 border border-slate-300">
                {((data.diplomes.filter(diplome=>diplome.code_dip.toString() === ca["ca_diplome_admission"]))[0]["label_dip"]).substring(0, 4) + " "} 
                {(data.series.filter(serie=>serie.code_serie.toString() === ca["ca_serie_diplome"]))[0]["label_serie"]}
                </td>
                <td className="px-3 py-3 border border-slate-300">{ca['ca_mention_diplome']}</td>
                <td className="px-3 py-3 border border-slate-300">{ca['ca_region_origine']}</td>
                <td className="px-3 py-3 border border-slate-300">{ca['ca_depart_origine']}</td>
                <td className="px-1 py-1 border border-slate-300">
                    <button className='rounded mr-3'>  <MdDelete  size={20} />  </button>
                    <button className='rounded'> <MdVisibility  size={20} />   </button>
                </td>
              </tr>
              
            ))
        }
        <tr>
          <td colSpan={13} className="px-3 py-3 border border-slate-300 font-bold text-xl">Total</td>
          <td>{data.candidats.length}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <div className='text-right'>
        {pageNumbers.map((number) => (
          <button key={number} onClick={() => handleClickNext(number)} className='bg-teal-500 mr-1 p-1 mt-1 text-white'>
            {number}
          </button>
        ))}
    </div>
   </div>
  )
}

export default AdminCandidate