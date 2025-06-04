import React from 'react'
import Navbar from '../Navbar'
import { Outlet } from 'react-router-dom'
import Footer from '../Footer'
import '../../../index.css';
function ClientLayout() {
  return (
    <main>
        <Navbar/>
        <Outlet/>
        <Footer/>
    </main>
  )
}

export default ClientLayout