import React from 'react';

function ConfirmationModal({ message, onConfirm, onCancel, isOpen }) {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div className="bg-white p-6 rounded-md shadow-md">
        <p className="text-lg mb-4">{message}</p>
        <div className="flex justify-end space-x-4">
          <button 
            onClick={onCancel} 
            className="px-4 py-2 bg-gray-300 text-gray-800 rounded-md"
          >
            Annuler
          </button>
          <button 
            onClick={onConfirm} 
            className="px-4 py-2 bg-teal-600 text-white rounded-md"
          >
            Confirmer
          </button>
        </div>
      </div>
    </div>
  );
}

export default ConfirmationModal;