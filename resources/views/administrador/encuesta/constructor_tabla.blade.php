<div style="position: relative; display: inline-block;" class="mt-3">
    <!-- Botón para agregar columna arriba a la derecha -->
    <button type="button" class="btn-agregar-columna p-1 btn btn-primary text-center"
        style="position: absolute; top: -30px; left: 0; z-index: 1;" data-bs-toggle="modal"
        data-bs-target="#modalAgregarColumna">
        <i class="fas fa-plus-circle"></i>
    </button>
    <div style="overflow-x: auto; max-width: 100%;" class="">
        <table id="tabla-pregunta" class="table table-bordered"
            style="width: 100%;
    border-collapse: collapse;
    table-layout: auto;">
            <thead>
                <tr id="encabezados" class="text-center table-primary text-light">


                </tr>
            </thead>
            <tbody id="cuerpo-tabla">
                <tr>



                </tr>
            </tbody>
        </table>
    </div>

    <!-- Botón para agregar fila abajo a la izquierda -->
    <button type="button" class="btn-agregar-fila p-1 btn btn-primary text-center"
        style="position: absolute; bottom: -7px; left: 0; z-index: 1;">
        <i class="fas fa-plus-circle"></i>
    </button>

</div>
<div class="mt-3">
    <button type="button" id="guardarPreguntasTabla" class="btn btn-success btn-sm">Guardar <i class="fas fa-table ms-1"></i></button>
</div>
