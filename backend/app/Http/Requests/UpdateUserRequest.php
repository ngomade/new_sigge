<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('code_user');

        return [
            'code_info_extra' => 'sometimes|integer|exists:info_extra,code_info_extra',
            'nom_user' => 'sometimes|string|max:255',
            'prenom_user' => 'sometimes|nullable|string|max:255',
            'sexe_user' => 'sometimes|string|max:10',
            'date_naissance_user' => 'sometimes|date',
            'lieu_naissance_user' => 'sometimes|string|max:255',
            'statut_mat_user' => 'sometimes|string|max:50',
            'lieu_resi_user' => 'sometimes|nullable|string|max:255',
            'first_phone_user' => 'sometimes|string|max:20',
            'second_phone_user' => 'sometimes|nullable|string|max:20',
            'numero_cni_user' => 'sometimes|string|max:50',
            'email_user' => 'sometimes|email|max:255|unique:user,email_user,' . $userId . ',code_user',
            'date_deliv_cni_user' => 'sometimes|date',
            'login_user' => 'sometimes|string|max:100|unique:user,login_user,' . $userId . ',code_user',
            'pwd_user' => 'sometimes|string|min:6',
            'photo_user' => 'sometimes|nullable|string|max:255',
            'handicap_user' => 'sometimes|nullable|string|max:255',
            'langue_user' => 'sometimes|nullable|string|max:50',
            'nbre_enfant_user' => 'sometimes|integer',
            'nationalite_user' => 'sometimes|nullable|string|max:100',
            'region_origine_user' => 'sometimes|nullable|string|max:100',
            'depart_origine_user' => 'sometimes|nullable|string|max:100',
            'arrond_origine_user' => 'sometimes|nullable|string|max:100',
            'bibiographie_user' => 'sometimes|nullable|string',
            'statut_user' => 'sometimes|integer',
            'ecole_user' => 'sometimes|string|max:255',
        ];
    }
}
