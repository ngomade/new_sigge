import React from 'react'
import { IoClose } from 'react-icons/io5'

function Modal({ show,children,onClose,title,isLoad,setLoadingState }) {
   
    return  (
        <div  className={show ? 'fixed top-0 overflow-auto bg-gray-500/20 left-0 z-[550] w-full h-full  flex justify-center items-center' : 'fixed  flex-col items-center top-0 left-0 w-full h-full hidden'}>
            {/* <button className='absolute top-5 bg-transparent right-5 ' onClick={onClose}><IoClose size={35}/></button> */}
            <div className="w-3/4 overflow-y-auto py-5 px-6 rounded-lg  bg-white relative max-h-screen  z-[100]">
                <button className='absolute top-5 bg-transparent  right-5 ' onClick={onClose}><IoClose size={35}/></button>
                <h1 className='text-xl font-bold mb-3'>{title}</h1>
                
                {
                    React.isValidElement(children)?React.cloneElement(children,{onClose,isLoad,setLoadingState}):children
                }
                
            </div>

        </div>
    )
}

export default Modal
