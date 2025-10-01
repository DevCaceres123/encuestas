<?php

namespace App\Http\Requests\Formulario;

use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;

class FormularioRequest extends BasePrincipalRequest
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
    public function rules(): array
    {
        $routeName = $this->route()->getName();

        switch ($routeName) {
            case 'formulario.store':
                return [
                    'tituloFormulario' => 'required|max:50|min:5',
                    'descripcionFormulario' => 'required|max:100|min:5',
                    'encuesta_id' => 'required|integer|exists:encuestas,id',
                ];
            case 'formulario.update':
                return [
                    'tituloFormulario_edit' => 'required|max:50|min:5',
                    'descripcionFormulario_edit' => 'required|max:100|min:5',
                    'encuesta_id_edit' => 'required|integer|exists:encuestas,id',
                ];            
            default:
                return [];
        }
    }
}
