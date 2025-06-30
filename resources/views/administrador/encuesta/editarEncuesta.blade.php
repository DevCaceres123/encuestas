@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')

    <div class="row">
        <div class="col-5 m-auto">
            <div class="card p-3">
                <h4 class="d-auto p-1 bg-danger rounded text-light text-center">INFORMACION</h4>

                <div class="mb-2 d-flex">
                    <div class="text-muted me-2" style="width: 140px;">Título:</div>
                    <div>{{ $encuesta->titulo }}</div>
                </div>
                <div class="mb-2 d-flex">
                    <div class="text-muted me-2" style="width: 140px;">Descripción:</div>
                    <div>{{ $encuesta->descripcion }}</div>
                </div>
                <div class="mb-2 d-flex">
                    <div class="text-muted me-2" style="width: 140px;">Fecha de creación:</div>
                    <div>{{ $encuesta->created_at->format('d/m/Y H:i') }}</div>
                </div>


                <input type="hidden" id="idEncuesta" value="{{ $encuesta->id }}">
            </div>
        </div>

        <div class="col-12">
            <div class="container">

                <div class="card p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="d-inline card-title">Preguntas :::</h4>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalPregunta">
                                <i class="fas fa-plus me-1"></i> Nueva Pregunta
                            </button>
                        </div>
                        <hr class="mt-2">
                    </div>
                    <div id="contenedor-preguntas">
                        @foreach ($preguntas as $pregunta)
                            <div class="row">


                                <div class="card-body position-relative pregunta-card" id="pregunta_{{ $pregunta['id'] }}">
                                    <!-- Dropdown de los 3 puntos -->
                                    <div class="dropdown position-absolute top-0 end-0">
                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                @if ($pregunta['editable'] === false)
                                                    <a class="dropdown-item btnEditarPregunta" title="Editar pregunta"
                                                        data-id="{{ $pregunta['id'] }}"
                                                        data-titulo="{{ $pregunta['titulo'] }}"
                                                        data-obligatorio="{{ $pregunta['obligatorio'] }}">Editar</a>
                                                    </a>
                                                @endif
                                            </li>

                                            <li>
                                                <button class="dropdown-item text-danger btnEliminarPRegunta"
                                                    data-id={{ $pregunta['id'] }}>Eliminar</button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-info  btn-sm btn-mover-manual"
                                                    data-bs-toggle="modal" data-bs-target="#modalMoverPregunta"
                                                    data-id="{{ $pregunta['id'] }}" title="Mover pregunta">
                                                    <i class="fas fa-arrows-alt-v"></i>
                                                    Mover</button>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="card border-start border-4 border-primary mb-3">
                                        <div class="card-body">
                                            <p class="mb-3 text-capitalize">
                                                <strong>{{ $loop->iteration }}.- {{ $pregunta['titulo'] }}</strong>

                                                @if ($pregunta['editable'] === true)
                                                    <span class="badge bg-danger ms-2 p-1"
                                                        title="Ya tiene respuestas registradas">
                                                        <i class="fas fa-lock me-1"></i> No editable
                                                    </span>
                                                @endif

                                                @if ($pregunta['obligatorio'] === 'si')
                                                    <span class="badge bg-primary ms-2 p-1"
                                                        title="Esta pregunta es obligatoria">
                                                        <i class="fas fa-asterisk me-1"></i> Obligatorio
                                                    </span>
                                                @endif
                                                @if ($pregunta['varias_respuestas'] === 'si')
                                                    <span class="badge bg-info text-dark ms-2 p-1"
                                                        title="Puedes seleccionar más de una opción">
                                                        <i class="fas fa-check-double me-1"></i>Más de una opción
                                                    </span>
                                                @endif
                                            </p>

                                            @if ($pregunta['tipo'] === 'tabla')
                                                {{-- Tabla Dinámica --}}

                                                <div class="pregunta-card" id="pregunta_{{ $pregunta['id'] }}"
                                                    data-tipo="{{ $pregunta['tipo'] }}">
                                                    <div class="vista-normal">
                                                        <table class="table table-bordered border-primary mt-2">
                                                            <thead class="table-dark text-uppercase">
                                                                <tr>
                                                                    @foreach ($pregunta['columnas'] as $columna)
                                                                        <th>{{ $columna['titulo'] }}</th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    // Determinar el número de filas (basado en columna tipo 'pregunta')
                                                                    $columnaPreguntas = collect(
                                                                        $pregunta['columnas'],
                                                                    )->firstWhere('tipo', 'pregunta');
                                                                    $filas = $columnaPreguntas['preguntas'] ?? [];
                                                                    $totalFilas = count($filas);

                                                                    if ($totalFilas === 0) {
                                                                        // Si no hay preguntas, mostrar una fila vacía
                                                                        $totalFilas = 1;
                                                                    }

                                                                @endphp

                                                                @for ($i = 0; $i < $totalFilas; $i++)
                                                                    <tr>
                                                                        @foreach ($pregunta['columnas'] as $columna)
                                                                            <td>
                                                                                @if ($columna['tipo'] === 'pregunta')
                                                                                    <p class="text-uppercase">
                                                                                        {{ $columna['preguntas'][$i]['texto'] ?? '-' }}
                                                                                    </p>
                                                                                @elseif ($columna['tipo'] === 'numero')
                                                                                    <input type="number" step="any"
                                                                                        class="form-control" />
                                                                                @elseif ($columna['tipo'] === 'porcentaje')
                                                                                    <input type="number" step="any"
                                                                                        class="form-control"
                                                                                        max="100" />
                                                                                @elseif ($columna['tipo'] === 'texto')
                                                                                    <textarea class="form-control" rows="1"></textarea>
                                                                                @elseif ($columna['tipo'] === 'opcion')
                                                                                    <select class="form-control">
                                                                                        <option value="">Seleccione
                                                                                        </option>
                                                                                        @foreach ($columna['opciones'] as $opcion)
                                                                                            <option
                                                                                                value="{{ $opcion['id'] }}">
                                                                                                {{ $opcion['opcion'] }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @endif
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endfor
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="vista-edicion d-none mt-3 mb-3">
                                                        <div class="container mb-2 d-auto">
                                                            <label for="" class="form-label">Editar titulo</label>
                                                            <input type="text" value="{{ $pregunta['titulo'] }}"
                                                                class="form-control mb-2" name="tituloPreguntaEdit"
                                                                id="tituloPreguntaEdit" placeholder="Título de la pregunta">
                                                        </div>

                                                        {{-- Editar columnas --}}
                                                        @foreach ($pregunta['columnas'] as $i => $columna)
                                                            <div class="mb-3 p-3 border rounded bg-light columna-tabla-edit"
                                                                data-tipo="{{ $columna['tipo'] }}"
                                                                data-id="{{ $columna['id'] }}">
                                                                <div class="text-end mb-2">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger btnEliminarColumnaEdit">
                                                                        <i class="fas fa-trash-alt"></i> Eliminar columna
                                                                    </button>
                                                                </div>
                                                                <div class="m-auto text-center mb-2">
                                                                    <label class="text-center text-uppercase">Columna
                                                                        {{ $i + 1 }}:</label>
                                                                </div>
                                                                <input type="text" class="form-control mb-2"
                                                                    name="titulo_columna"
                                                                    value="{{ $columna['titulo'] }}">

                                                                @if ($columna['tipo'] === 'pregunta')
                                                                    <label class="mb-2">Pregunta (Filas):</label>
                                                                    <div class="contenedor-filas-tabla mb-2">
                                                                        @foreach ($columna['preguntas'] as $j => $fila)
                                                                            <div class="input-group mb-1 fila-item">
                                                                                <input type="text"
                                                                                    class="form-control mb-1"
                                                                                    name="fila_columna[]"
                                                                                    value="{{ $fila['texto'] }}"
                                                                                    data-id="{{ $fila['id'] }}">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-danger btn-sm btnEliminarFila">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button"
                                                                        class="btn btn-outline-primary btn-sm agregarFilaTabla">
                                                                        <i class="fas fa-plus me-1"></i> Agregar fila
                                                                    </button>
                                                                @elseif ($columna['tipo'] === 'opcion')
                                                                    <label class="mb-2">Opciones:</label>
                                                                    <div class="contenedor-opciones-tabla mb-2"
                                                                        data-columna="{{ $i }}">
                                                                        @foreach ($columna['opciones'] as $j => $op)
                                                                            <div class="input-group mb-1">
                                                                                <input type="text" class="form-control"
                                                                                    name="opcion_columna[]"
                                                                                    value="{{ $op['opcion'] }}"
                                                                                    data-id="{{ $op['id'] }}">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-danger btn-sm btnEliminarOpcionTabla">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button"
                                                                        class="btn btn-outline-success btn-sm agregarOpcionTabla"
                                                                        data-columna="{{ $i }}"><i
                                                                            class="fas fa-plus me-1"></i>Agregar opción
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endforeach

                                                        <button type="button"
                                                            class="btn btn-outline-info btnAgregarColumnaEdit">
                                                            <i class="fas fa-plus-circle"></i> Nueva columna
                                                        </button>
                                                        <div class="container mb-2 mt-2">
                                                            @if ($pregunta['obligatorio'] === 'si')
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="obligatorioPreguntaEdit"
                                                                    name="obligatorioPreguntaEdit" checked>
                                                                <label class="form-check-label"
                                                                    for="obligatorioPregunta">Obligatorio</label>
                                                            @elseif ($pregunta['obligatorio'] === 'no')
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="obligatorioPreguntaEdit"
                                                                    name="obligatorioPreguntaEdit">
                                                                <label class="form-check-label"
                                                                    for="obligatorioPregunta">Obligatorio</label>
                                                            @endif
                                                        </div>

                                                        <div class="botones-edicion d-flex gap-2 mt-2">
                                                            <button class="btn btn-danger btn-md btnCancelarEdicion"
                                                                type="button"><i
                                                                    class="me-1 fas fa-window-close "></i>Cancelar</button>
                                                            <button class="btn btn-primary btn-lg btnGuardarEdicion"
                                                                type="button"><i class="me-1 fas fa-save "></i>Guardar
                                                                Cambios</button>

                                                        </div>

                                                    </div>

                                                </div>
                                            @elseif ($pregunta['tipo'] === 'opcional')
                                                <div class="pregunta-card" id="pregunta_{{ $pregunta['id'] }}"
                                                    data-tipo="{{ $pregunta['tipo'] }}">
                                                    <div class="vista-normal">
                                                        @foreach ($pregunta['opciones'] as $opcion)
                                                            <div class="form-check">
                                                                @if ($pregunta['varias_respuestas'] === 'si')
                                                                    <input type="checkbox" name="masDeunaOpcion[]"
                                                                        class="form-check-input">
                                                                    <label
                                                                        class="form-check-label">{{ $opcion['opcion'] }}</label>
                                                                @else
                                                                    <input type="radio" name="masDeunaOpcion"
                                                                        class="form-check-input">
                                                                    <label
                                                                        class="form-check-label">{{ $opcion['opcion'] }}</label>
                                                                @endif

                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="vista-edicion d-none mt-3 mb-3">

                                                        {{-- titulo editable --}}
                                                        <div class="container mb-2">
                                                            <label for="" class="form-label">Editar titulo</label>
                                                            <input type="text" value="{{ $pregunta['titulo'] }}"
                                                                class="form-control mb-2" name="tituloPreguntaEdit"
                                                                id="tituloPreguntaEdit"
                                                                placeholder="Título de la pregunta">
                                                        </div>

                                                        {{-- Opciones editables --}}
                                                        <div class="contenedor-opciones-editables mb-2">
                                                            @foreach ($pregunta['opciones'] as $opcion)
                                                                <div class="input-group mb-1">
                                                                    <input type="text" name="opciones_editables[]"
                                                                        class="form-control"
                                                                        value="{{ $opcion['opcion'] }}"
                                                                        data-id="{{ $opcion['id'] }}">
                                                                    <button
                                                                        class="btn btn-outline-danger btn-sm btnEliminarOpcion"
                                                                        type="button">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        </div>


                                                        <div class="container mb-3 mt-3">
                                                            @if ($pregunta['obligatorio'] === 'si')
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="obligatorioPreguntaEdit"
                                                                    name="obligatorioPreguntaEdit" checked>
                                                                <label class="form-check-label"
                                                                    for="obligatorioPregunta">Obligatorio</label>
                                                            @elseif ($pregunta['obligatorio'] === 'no')
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="obligatorioPreguntaEdit"
                                                                    name="obligatorioPreguntaEdit">
                                                                <label class="form-check-label"
                                                                    for="obligatorioPregunta">Obligatorio</label>
                                                            @endif

                                                            @if ($pregunta['varias_respuestas'] === 'si')
                                                                <input type="checkbox" name="masDeunaOpcion"
                                                                    id="masDeunaOpcion" class="form-check-input ms-2"
                                                                    checked>
                                                                <label class="form-check-label">Mas de 1 opción</label>
                                                            @else
                                                                <input type="checkbox" name="masDeunaOpcion"
                                                                    id="masDeunaOpcion" class="form-check-input ms-2">
                                                                <label class="form-check-label">Mas de 1 opcion</label>
                                                            @endif
                                                        </div>


                                                        {{-- Botones de acción --}}
                                                        <div class="botones-edicion d-flex gap-2">
                                                            <button class="btn btn-outline-success btn-sm agregarOpcion"
                                                                type="button">
                                                                Agregar opción
                                                            </button>

                                                            <button
                                                                class="btn btn-outline-primary btn-sm btnGuardarEdicion"
                                                                type="button">Guardar</button>
                                                            <button
                                                                class="btn btn-outline-danger btn-sm btnCancelarEdicion"
                                                                type="button">Cancelar</button>



                                                        </div>
                                                    </div>

                                                </div>
                                            @elseif ($pregunta['tipo'] === 'texto')
                                                <div class="pregunta-card" id="pregunta_{{ $pregunta['id'] }}"
                                                    data-tipo="{{ $pregunta['tipo'] }}">
                                                    {{-- Vista normal --}}
                                                    <div class="vista-normal">
                                                        <input type="text" class="form-control mt-2" disabled />
                                                    </div>
                                                    {{-- Vista edición --}}
                                                    <div class="vista-edicion d-none mt-3">
                                                        <label class="form-label">Editar título</label>
                                                        <input type="text" name="tituloPreguntaEdit"
                                                            value="{{ $pregunta['titulo'] }}" class="form-control mb-2">

                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="obligatorioPreguntaEdit"
                                                                {{ $pregunta['obligatorio'] === 'si' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="obligatorioPreguntaEdit">Obligatorio</label>
                                                        </div>

                                                        <button class="btn btn-success btnGuardarEdicion">Guardar</button>
                                                        <button
                                                            class="btn btn-secondary btnCancelarEdicion">Cancelar</button>
                                                    </div>
                                                </div>
                                            @elseif ($pregunta['tipo'] === 'numerico')
                                                <div class="pregunta-card" id="pregunta_{{ $pregunta['id'] }}"
                                                    data-tipo="{{ $pregunta['tipo'] }}">
                                                    {{-- Vista normal --}}
                                                    <div class="vista-normal">

                                                        <input type="number" step="any" class="form-control mt-2" />
                                                    </div>

                                                    {{-- Vista edición --}}
                                                    <div class="vista-edicion d-none mt-3">
                                                        <label class="form-label">Editar título</label>
                                                        <input type="text" name="tituloPreguntaEdit"
                                                            value="{{ $pregunta['titulo'] }}" class="form-control mb-2">

                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="obligatorioPreguntaEdit"
                                                                {{ $pregunta['obligatorio'] === 'si' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="obligatorioPreguntaEdit">Obligatorio</label>
                                                        </div>

                                                        <button class="btn btn-success btnGuardarEdicion">Guardar</button>
                                                        <button
                                                            class="btn btn-secondary btnCancelarEdicion">Cancelar</button>
                                                    </div>
                                                </div>
                                            @elseif ($pregunta['tipo'] === 'porcentaje')
                                                <input type="number" step="any" max="100"
                                                    class="form-control mt-2" placeholder="% máx 100" />
                                            @else
                                                <p class="text-muted mt-2">Tipo de pregunta desconocido:
                                                    {{ $pregunta['tipo'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3 d-none botones-edicion">
                                        <button class="btn btn-success btn-sm me-2 btnGuardarCambios">Guardar</button>
                                        <button class="btn btn-secondary btn-sm me-2 btnCancelarEdicion">Cancelar</button>
                                        <button class="btn btn-outline-primary btn-sm me-2 btnAgregarFila">Agregar
                                            Fila</button>
                                        <button class="btn btn-outline-info btn-sm btnAgregarColumna">Agregar
                                            Columna</button>
                                    </div>
                                </div>


                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>




    </div>

    <!-- Modal Crear Pregunta -->
    <div class="modal fade" id="modalPregunta" tabindex="-1" aria-labelledby="modalPreguntaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Encabezado -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span class="badge text-bg-primary">NUEVA PREGUNTA</span>
                    </h4>
                    <span class="ms-3 text-light">Campos obligatorios <strong class="text-danger">(*)</strong></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Cuerpo del Modal -->
                <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                    <div class="row p-3 border rounded position-relative mx-auto">

                        <!-- Panel Izquierdo: Configuración -->
                        <div class="col-md-4">
                            <!-- Pregunta -->
                            <div class="mb-3">
                                <label for="descripcionPregunta" class="form-label">Pregunta: <strong
                                        class="text-danger">*</strong></label>
                                <textarea class="form-control" id="descripcionPregunta" name="descripcionPregunta" rows="3"></textarea>
                                <div id="_descripcionPregunta"></div>
                            </div>

                            <!-- Tipo de pregunta -->
                            <div class="mb-3">
                                <label for="tipoPregunta" class="form-label">Tipo de pregunta: <strong
                                        class="text-danger">*</strong></label>
                                <select id="tipoPregunta" name="tipoPregunta" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="texto">Texto</option>
                                    <option value="numerico">Número</option>
                                    <option value="opcional">Opción</option>
                                    <option value="tabla">Tabla</option>
                                </select>
                                <div id="_tipoPregunta"></div>
                            </div>

                            <!-- Campo obligatorio -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="obligatorioPregunta"
                                    name="obligatorioPregunta" checked>
                                <label class="form-check-label" for="obligatorioPregunta">Obligatorio</label>
                            </div>

                            <!-- Opciones (solo visible si aplica) -->
                            <div id="contenedorOpciones" class="mb-3" style="display: none;">
                                <label class="form-label">Opciones:</label>
                                <div id="opcionesContainer"></div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                    id="agregarOpcionBtn">
                                    + Añadir opción
                                </button>
                                <br>
                                <div class="mt-2">
                                    <input class="form-check-input mt-2" type="checkbox" id="varias_respuestas"
                                        name="varias_respuestas">
                                    <label class="form-check-label mt-2 ms-1" for="varias_respuestas">Mas de 1
                                        opcion</label>
                                </div>

                            </div>

                            <!-- Botón de guardar -->
                            <div class="d-grid">
                                <button type="button" class="btn btn-success" id="agregarPreguntaFinal">
                                    Guardar Pregunta <i class="fas fa-arrow-circle-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Panel Derecho: Vista previa -->
                        <div class="col-md-8 border-start border-primary ps-4">
                            <h5 class="mb-3">Vista previa:</h5>
                            <div id="vistaPreviaPregunta" class="bg-light p-3 rounded border"></div>
                        </div>
                    </div>
                </div>

                <!-- Pie del Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal crear Columna-->
    <div class="modal fade" id="modalAgregarColumna" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Agregar nueva columna</h5>
                    <span class="ms-3 text-light">Campos obligatorios <strong class="text-danger">(*)</strong></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarColumna">
                        <div class="mb-3">
                            <label for="nombreColumna" class="form-label">Nombre de la columna <strong
                                    class="text-danger">(*)</strong></label>
                            <input type="text" class="form-control" id="nombreColumna" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de columna <strong class="text-danger">(*)</strong></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tipo-col" type="radio" name="tipoColumna"
                                    id="tipoNumero" value="numero">
                                <label class="form-check-label" for="tipoNumero">Número</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tipo-col" type="radio" name="tipoColumna"
                                    id="tipoTexto" value="texto">
                                <label class="form-check-label" for="tipoTexto">Texto</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tipo-col" type="radio" name="tipoColumna"
                                    id="tipoOpcion" value="opcion">
                                <label class="form-check-label" for="tipoOpcion">Opción</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tipo-col" type="radio" name="tipoColumna"
                                    id="tipoPorcentaje" value="porcentaje">
                                <label class="form-check-label" for="tipoPorcentaje">Porcentaje</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tipo-col" type="radio" name="tipoColumna"
                                    id="tipoPregunta" value="pregunta">
                                <label class="form-check-label" for="tipoPregunta">Pregunta</label>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="esSumable" name="esSumable">
                            <label class="form-check-label" for="esSumable">¿Es sumable?</label>
                        </div>

                        <!-- Contenedor para opciones -->
                        <div id="opciones_container" class="mt-3 d-none">
                            <label class="mb-1">Opciones</label>
                            <div id="lista_opciones"></div>
                            <button type="button" id="btn_agregar_opcion" class="btn btn-sm btn-secondary mt-2 mb-2">➕
                                Agregar opción</button>
                        </div>
                        <button class="btn btn-info" id="btn_ver_tabla">Ver tabla</button>
                        <button type="submit" class="btn btn-primary ms-3" id="btn_agregar_columna">Agregar
                            columna</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- <!-- Modal Editar Tabla -->
    <div class="modal fade" id="modalEditarPregunta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formEditarTabla">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Pregunta Tipo Tabla</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="pregunta_id" id="tabla_id">

                        <div class="mb-3">
                            <label>Título de la Pregunta</label>
                            <input type="text" class="form-control" name="titulo" id="tabla_titulo">
                        </div>

                        <div id="contenedorColumnas">
                            <!-- columnas se generarán dinámicamente con JS -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Modal -->
    <div class="modal fade" id="modalMoverPregunta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mover pregunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formMoverPregunta">
                        <input type="hidden" name="mover_id" id="mover_id">
                        <div class="mb-3">
                            <label class="mb-2">Selecciona la pregunta destino:</label>
                            <select class="form-select" id="destino_id" name="destino_id">
                                @foreach ($preguntas as $p)
                                    <option value="{{ $p['id'] }}">{{ $p['titulo'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="mb-2">Seleccionar posicion...!</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accion" value="antes" checked>
                                <label class="form-check-label">Antes de la seleccionada</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accion" value="despues">
                                <label class="form-check-label">Después de la seleccionada</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarMover">Mover</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        window.rutaConstructorTabla = "{{ route('preguntas.constructor_tabla') }}";
    </script>
@endsection

@section('scripts')
    
    <script src="{{ asset('js/modulos/encuesta/editar_encuesta.js') }}" type="module"></script>
@endsection
