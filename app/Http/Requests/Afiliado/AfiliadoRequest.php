<?php

namespace App\Http\Requests\Afiliado;

use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;

class AfiliadoRequest extends BasePrincipalRequest
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
            case 'afiliado.store':
                return [
                    'ci' => 'required|digits_between:6,20|unique:afiliados,ci',
                    'complemento' => 'nullable|min:1|max:4',
                    'comunidad_id' => 'required|integer|exists:comunidades,id',
                    'expedido_id' => 'required|integer|exists:expedidos,id',
                    'hombres' => 'required|integer|digits_between:1,2',
                    'mujeres' => 'required|integer|digits_between:1,2',
                    'nombres' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                    'paterno' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                    'materno' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
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
