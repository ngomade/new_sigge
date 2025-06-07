<?php

namespace App\Http\Requests\concours;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CandidatRequest extends FormRequest
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
        return [
            'id' => 'required|integer|exists:sessionconcour,id',
            'filiere_code' => 'required|string|exists:filiere,filiere_code',
            'code_site' => 'required|integer|exists:site_etude,code_site',
            'ca_nom' => 'required|string|max:255',
            'ca_prenom' => 'required|string|max:255',
            'ca_sexe' => 'required|string|in:Masculin,Feminin',
            'ca_date_naiss' => 'required|date',
            'ca_lieu_naiss' => 'required|string|max:255',
            'ca_statut_mat' => 'required|string|max:50',
            'ca_adresse' => 'nullable|string',
            'ca_telephone' => 'required|string|max:20',
            'ca_num_cni' => 'required|string|max:50',
            'ca_email' => 'required|email|max:255',
            'ca_premiere_lang' => 'required|string|max:50',
            'ca_nationalite' => 'required|string|max:100',
            'ca_region_origine' => 'required|string|max:100',
            'ca_depart_origine' => 'required|string|max:100',
            'ca_diplome_admission' => 'required|string|max:100',
            'ca_annee_diplome' => 'required|max:4',
            'ca_serie_diplome' => 'required|string|max:50',
            'ca_mention_diplome' => 'required|string|max:50',
            'ca_etab_diplome' => 'required|string|max:255',
            'ca_pays_diplome' => 'required|string|max:100',
            'ca_centre_examen' => 'required|string|max:255',
            'ca_centre_depot' => 'required|string|max:255',
            'ca_nom_pere' => 'required|string|max:255',
            'ca_telephone_pere' => 'required|string|max:20',
            'ca_nom_mere' => 'required|string|max:255',
            'ca_telephone_mere' => 'required|string|max:20',
            'ca_handicap' => 'required|string|max:50',
            'ca_email_pere' => 'nullable|email|max:255',
            'ca_deliv_cni' => 'required|string|max:255',
            'ca_num_recu' => 'required|string|max:50',
            'ca_recu' => 'required|string|max:255',
            'ecoles' => 'sometimes|array',
            'ecoles.*.code_ecole' => 'required|string|exists:ecole,code_ecole',
        ];
    }
}
