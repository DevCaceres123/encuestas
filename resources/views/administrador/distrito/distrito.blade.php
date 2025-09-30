@extends('principal')
@section('titulo', 'distrito y comunidades')
@section('contenido')

    <div class="row py-5">
        <div class="col-12 col-md-9 card shadow-lg border-0 rounded-4 overflow-hidden m-auto mb-3">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">LISTA DE DISTRITOS</h4>
                        </div>
                        <div class="col-auto">
                            @can('distrito_comunidad.distrito.crear')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoDistrito">
                                    <i class="fas fa-plus me-1"></i> Nuevo
                                </button>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear reunions --}}
                                <table class="table  mb-0 table-centered" id="table_distrito">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>TITULO</th>
                                            <th>DESCRIPCION</th>
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

        <div class="col-12 col-md-9 card shadow-lg border-0 rounded-4 overflow-hidden m-auto">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">LISTA DE COMUNIDADES</h4>
                        </div>
                        <div class="col-auto">
                            @can('distrito_comunidad.comunidad.crear')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaComunidad">
                                    <i class="fas fa-plus me-1"></i> Nuevo
                                </button>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear reunions --}}
                                <table class="table  mb-0 table-centered" id="table_comunidad">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>TITULO</th>
                                            <th>DESCRIPCION</th>
                                            <th>DISTRITO</th>
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

    <div class="modal fade" id="modalNuevoDistrito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">AGREGAR DISTRITO</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formnuevo_distrito">
                        <div class="row">
                            <div class="row">
                                <div class="form-group py-2 col-12 col-md-12">


                                    <label for="" class="form-label">TITULO</label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="titulo_distrito"
                                            id="titulo_distrito">
                                        <div id="_titulo_distrito">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-md-12">
                                    <label for="" class="form-label">DESCRIPCION</label>
                                    <textarea class="form-control" placeholder="Ingrese Descripcion" name="descripcion_distrito" id="descripcion_distrito"
                                        style="height: 100px"></textarea>
                                    <div id="_descripcion_distrito">

                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                                    class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success rounded btn-sm" id="btnDistrito_nuevo"><i
                                    class="ri-save-3-line me-1 align-middle"></i> guardar</button>
                        </div>


                    </form>
                </div>
            </div>


        </div>

    </div>

    {{-- CREAR COMUNIDADES --}}
    <div class="modal fade" id="modalNuevaComunidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">AGREGAR COMUNIDAD</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formnuevo_comunidad">
                        <div class="row">
                            <div class="row">
                                <div class="form-group py-2 col-12 col-md-12">


                                    <label for="" class="form-label">TITULO</label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="titulo_comunidad"
                                            id="titulo_comunidad">
                                        <div id="_titulo_comunidad">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-md-12">
                                    <label for="" class="form-label">DESCRIPCION</label>
                                    <textarea class="form-control" placeholder="Ingrese Descripcion" name="descripcion_comunidad"
                                        id="descripcion_comunidad" style="height: 100px"></textarea>
                                    <div id="_descripcion_comunidad">

                                    </div>
                                </div>

                                <div class="form-group py-2 col-md-12">
                                    <label for="" class="form-label">SELECCIONAR DISTRITO</label>
                                    <select class="form-select" aria-label="Default select example" id="distrito_nombre"
                                        name="distrito_nombre">
                                        <option disabled selected>Seleccionar</option>
                                        @foreach ($distritos as $item)
                                            <option value="{{ $item->id }}">{{ $item->titulo }}</option>
                                        @endforeach


                                    </select>
                                    <div id="_distrito_nombre">

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                                    class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success rounded btn-sm" id="btnComunidad_nuevo"><i
                                    class="ri-save-3-line me-1 align-middle"></i> guardar</button>
                        </div>


                    </form>
                </div>
            </div>


        </div>

    </div>



    {{-- EDITAR DISTRITO --}}

    <div class="modal fade" id="modalEditarDistrito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">EDITAR DISTRITO</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar_distrito">
                        <div class="row">
                            <div class="row">
                                <div class="form-group py-2 col-12 col-md-12">

                                    <label for="" class="form-label">TITULO</label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="hidden" name="distrito_id" id="distrito_id">
                                        <input type="text" class="form-control rounded" name="titulo_distrito"
                                            id="titulo_distrito-edit" required>

                                        <div id="_titulo_distrito-edit">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-md-12">
                                    <label for="" class="form-label">DESCRIPCION</label>
                                    <textarea class="form-control" placeholder="Ingrese Descripcion" name="descripcion_distrito-edit"
                                        id="descripcion_distrito-edit" style="height: 100px" required></textarea>
                                    <div id="_descripcion_distrito-edit">

                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                                    class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success rounded btn-sm" id="btnDistrito_editar"><i
                                    class="ri-save-3-line me-1 align-middle"></i> guardar</button>
                        </div>


                    </form>
                </div>
            </div>


        </div>

    </div>


@endsection


@section('scripts')

    <script src="{{ asset('js/modulos/distrito/distrito_comunidad.js') }}" type="module"></script>
@endsection
