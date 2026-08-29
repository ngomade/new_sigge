<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonnelRequest extends FormRequest
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
        $id = $this->route('personnel');

        return [
            'nom_pers' => 'sometimes|string|max:255',
            'prenom_pers' => 'nullable|string|max:255',
            'sexe_pers' => 'sometimes|string|max:1',
            'date_naissance_pers' => 'sometimes|date',
            'lieu_naissance_pers' => 'sometimes|string|max:255',
            'statut_mat_pers' => 'sometimes|string|max:32',
            'lieu_residence_pers' => 'nullable|string|max:255',
            'first_phone_pers' => 'sometimes|string|max:32',
            'second_phone_pers' => 'nullable|string|max:32',
            'cni_pers' => 'sometimes|string|max:32',
            'date_deliv_cni_pers' => 'sometimes|date',
            'email_pers' => 'sometimes|email|max:255|unique:personnel,email_pers,'.$id.',code_pers',
            'login_pers' => 'sometimes|string|max:255|unique:personnel,login_pers,'.$id.',code_pers',
            'pwd_pers' => 'sometimes|string|min:6',
            'photo_pers' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'lang_pers' => 'nullable|string|max:10',
            'nationalite_pers' => 'nullable|string|max:255',
            'region_pers' => 'nullable|string|max:255',
            'depart_pers' => 'nullable|string|max:255',
            'arrond_pers' => 'nullable|string|max:255',
            'bibliographie_pers' => 'nullable|string',
            'nb_enfant_pers' => 'nullable|integer|min:0',
        ];
    }
}
