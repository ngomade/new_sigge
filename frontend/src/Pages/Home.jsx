import React from 'react'
import Header from '../Components/website/Header'
import Homecontent from '../Contents/Homecontent'
import Footer from '../Components/website/Footer'

export default function Home() {
  return (
    <>
      <Header />
      <main>
        <Homecontent />
      </main>
      <Footer />
    </>
  )
}