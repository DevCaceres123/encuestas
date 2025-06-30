<?php

namespace App\Http\Requests\encuesta;

use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;

class EncuestaRequest extends BasePrincipalRequest
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
            case 'encuestas.store':
                return [
                    'titulo' => 'required|max:50|min:5',
                    'descripcion' => 'required|max:100|min:5',
                ];
            case 'encuesta.guardarPregunta':
                return [
                    'descripcionPregunta' => 'required|string|min:5|max:255',
                    'tipoPregunta' => 'required|in:texto,numerico,opcional',
                    'opciones' => 'nullable|array',
                ];
            case 'encuesta.guardarPreguntaTabla':
                return [
                    'titulo' => 'required|string|min:5|max:255',
                    'tipo' => 'required|in:tabla',
                ];
            case 'encuesta.actualizarEncuesta':
                return [
                    'tituloEdit' => 'required|max:50|min:5',
                    'descripcionEdit' => 'required|max:100|min:5',
                ];
            default:
                return [];
        }
    }
}
