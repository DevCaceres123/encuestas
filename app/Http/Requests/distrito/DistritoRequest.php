<?php

namespace App\Http\Requests\distrito;

use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;

class DistritoRequest extends BasePrincipalRequest
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
            case 'distrito.store':
                return [
                    'titulo_distrito' => 'required|max:100|min:5',
                    'descripcion_distrito' => 'required|max:100|min:5',
                ];
            case 'comunidad.nuevo':
                return [
                    'titulo_comunidad' => 'required|max:100|min:5',
                    'descripcion_comunidad' => 'required|max:100|min:5',
                    'distrito_nombre' => 'required|exists:distritos,id',
                    // Más reglas según sea necesario
                ];
            default:
                return [];
        }
    }
}
