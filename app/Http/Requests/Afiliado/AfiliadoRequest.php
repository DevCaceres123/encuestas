<?php

namespace App\Http\Requests\Afiliado;

use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                $ci = trim($this->input('ci'));
                $complemento = trim($this->input('complemento'));
                $ciCompleto = $complemento ? "$ci-$complemento" : $ci;
                return [
                    'ci' => [
                        'required',
                        'alpha_num',
                        'min:6',
                        'max:20',
                        Rule::unique('afiliados', 'ci')->where(function ($query) use ($ciCompleto) {
                            return $query->where('ci', $ciCompleto);
                        }),
                    ],
                    'complemento' => 'nullable|min:1|max:4',
                    'comunidad_id' => 'required|integer|exists:comunidades,id',
                    'expedido_id' => 'required|integer|exists:expedidos,id',
                    'hombres' => 'required|integer|digits_between:1,2',
                    'mujeres' => 'required|integer|digits_between:1,2',
                    'nombres' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                    'paterno' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                    'materno' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                ];
            case 'afiliado.actualizarAfiliado':

                $ci = trim($this->input('ci-edit'));
                $complemento = trim($this->input('complemento-edit'));
                $ciCompleto = $complemento ? "$ci-$complemento" : $ci;
                return [
                    'ci-edit' => [
                        'required',
                        'alpha_num',
                        'min:6',
                        'max:20',
                        Rule::unique('afiliados', 'ci')
                        ->ignore($this->input('id_afiliado')) //se ignora el id actual
                        ->where(function ($query) use ($ciCompleto) {
                            return $query->where('ci', $ciCompleto);
                        }),
                    ],
                    'complemento-edit' => 'nullable|min:1|max:4',
                    'comunidad_id-edit' => 'required|integer|exists:comunidades,id',
                    'expedido_id-edit' => 'required|integer|exists:expedidos,id',
                    'hombres-edit' => 'required|integer|digits_between:1,2',
                    'mujeres-edit' => 'required|integer|digits_between:1,2',
                    'nombres-edit' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                    'paterno-edit' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                    'materno-edit' => 'required|string|max:50|min:2|regex:/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/',
                ];
            case 'comunidad.update':
                return [
                    'id_afiliado' => 'required|exists:afiliados,id',
                   'estado' => 'required|string|in:activo,inactivo',

                    // Más reglas según sea necesario
                ];
            default:
                return [];
        }
    }
}
