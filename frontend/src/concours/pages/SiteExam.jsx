import React, { useState, useEffect } from 'react';
import { getSiteComposition } from '../api/routes/concours';

function SiteExam() {
  const [siteCompo, setSiteCompo] = useState([]);

  useEffect(() => {
    const fetchSiteExam = async () => {
      try {
        const res = await getSiteComposition();
        if (res.status === 200) {
          const data = await res.json();
          setSiteCompo(data);
        }
      } catch (error) {
        console.error('Error fetching site composition:', error);
      }
    };

    fetchSiteExam();
  }, []);

  return (
    <div className="flex items-center justify-center flex-col py-5">
      <h1 className="text-2xl font-bold text-center mb-5">Liste des sites de composition</h1>
      <table className="text-left rtl:text-right border-collapse border border-slate-400">
        <thead className="text-xl text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" className="px-6 py-3 border border-slate-300">Ville</th>
            <th scope="col" className="px-6 py-3 border border-slate-300">Sites de composition</th>
          </tr>
        </thead>
        <tbody>
          {siteCompo.map((sC, k) => (
            <tr key={k} className="bg-white border dark:bg-gray-800 dark:border-gray-700 text-xl">
              <td className="px-6 py-4 border border-slate-300">{sC.site_ville}</td>
              <td className="px-6 py-4 border border-slate-300">{sC.site_lieu}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default SiteExam;
