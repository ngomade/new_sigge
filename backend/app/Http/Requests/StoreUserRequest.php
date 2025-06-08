<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code_info_extra' => 'required|integer|exists:info_extra,code_info_extra',
            'nom_user' => 'required|string|max:255',
            'prenom_user' => 'sometimes|nullable|string|max:255',
            'sexe_user' => 'required|string|max:10',
            'date_naissance_user' => 'required|date',
            'lieu_naissance_user' => 'required|string|max:255',
            'statut_mat_user' => 'required|string|max:50',
            'lieu_resi_user' => 'sometimes|nullable|string|max:255',
            'first_phone_user' => 'required|string|max:20',
            'second_phone_user' => 'sometimes|nullable|string|max:20',
            'numero_cni_user' => 'required|string|max:50',
            'email_user' => 'required|email|max:255|unique:user,email_user',
            'date_deliv_cni_user' => 'required|date',
            'login_user' => 'required|string|max:100|unique:user,login_user',
            'pwd_user' => 'required|string|min:6',
            'photo_user' => 'sometimes|nullable|string|max:255',
            'handicap_user' => 'sometimes|nullable|string|max:255',
            'langue_user' => 'sometimes|nullable|string|max:50',
            'nbre_enfant_user' => 'required|integer',
            'nationalite_user' => 'sometimes|nullable|string|max:100',
            'region_origine_user' => 'sometimes|nullable|string|max:100',
            'depart_origine_user' => 'sometimes|nullable|string|max:100',
            'arrond_origine_user' => 'sometimes|nullable|string|max:100',
            'bibiographie_user' => 'sometimes|nullable|string',
            'statut_user' => 'required|integer',
            'ecole_user' => 'required|string|max:255',
        ];
    }
}
