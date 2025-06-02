/**
 * Vérifie si tous les champs obligatoires de formData ne sont pas vides, undefined ou null,
 * sauf pour les clés spécifiées qui peuvent être null ou vides.
 * @param {Object} formData - Les données du formulaire à vérifier
 * @param {Array} allowedEmptyFields - Les clés qui peuvent être null ou vides
 * @returns {boolean} - true si tous les champs obligatoires sont remplis, sinon false
 */
export function notEmpty(formData, allowedEmptyFields = []) {
    return Object.keys(formData).every(key => {
        if (allowedEmptyFields.includes(key)) {
            return true;
        }
        const value = formData[key];
        return value !== '' && value !== undefined && value !== null;
    });
}

/**
 * Remplit les champs d'un formulaire avec des valeurs par défaut et appelle un callback avec ces valeurs.
 * @param {String} form - Sélecteur du formulaire
 * @param {Function} callback - Fonction de rappel à appeler avec les valeurs des champs
 * @param {Object} default_value - Valeurs par défaut pour les champs
 * @returns - Retourne le résultat du callback
 */
export function fieldSet(form, callback, default_value) {
    const setField = {};
    const fields = [...document.querySelectorAll(`${form} input`), ...document.querySelectorAll(`${form} select`)].filter((i)=>i.name!==''||i.name===undefined);

    fields.forEach((f) => {
        if (f.name!=="") {
            setField[f.name] = default_value[f.name] !== undefined ? default_value[f.name] : '';
        }
    });

    // Ajouter les valeurs par défaut pour les champs qui n'existent pas dans le formulaire
    Object.keys(default_value).forEach((key) => {
        if (!setField.hasOwnProperty(key)) {
            if (setField[key]!=="") {
                setField[key] = default_value[key];
            }
        }
    });

    return callback(setField);
}
/**
 * 
 * @returns 
 */
export function generateYears(){
    const currentYear = new Date().getFullYear();
    const yearList = [];
    for (let year = currentYear - 20; year <= currentYear; year++) {
      yearList.push(year);
    }
    return yearList
}