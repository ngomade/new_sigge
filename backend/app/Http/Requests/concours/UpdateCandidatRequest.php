<?php

namespace App\Http\Requests\concours;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $ca_code = $this->route('ca_code');

        return [
            'id' => ['sometimes', 'integer', 'exists:session_concours,id'],
            'filiere_code' => ['sometimes', 'string', 'exists:filiere,code_filiere'],
            'code_site' => ['sometimes', 'integer', 'exists:site_etude,code_site'],
            'ca_nom' => ['sometimes', 'string', 'max:255'],
            'ca_prenom' => ['sometimes', 'string', 'max:255'],
            'ca_sexe' => ['sometimes', 'string', 'in:Masculin,Feminin'],
            'ca_date_naiss' => ['sometimes', 'date'],
            'ca_lieu_naiss' => ['sometimes', 'string', 'max:255'],
            'ca_statut_mat' => ['sometimes', 'string', 'max:50'],
            'ca_adresse' => ['sometimes', 'nullable', 'string'],
            'ca_telephone' => 'sometimes|string|max:20',
            'ca_num_cni' => 'sometimes|string|max:50|unique:candidat,ca_num_cni,'.$ca_code.',ca_code',
            'ca_email' => 'sometimes|email|max:255|unique:candidat,ca_email,'.$ca_code.',ca_code',
            'ca_premiere_lang' => ['sometimes', 'string', 'max:50'],
            'ca_nationalite' => ['sometimes', 'string', 'max:100'],
            'ca_region_origine' => ['sometimes', 'string', 'max:100'],
            'ca_depart_origine' => ['sometimes', 'string', 'max:100'],
            'ca_diplome_admission' => ['sometimes', 'string', 'max:100'],
            'ca_annee_diplome' => ['sometimes', 'max:4'],
            'ca_serie_diplome' => ['sometimes', 'string', 'max:50'],
            'ca_mention_diplome' => ['sometimes', 'string', 'max:50'],
            'ca_etab_diplome' => ['sometimes', 'string', 'max:255'],
            'ca_pays_diplome' => ['sometimes', 'string', 'max:100'],
            'ca_centre_examen' => ['sometimes', 'string', 'max:255'],
            'ca_centre_depot' => ['sometimes', 'string', 'max:255'],
            'ca_nom_pere' => ['sometimes', 'string', 'max:255'],
            'ca_telephone_pere' => ['sometimes', 'string', 'max:20'],
            'ca_nom_mere' => ['sometimes', 'string', 'max:255'],
            'ca_telephone_mere' => ['sometimes', 'string', 'max:20'],
            'ca_handicap' => ['sometimes', 'string', 'max:50'],
            'ca_email_pere' => ['sometimes', 'nullable', 'email', 'max:255'],
            'ca_deliv_cni' => ['sometimes', 'string', 'max:255'],
            'ca_num_recu' => ['sometimes', 'string', 'max:50'],
            'ca_recu' => ['sometimes', 'string', 'max:255'],
            'ecoles' => 'sometimes|array',
            'ecoles.*.code_ecole' => 'required|string|exists:ecole,code_ecole',
        ];
    }
}
