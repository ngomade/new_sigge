import React from 'react'
import { LuLoader } from 'react-icons/lu'

function Loading() {
  return (
    <div className='w-full bg-gray-300/50 h-screen fixed top-0 flex items-center justify-center'>
        <div className="flex items-center justify-center">
            <LuLoader size={50} className=' text-teal-500 animate-spin duration-500'/>
        </div>
    </div>
  )
}

export default Loading