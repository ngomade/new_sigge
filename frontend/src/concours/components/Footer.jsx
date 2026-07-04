import React from 'react'
import { Link } from 'react-router-dom'

function Footer() {
  return (
    <div className='bg-white w-full p-2 flex text-gray-500 justify-between items-center'>
        <Link to={'/'} className='text-xl font-bold'>ESTLC &copy; { new Date().getFullYear() }</Link>
        <p>Copyright &copy; { new Date().getFullYear() }</p>
    </div>
  )
}

export default Footer