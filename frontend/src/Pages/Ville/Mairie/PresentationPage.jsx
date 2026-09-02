import React from 'react'
import Header from '../../../Components/website/Header'
import Footer from '../../../Components/website/Footer'
import PresentationMairie from '../../../Contents/VilleContent/Mairie/PresentationMairieContent'

export default function PresentationPage() {
  return (
    <div>
        <Header />
        <PresentationMairie />
        <Footer />
    </div>
  )
}
