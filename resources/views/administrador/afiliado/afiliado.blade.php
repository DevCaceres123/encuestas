@extends('principal')
@section('titulo', 'distrito y comunidades')
@section('contenido')

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">LISTA DE DISTRITOS</h4>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoDistrito">
                                <i class="fas fa-plus me-1"></i> Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear reunions --}}
                                <table class="table  mb-0 table-centered" id="table_distrito">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>CI</th>
                                            <th>NOMBRES</th>
                                            <th>PATARNO</th>
                                            <th>MATARNO</th>
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









@endsection


@section('scripts')

    {{-- <script src="{{ asset('js/modulos/distrito/distrito_comunidad.js') }}" type="module"></script> --}}
@endsection
