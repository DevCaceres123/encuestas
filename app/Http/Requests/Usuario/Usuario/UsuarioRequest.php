<?php

namespace App\Http\Requests\Usuario\Usuario;
use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends BasePrincipalRequest
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
        $routeName = $this->route()->getName();

        switch ($routeName) {
            case 'usuarios.store':
                return [
                    'ci' => 'required|string|max:20|unique:users',
                    'nombres' => 'required|string|max:255',
                    'paterno' => 'required|string|max:255',
                    'materno' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email',
                    'role' => 'required|integer',
                    
                ];
            case 'usuarios.update':
                return [
                    'username' => 'required|string|max:255',
                    'bio' => 'nullable|string|max:500',
                    // Más reglas según sea necesario
                ];
            default:
                return [];
        }
    }

    /**
     * Get the custom error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ci.unique' => 'El carnet de identidad ya esta en uso.',
            'ci.required' => 'El campo CI es obligatorio.',
            'ci.string' => 'El CI debe ser una cadena de texto.',
            'ci.max' => 'El CI no puede tener más de 20 caracteres.',

            'nombres.required' => 'El nombre es obligatorio.',
            'nombres.string' => 'El nombre debe ser una cadena de texto.',
            'nombres.max' => 'El nombre no puede tener más de 255 caracteres.',

            'paterno.required' => 'El apellido paterno es obligatorio.',
            'paterno.string' => 'El apellido paterno debe ser una cadena de texto.',
            'paterno.max' => 'El apellido paterno no puede tener más de 255 caracteres.',

            'materno.string' => 'El apellido materno debe ser una cadena de texto.',
            'materno.max' => 'El apellido materno no puede tener más de 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe ser un formato válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está en uso.',

         
        ];
    }
}
