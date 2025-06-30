@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')

@section('contenido')

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <h4 class="mb-3">Configurar campos para contabilización en el informe: {{ $informe->titulo }}</h4>


            </div>

        </div>
    </div>





    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col text-center mb-2 mt-2">
                            <h4 class="d-inline card-title p-2 bg-danger rounded text-light">{{ $informe->titulo }}</h4>
                        </div>
                        <div class="col-auto">

                        </div>
                        <div class="card-body">
                            <form id="formConfigurarCampos">
                                @csrf
                                <table class="table table-striped table-bordered align-middle">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Pregunta</th>
                                            <th>Columna</th>
                                            <th>Fila</th>
                                            <th>Título</th>
                                            <th>Contabilizar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($preguntas as $campo)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $campo->pregunta->titulo ?? '-' }}</td>
                                                <td>{{ $campo->columna->pregunta ?? '-' }}</td>
                                                <td>{{ $campo->fila->pregunta ?? '-' }}</td>
                                                <td>{{ $campo->titulo }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="campos[{{ $campo->id }}]" value="activo"
                                                        @checked($campo->estado === 'activo')>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        Guardar configuración
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')

    <script src="{{ asset('js/modulos/encuesta/listar_encuesta.js') }}" type="module"></script>
@endsection
