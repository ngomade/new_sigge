// src/components/Stepper.js
import React from 'react';
import { useSelector } from 'react-redux';
/* import { nextStep, prevStep } from '../app/modules/stepper'; */

const Stepper = ({children}) => {
  const step = useSelector((state) => state.stepper.step);
  const totalSteps = useSelector((state) => state.stepper.totalSteps);
  /* const dispatch = useDispatch(); */

  const progressPercentage = (step / totalSteps) * 100;

  return (
    <div className="max-w-3/4 w-full mx-auto p-8">
      <div className="mb-4">
        <div className="relative pt-1">
          <div className="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
            <div
              style={{ width: `${progressPercentage}%` }}
              className="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-teal-600"
            ></div>
          </div>
        </div>
      </div>
      <div className='py-10 px-4'>
        {children}
      </div>
      <div>
        <h2 className="text-xl font-semibold mb-4">Etape {step} sur {totalSteps}</h2>
        
      </div>
    </div>
  );
};

export default Stepper;
