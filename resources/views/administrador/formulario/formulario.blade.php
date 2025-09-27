@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="d-inline card-title p-2 bg-danger rounded text-light">LISTA DE FORMULARIOS</h4>
                        </div>

                        <div class="col-auto">
                            @can('formulario.crear')
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearFormulario">
                                <i class="fas fa-plus me-1"></i> Nuevo
                            </button>
                            @endcan
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                {{-- tabla para crear reunions --}}
                                <table class="table  mb-0 table-centered table-bordered" id="table_formulario">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>TITULO</th>
                                            <th>DESCRIPCION</th>
                                            <th>FECHA</th>
                                            <TH>ESTADO</TH>
                                            <th class="text-end">ACCIONES</th>
                                            <th class="text-end">ESTADO</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL CREAR FORMULARIO -->
    <div class="modal fade" id="modalCrearFormulario" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        Crear Formulario
                    </h5>
                    <span class="ms-3 text-light">Campos obligatorios <strong class="text-danger">(*)</strong></span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="formCrearFormulario">
                        <div class="mb-3">
                            <label for="tituloFormulario" class="form-label">
                                <i class="fas fa-certificate me-1"></i> Título del Formulario <span class="text-danger">
                                    (*)</span>
                            </label>
                            <input type="text" class="form-control text-uppercase" id="tituloFormulario" name="tituloFormulario"
                                required>
                            <div id="_tituloFormulario">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcionFormulario" class="form-label">
                                <i class="fas fa-align-left me-1"></i> Descripción <span class="text-danger"> (*)</span>
                            </label>
                            <textarea class="form-control" id="descripcionFormulario" name="descripcionFormulario" rows="3" required></textarea>
                             <div id="_descripcionFormulario">

                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="encuestaFormulario" class="form-label">
                                <i class="fas fa-list me-1"></i> Seleccionar Encuesta <span class="text-danger"> (*)</span>
                            </label>
                            <select class="form-select" id="encuesta_id" name="encuesta_id" required>
                                <option value="" disabled selected>Seleccione una encuesta</option>
                                @foreach ($encuestas as $encuesta)
                                    <option value="{{ $encuesta->id }}">{{ $encuesta->titulo }}</option>
                                @endforeach
                            </select>
                            <div id="_encuesta_id">

                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-success" id="btn_guaradarFormulario">
                                <i class="fas fa-save me-1"></i> Guardar Formulario
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>



    <!-- MODAL BUSCAR AFILIADO -->
    <div class="modal fade" id="modalBuscarAfiliado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-search me-2"></i> Buscar Afiliado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- FORMULARIO DE BÚSQUEDA -->
                    <form id="formBuscarAfiliado">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="buscarAfiliado" name="buscarAfiliado"
                                placeholder="Ingrese CI o Nombre del afiliado" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>

                        <!-- Resultados dinámicos -->
                        <div id="resultadosBusqueda" class="list-group mt-2 d-none"
                            style="max-height: 200px; overflow-y: auto;">
                            <!-- Se llenará con botones de resultados -->
                        </div>
                    </form>

                    <!-- Detalle del afiliado seleccionado -->
                    <div id="detalleAfiliado" class="card mt-3 d-none text-capitalize">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-3x text-primary mb-2"></i>
                            <h5 id="nombreAfiliado"></h5>
                            <p class="text-muted mb-1">Documento de Identidad: <span id="ciAfiliado"></span></p>
                            <button id="btnIniciarFormulario" class="btn btn-success mt-2" disabled>
                                <i class="fas fa-play me-1"></i> Iniciar Responder Formulario
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>







@endsection


@section('scripts')
    <script src="{{ asset('js/modulos/formulario/formulario.js') }}" type="module"></script>
@endsection
