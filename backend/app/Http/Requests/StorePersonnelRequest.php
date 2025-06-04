<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonnelRequest extends FormRequest
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
            'nom_pers' => 'required|string|max:255',
            'prenom_pers' => 'nullable|string|max:255',
            'sexe_pers' => 'required|string|max:1',
            'date_naissance_pers' => 'required|date',
            'lieu_naissance_pers' => 'required|string|max:255',
            'statut_mat_pers' => 'required|string|max:32',
            'lieu_residence_pers' => 'nullable|string|max:255',
            'first_phone_pers' => 'required|string|max:32',
            'second_phone_pers' => 'nullable|string|max:32',
            'cni_pers' => 'required|string|max:32',
            'date_deliv_cni_pers' => 'required|date',
            'email_pers' => 'required|email|max:255|unique:personnel,email_pers',
            'login_pers' => 'required|string|max:255|unique:personnel,login_pers',
            'pwd_pers' => 'required|string|min:6',
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
