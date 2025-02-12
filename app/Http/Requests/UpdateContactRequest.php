<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:contacts,email,' . $this->id,
            'address' => 'required|string|max:255',
            'phone' => 'required|max:11'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'email.required' => 'O e-mail é obrigatório',
            'address.required' => 'O endereço é obrigatório',
            'phone.required' => 'O telefone é obrigatório',
            'email.unique' => 'O e-mail já está em uso',
            'email.email' => 'O e-mail é inválido',
            'phone.max' => 'O telefone deve ter no máximo 11 dígitos'
        ];
    }
}
