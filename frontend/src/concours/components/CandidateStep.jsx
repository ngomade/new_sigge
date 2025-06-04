import React from 'react';
import Modal from './Modal';

const CandidateStep = ({ stepNumber, Icon, title,children,isLoad,setLoadingState}) => {
    const [showModal,setModalShow] = React.useState(false)
    function onClick(){
        setModalShow(false)
    }
    function onClose(){
        setModalShow(false)
    }
    return (
        <>
            <div className='flex bg-white items-start gap-3 cursor-pointer' onClick={onClick}>
                <div className='text-teal-500 flex-none rounded-full w-6 h-6 border px-1 text-center border-teal-500 flex items-center justify-center'>
                    {stepNumber}
                </div>
                <div className='shadow border p-3 rounded-lg'>
                    <div className="flex items-center justify-center gap-3">
                        <Icon className='text-5xl text-teal-500' />
                        <h2 className='mt-2 font-bold text-xl'>
                            {title}
                        </h2>
                    </div>
                </div>
            </div>
            <Modal show={showModal} isLoad={isLoad} setLoadingState={setLoadingState} onClose={onClose} title={title} children={children}/>
        </>
    );
};



export default CandidateStep;
