@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')


    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="d-inline card-title p-2 bg-danger rounded text-light">LISTA DE ENCUESTAS</h4>
                        </div>
                        <div class="col-auto">
                            @can('encuestas.crear')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEncuesta">
                                    <i class="fas fa-plus me-1"></i> Nueva Encuesta
                                </button>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear reunions --}}
                                <table class="table  mb-0 table-centered table-bordered" id="table_encuesta">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>TITULO</th>
                                            <th>DESCRIPCION</th>
                                            <th>FECHA</th>
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


        {{-- CREAR ENCUESTA --}}

        <div class="modal fade" id="modalEncuesta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-center modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title " id="">
                            <span class="badge badge-outline-primary rounded">NUEVA ENCUESTA</span>
                        </h4>
                        <span class="ms-3 text-light">Campos obligatorios <strong class="text-danger">(*)</strong></span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formnuevo_encuesta">
                            <div class="container">
                                <div class="row">
                                    {{-- datos personsales --}}
                                    <div class=" row border border-3 rounded m-auto position-relative">

                                        <div class="form-group py-2 col-12 col-md-12 col-lg-12 mt-2">
                                            <label for="" class="form-label">
                                                <i class="fas fa-certificate   me-1"></i>
                                                TITULO <strong class="text-danger">(*)</strong></label>
                                            <div class="container-validation" id="group_usuarioReset">
                                                <input type="text" class="form-control rounded text-uppercase"
                                                    name="titulo" id="titulo" required>
                                                <div id="_titulo">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group py-2 col-12 col-md-12 col-lg-12">
                                            <label for="" class="form-label">
                                                <i class="fas fa-receipt   me-1"></i>
                                                DESCRIPCION <strong class="text-danger">(*)</strong></label>
                                            <div class="container-validation" id="group_usuarioReset">
                                                <textarea class="form-control" placeholder="Escriba una descripcion de la encuesta" id="descripcion"
                                                    style="height: 100px" name="descripcion"></textarea>
                                                <div id="_descripcion">

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                                            class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                                    <button type="submit" class="btn btn-success rounded btn-sm" id="btnnuevo_encuesta"><i
                                            class="ri-save-3-line me-1 align-middle"></i> guardar</button>
                                </div>


                        </form>
                    </div>
                </div>


            </div>

        </div>



    </div>


    {{-- EDITAR ENCUESTA --}}
    <div class="modal fade" id="modalEncuestaEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="">
                        <span class="badge badge-outline-primary rounded">EDITAR ENCUESTA</span>
                    </h4>
                    <span class="ms-3 text-light">Campos obligatorios <strong class="text-danger">(*)</strong></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formnuevo_encuestaEdit">
                        <div class="container">
                            <div class="row">
                                {{-- datos personsales --}}
                                <div class=" row border border-3 rounded m-auto position-relative">

                                    <div class="form-group py-2 col-12 col-md-12 col-lg-12 mt-2">
                                        <label for="" class="form-label">
                                            <i class="fas fa-certificate   me-1"></i>
                                            TITULO <strong class="text-danger">(*)</strong></label>
                                        <div class="container-validation" id="group_usuarioReset">
                                            <input type="hidden" class="form-control rounded text-uppercase"
                                                name="idEncuesta" id="idEncuesta" required>
                                            <input type="text" class="form-control rounded text-uppercase"
                                                name="tituloEdit" id="tituloEdit" required>
                                            <div id="_tituloEdit">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-2 col-12 col-md-12 col-lg-12">
                                        <label for="" class="form-label">
                                            <i class="fas fa-receipt   me-1"></i>
                                            DESCRIPCION <strong class="text-danger">(*)</strong></label>
                                        <div class="container-validation" id="group_usuarioReset">
                                            <textarea class="form-control" placeholder="Escriba una descripcion de la encuesta" id="descripcionEdit"
                                                style="height: 100px" name="descripcionEdit"></textarea>
                                            <div id="_descripcionEdit">

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                                <button type="submit" class="btn btn-success rounded btn-sm"
                                    id="btnnuevo_encuestaEdit"><i class="ri-save-3-line me-1 align-middle"></i>
                                    guardar</button>
                            </div>


                    </form>
                </div>
            </div>


        </div>

    </div>
@endsection


@section('scripts')

    <script src="{{ asset('js/modulos/encuesta/listar_encuesta.js') }}" type="module"></script>
@endsection
