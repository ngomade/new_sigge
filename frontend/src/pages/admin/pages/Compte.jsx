import React, { useEffect, useState } from 'react'
import { getCandidatesByCentre, } from '../../../api/routes/candidate';
import { toast } from 'react-toastify';
import {
  MdDelete,
  MdEdit,
  MdRefresh,
  MdVisibility,
} from "react-icons/md";
import { deleteCompte, getCompteStat, showRecu } from '../../../api/routes/compte';
import ConfirmationModal from '../components/modals/ConfirmationModal';



function Compte() {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filter, setFilter] = useState('');
  const [ca_num_recu, setCaNumRecu] = useState('');
  const [filter_false, setFilterFalse] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  

  useEffect(() => {
    getCompteStat()
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
  const candidatsFiltrer = data.comptes.filter(compte =>
    (compte.ca_num_recu.toLowerCase().includes(filter.toLowerCase()) ||
    compte.ca_nom.toLowerCase().includes(filter.toLowerCase()) ||
    compte.ca_prenom.toLowerCase().includes(filter.toLowerCase())||
    compte.ca_email.toLowerCase().includes(filter.toLowerCase())) &&
    (compte.ca_code !== null)
    );
    const candidatsFiltrerFalse = data.comptes.filter(compte =>
      ( compte.ca_code === null || compte.ca_num_recu.length !== 6) &&
      (compte.ca_num_recu.toLowerCase().includes(filter_false.toLowerCase()) ||
      compte.ca_nom.toLowerCase().includes(filter_false.toLowerCase()) ||
      compte.ca_prenom.toLowerCase().includes(filter_false.toLowerCase())||
      compte.ca_email.toLowerCase().includes(filter_false.toLowerCase()))
      );
  function callShowRecu(ca_num_recu){
    showRecu(ca_num_recu).then(response => response.json())
    .then(data => {
        const base64Pdf = data.base64_pdf;
        const binary = atob(base64Pdf);
        const len = binary.length;
        const buffer = new ArrayBuffer(len);
        const view = new Uint8Array(buffer);
        for (let i = 0; i < len; i++) {
            view[i] = binary.charCodeAt(i);
        }
        const blob = new Blob([view], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);
        window.open(url);
    })
    .catch(error => console.error('Error:', error));
  }
  const handleDelete = (recu) => {
    setCaNumRecu(recu)
    setIsModalOpen(true);
  };

  const handleCancel = () => {
    setIsModalOpen(false);
  };

  function callDeleteCompte(){
    deleteCompte(ca_num_recu).then(async(response)=>{
      if(response.status === 200){
        const {message} = await response.json()
        toast.success(message)
        setTimeout(() => {
          window.location.reload()
      }, 1000);
      }else{
        return toast.error("Erreur lors de la suppression du compte")
      }
    })
    setIsModalOpen(false);
  }
  return (
   <div>
      <div className='grid items-top gap-3 grid-cols-1 md:grid-cols-2 lg:grid-cols-2 mr-3'>
      <h1 className='text-3xl text-center text-teal-600 mb-1 font-bold'>Liste des comptes </h1>
      </div>
      <div className="overflow-sx-auto grid items-top grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
        <div>
          <div className=' text-center text-teal-600 mb-1'>
            <input
              type="text"
              placeholder="Rechercher par critère..."
              value={filter}
              onChange={e => setFilter(e.target.value)}
              className="p-2 border mb-4 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
            />
          </div>
          <div className='overflow-x-auto mb-2 mr-2'>
            <table className="min-w-full text-center border-collapse border border-slate-400 ">
              <thead className="text-xs text-gray-700 uppercase dark:text-gray-400">
                <tr className='bg-teal-500 text-white'>
                  <th scope="col" className="px-3 py-3 border border-slate-300">N°</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">N° Reçu</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">Noms & Prénons</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">E-emil </th>
                  <th scope="col" className="px-3 py-3 border border-slate-300"> Code Candidat</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">Actions</th>
                </tr>
              </thead>
              <tbody>
              {
                  candidatsFiltrer.map((cp, k) => (
                    
                    <tr key={k} className="bg-white border dark:bg-gray-800 dark:border-gray-700 text-sm">
                      <td className="px-3 py-3 border border-slate-300" key={k}>{k+1}</td>
                      <td className="px-3 py-3 border border-slate-300" >{cp["ca_num_recu"]}</td>
                      <td className="px-3 py-3 border border-slate-300 text-left">{cp["ca_nom"]+ " "+ cp["ca_prenom"]}</td>
                      <td className="px-3 py-3 border border-slate-300">{cp['ca_email']}</td>
                      <td className="px-3 py-3 border border-slate-300">{cp['ca_code']==null ?"":cp['ca_code']}</td>
                      <td className="px-1 py-1 border border-slate-300">
                          <button className='rounded mr-3' title='Supprimer' onClick={ ()=> handleDelete(cp["ca_num_recu"])}>  <MdDelete  size={20} />  </button>
                          {/* <button className='rounded mr-3' title='Modifier'>  <MdEdit size={20} />  </button> */}
                          <button className='rounded' title='Visualiser le recu' onClick={()=> callShowRecu(cp["ca_num_recu"])} target="blank"> <MdVisibility  size={20} />   </button>
                      </td>
                    </tr>
                    
                  ))
              }
              <tr>
                <td colSpan={5} className="px-3 py-3 border border-slate-300 font-bold text-xl">Total</td>
                <td>{candidatsFiltrer.length}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div className='mt-2'>
          <div className=' text-center text-teal-600 mb-1'>
            <input
              type="text"
              placeholder="Rechercher par critère..."
              value={filter_false}
              onChange={e => setFilterFalse(e.target.value)}
              className="p-2 border mb-4 border border-teal-600 rounded-md outline-none focus:ring focus:ring-teal-600/50 indent-1"
            />
          </div>
          <div className='overflow-x-auto mb-2'>
            <table className="min-w-full text-center border-collapse border border-slate-400 ">
              <thead className="text-xs text-gray-700 uppercase dark:text-gray-400">
                <tr className='bg-red-500 text-white'>
                  <th scope="col" className="px-3 py-3 border border-slate-300">N°</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">N° Reçu</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">Noms & Prénons</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">E-mail </th>
                  <th scope="col" className="px-3 py-3 border border-slate-300"> Code Candidat</th>
                  <th scope="col" className="px-3 py-3 border border-slate-300">Actions</th>
                </tr>
              </thead>
              <tbody>
              {
                  candidatsFiltrerFalse.map((cp, k) => (
                    
                    <tr key={k} className="bg-white border dark:bg-gray-800 dark:border-gray-700 text-sm">
                      <td className="px-3 py-3 border border-slate-300" key={k}>{k+1}</td>
                      <td className="px-3 py-3 border border-slate-300" >{cp["ca_num_recu"]}</td>
                      <td className="px-3 py-3 border border-slate-300 text-left">{cp["ca_nom"]+ " "+ cp["ca_prenom"]}</td>
                      <td className="px-3 py-3 border border-slate-300">{cp['ca_email']}</td>
                      <td className="px-3 py-3 border border-slate-300">{cp['ca_code']==null ?"":cp['ca_code']}</td>
                      <td className="px-1 py-1 border border-slate-300">
                          <button className='rounded mr-3' title='Supprimer' onClick={ ()=> handleDelete(cp["ca_num_recu"])}>  <MdDelete  size={20} />  </button>
                          {/* <button className='rounded mr-3' title='Reinitialiser le mot de passe'>  <MdEdit  size={20} />  </button> */}
                          <button className='rounded' title='Visualiser le reçu' onClick={()=> callShowRecu(cp["ca_num_recu"])} target="blank"> <MdVisibility  size={20} />   </button>
                      </td>
                    </tr>
                    
                  ))
              }
              <tr>
                <td colSpan={5} className="px-3 py-3 border border-slate-300 font-bold text-xl">Total</td>
                <td>{candidatsFiltrerFalse.length}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
    </div>
    <ConfirmationModal
        message="Êtes-vous sûr de vouloir supprimer ce compte ?" 
        isOpen={isModalOpen} 
        onConfirm={callDeleteCompte} 
        onCancel={handleCancel} 
      />
   </div>
  )
}

export default Compte