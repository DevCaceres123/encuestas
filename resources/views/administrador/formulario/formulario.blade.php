@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">LISTA DE ENCUESTAS</h4>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAfiliado">
                                <i class="fas fa-plus me-1"></i> Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear reunions --}}
                                <table class="table  mb-0 table-centered" id="table_afiliado">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>CI</th>
                                            <th>NOMBRES</th>
                                            <th>PATARNO</th>
                                            <th>MATERNO</th>
                                            <th>T. INTEGRANTES</th>
                                            <th class="text-end">ACCIONES</th>
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



    {{-- CREAR DISTRITO --}}

    {{-- <div class="modal fade" id="modalAfiliado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="">
                        <span class="badge badge-outline-primary rounded">AGREGAR DISTRITO</span>
                    </h4>
                    <span class="ms-3 text-light">Campos obligatorios <strong class="text-danger">(*)</strong></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formnuevo_afiliado">
                        <div class="row">
                            <div class="row">
                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">CARNET DE INDENTIDAD <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="ci" id="ci"
                                            required>
                                        <div id="_ci">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-12 col-md-6 col-lg-3">
                                    <label for="" class="form-label">COMPLEMENTO</label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="complemento"
                                            id="complemento">
                                        <div id="_complemento">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-12 col-md-6 col-lg-3">
                                    <label for="" class="form-label">EXPEDIDO <strong
                                            class="text-danger">(*)</strong></label>

                                    <div class="container-validation" id="group_usuarioReset">
                                        <select class="form-select" aria-label="Default select example" id="expedido_id"
                                            name="expedido_id">
                                            <option disabled selected>Seleccionar</option>
                                            @foreach ($expedidos as $item)
                                                <option value="{{ $item->id }}">{{ $item->departamento }}</option>
                                            @endforeach


                                        </select>

                                        <div id="_expedido_id">

                                        </div>
                                    </div>
                                </div>



                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">NOMBRES <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="nombres" id="nombres"
                                            required>
                                        <div id="_nombres">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">PATERNO <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="paterno" id="paterno"
                                            required>
                                        <div id="_paterno">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">MATERNO <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="materno" id="materno"
                                            required>
                                        <div id="_materno">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">COMUNIDAD <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">

                                        <select class="form-select" aria-label="Default select example" id="comunidad_id"
                                            name="comunidad_id">
                                            <option disabled selected>Seleccionar</option>
                                            @foreach ($comunidadades as $item)
                                                <option value="{{ $item->id }}">{{ $item->titulo }}</option>
                                            @endforeach


                                        </select>
                                        <div id="_comunidad_id">

                                        </div>
                                    </div>
                                </div>
                                <label for="" class="form-label">DATOS FAMILIA </label>
                                <div class="col-12 ms-2 row border border-3 rounded m-auto">

                                    <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                        <label for="" class="form-label">INTEGRANTES MUJERES <strong
                                                class="text-danger">(*)</strong></label>
                                        <div class="container-validation" id="group_usuarioReset">
                                            <input type="number" class="form-control rounded" name="mujeres"
                                                id="mujeres" required>
                                            <div id="_mujeres">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                        <label for="" class="form-label">INTEGRANTES HOMBRES <strong
                                                class="text-danger">(*)</strong></label>
                                        <div class="container-validation" id="group_usuarioReset">
                                            <input type="number" class="form-control rounded" name="hombres"
                                                id="hombres" required>
                                            <div id="_hombres">

                                            </div>
                                        </div>
                                    </div>

                                  
                                </div>


                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                                    class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success rounded btn-sm" id="btnnuevo_afiliado"><i
                                    class="ri-save-3-line me-1 align-middle"></i> guardar</button>
                        </div>


                    </form>
                </div>
            </div>


        </div>

    </div> --}}









@endsection


@section('scripts')

    {{-- <script src="{{ asset('js/modulos/afiliado/afiliado.js') }}" type="module"></script> --}}
@endsection
