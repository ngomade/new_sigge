
import React from 'react';

const CustomCheckbox = ({ name, label, checked, onChange }) => {
  return (
    <label className="inline-flex items-center cursor-pointer">
      <input
        type="checkbox"
        className="hidden"
        name={name}
        checked={checked}
        onChange={onChange}
      />
      <span
        className={`w-5 h-5 inline-block border-2 border-gray-300 rounded transition-colors duration-200 ${checked ? 'bg-teal-600 border-teal-600' : 'bg-white'}`}
      >
        {checked && (
          <svg
            className="w-4 h-4 text-white mx-auto mt-0.5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
          </svg>
        )}
      </span>
      <span className="ml-2 text-gray-700">{label}</span>
    </label>
  );
};

export default CustomCheckbox;
