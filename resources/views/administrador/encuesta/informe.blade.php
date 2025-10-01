@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')
@section('contenido')
    <div class="row justify-content-center coontainer">
        <div class="col-12 col-lg-10 ">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-danger text-danger text-center py-3 px-4">
                    <h4 class="mb-0 fw-bold text-uppercase fs-5">{{ $informe->titulo }}</h4>
                </div>
                <div class="card-body p-4">
                    <form id="formConfigurarCampos">
                        @csrf

                        <div class="row mb-5">
                            {{-- 1️⃣ Preguntas normales (en la primera columna) --}}
                            @php
                                $normales = $preguntas_informe->filter(
                                    fn($c) => is_null($c->columna_id) && is_null($c->fila_id),
                                );
                            @endphp

                            @if ($normales->count())
                                <div class="col-12 col-md-12 mb-3">
                                    <h5 class="mb-4 text-dark border-bottom pb-2 fw-semibold">
                                        <i class="fas fa-list-ul me-2 text-primary"></i> Preguntas normales
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table  mb-0 table-centered table-bordered" id="tabla_preguntas">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-center py-3 rounded-start-4">#</th>
                                                    <th scope="col" class="py-3">Título</th>
                                                    <th scope="col" class="py-3">Tipo de análisis</th>
                                                    <th scope="col" class="text-center py-3 rounded-end-4">Incluir en
                                                        informe
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($normales as $campo)
                                                    <tr class="shadow-sm">
                                                        <td class="text-center">{{ $loop->iteration }}</td>

                                                        <td>{{ $campo->titulo }}</td>
                                                        <td class="text-center text-capitalize">
                                                            @php
                                                                $color = match ($campo->tipo_analisis) {
                                                                    'conteo' => 'bg-primary',
                                                                    'suma' => 'bg-success',
                                                                    'promedio' => 'bg-warning text-dark',
                                                                    default => 'bg-secondary',
                                                                };
                                                            @endphp
                                                            <span
                                                                class="badge {{ $color }} rounded-pill px-3 py-1 shadow-sm fs-6">
                                                                {{ $campo->tipo_analisis ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="campos[{{ $campo->id }}]" value="activo"
                                                                    @checked($campo->estado === 'activo')>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- 2️⃣ Preguntas tipo tabla (en la segunda columna) --}}
                            @php
                                $tablas = $preguntas_informe->filter(
                                    fn($c) => !is_null($c->columna_id) || !is_null($c->fila_id),
                                );
                            @endphp

                            @if ($tablas->count())
                                <div class="col-12 col-md-12 mt-3">
                                    <h5 class="mb-4 text-dark border-bottom pb-2 fw-semibold">
                                        <i class="fas fa-table me-2 text-primary"></i> Preguntas tipo tabla
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table  mb-0 table-centered table-bordered" id="tabla_preguntas_tabla">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-center py-3 rounded-start-4">#</th>
                                                    <th scope="col" class="py-3">Columna</th>
                                                    <th scope="col" class="py-3">Fila</th>
                                                    <th scope="col" class="py-3">Título</th>
                                                    <th scope="col" class="py-3">Tipo de análisis</th>
                                                    <th scope="col" class="text-center py-3 rounded-end-4">Incluir en
                                                        informe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tablas as $campo)
                                                    <tr class="shadow-sm">
                                                        <td class="text-center">{{ $loop->iteration }}</td>

                                                        <td>{{ $campo->columna->pregunta ?? '-' }}</td>
                                                        <td>{{ $campo->fila->pregunta ?? '-' }}</td>
                                                        <td>{{ $campo->titulo }}</td>
                                                       <td class="text-center text-capitalize">
                                                            @php
                                                                $color = match ($campo->tipo_analisis) {
                                                                    'conteo' => 'bg-primary',
                                                                    'suma' => 'bg-success',
                                                                    'promedio' => 'bg-warning text-dark',
                                                                    default => 'bg-secondary',
                                                                };
                                                            @endphp
                                                            <span
                                                                class="badge {{ $color }} rounded-pill px-3 py-1 shadow-sm fs-6">
                                                                {{ $campo->tipo_analisis ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="campos[{{ $campo->id }}]" value="activo"
                                                                    @checked($campo->estado === 'activo')>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="{{ asset('js/modulos/encuesta/listar_informe.js') }}" type="module"></script>
@endsection
