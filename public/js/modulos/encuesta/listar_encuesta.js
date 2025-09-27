import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

let permisosGlobal;
let tabla_encuesta;

$(document).ready(function () {

    listar_afiliado();
});


function listar_afiliado() {

    tabla_encuesta = $('#table_encuesta').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: 'listarEncuesta', // Ruta que recibe la solicitud en el servidor
            type: 'GET', // Método de la solicitud (GET o POST)
            dataSrc: function (json) {

                permisosGlobal = json.permisos;
                // console.log(permisosGlobal); // Guardar los permisos para usarlos en las columnas
                return json.data; // Data que se pasará al DataTable
            }
        },
        columns: [
            {
                data: null,
                className: 'table-td',
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Usar meta.row para obtener el índice de la fila
                }
            },
            {
                data: 'titulo',
                className: 'table-td text-uppercase',
                render: function (data) {
                    return `                            
                        ${data}
                    `;
                }
            },
            {
                data: 'descripcion',
                className: 'table-td text-uppercase',
                render: function (data) {
                    return data;
                }
            },
            {
                data: 'created_at_formateado',
                className: 'table-td text-uppercase',
                render: function (data) {
                    return data;
                }
            },
            {
                data: null,
                className: 'table-td text-end',
                render: function (data, type, row) {

                    return ` <div class="d-flex justify-content-end">

                         ${permisosGlobal.editar ?
                            `
                        <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center eliminar_encuesta me-1" data-id="${row.id}" title="Eliminar Encuesta">
                            <i class="fas fa-window-close fs-16"></i>
                        </a>
                            `
                            : ``
                        }
                      
                             ${permisosGlobal.eliminar ?
                            ` <a class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center editar_encuesta me-1" data-id="${row.id}" title="Editar Encuesta">
                            <i class="fas fa-pencil-alt fs-16"></i>
                        </a>`
                            : ``
                        }

                        ${permisosGlobal.ver_preguntas ?
                            ` <a href="verEncuesta/${row.id}" class="btn btn-sm btn-outline-info px-2 d-inline-flex align-items-center me-1" data-id="${row.id}" title="Ver Preguntas">
                            <i class="fas fa-eye   fs-16"></i>
                        </a>`
                            : ``
                        }  
                        ${permisosGlobal.ver_informe ?
                            ` <a href="verInforme/${row.id}" class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center" data-id="${row.id}" title="Ver Informe">
                            <i class="fas fa-file-alt  fs-16"></i>
                        </a>`
                            : ``
                        }                                             
                        </div>`;

                }
            },

            {
                data: null,
                className: 'table-td',
                render: function (data, type, row) {

                    let estadoChecked = row.estado === "activo" ? 'checked' : '';

                    // Aquí verificamos el permiso de desactivar
                    let desactivarContent = permisosGlobal['estado'] ? `
                            <a class="cambiar_estado_encuesta" data-id="${row.id},${row.estado}">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" 
                                           ${estadoChecked} style="transform: scale(2.0);">
                                </div>
                            </a>` : `
                           <p>No permitido...<p/>
                        `;

                    return `
                            <div data-class="">
                                ${desactivarContent}
                            </div>`;

                }
            },


        ],
    });
}


// Llamada a la función para recargar la tabla después de una operación
function actualizarTabla() {

    tabla_encuesta.ajax.reload(null, false); // Recarga los datos sin resetear el paginado
}

// NUEVA ESCUESTA

$('#formnuevo_encuesta').submit(function (e) {
    e.preventDefault();
    let datosFormulario = $('#formnuevo_encuesta').serialize();
    $("#btnnuevo_encuesta").prop("disabled", true);
    vaciar_errores("formnuevo_encuesta");
    crud("admin/encuestas", "POST", null, datosFormulario, function (error, response) {


        $("#btnnuevo_encuesta").prop("disabled", false);
        // console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
      
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        //si todo esta correcto muestra el mensaje de correcto
        $('#modalEncuesta').modal('hide');
        vaciar_formulario("formnuevo_encuesta");
        mensajeAlerta(response.mensaje, response.tipo);
        actualizarTabla();

    })
});





// eliminar afiliado
$('#table_encuesta').on('click', '.eliminar_encuesta', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let encuesta_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de Eliminar la encuesta?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Estoy seguro",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/eliminarEncuesta", "DELETE", encuesta_id, null, function (error, response) {

                // console.log(response);
                // Verificamos que no haya un error o que todos los campos sean llenados
                if (response.tipo === "errores") {
                    mensajeAlerta(response.mensaje, "errores");
                    return;
                }
                if (response.tipo != "exito") {
                    mensajeAlerta(response.mensaje, response.tipo);
                    return;
                }
                // si todo esta correcto muestra el mensaje de correcto
                mensajeAlerta(response.mensaje, response.tipo);
                actualizarTabla();
            })
        } else {
            alerta_top('error', 'Se canceló la eliminacion');
        }
    })
});


$('#table_encuesta').on('click', '.editar_encuesta', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let encuesta_id = $(this).data('id'); // Obtener el id del afiliado desde el data-id
    $('#modalEncuestaEdit').modal('show');
    crud("admin/encuestas", "GET", encuesta_id+'/edit', null, function (error, response) {

        console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo === "errores") {
            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }


        $('#idEncuesta').val(response.mensaje.id);
        $('#tituloEdit').val(response.mensaje.titulo);
        $('#descripcionEdit').val(response.mensaje.descripcion);

    })
});


// EDITAR ENCUESTA


$('#formnuevo_encuestaEdit').submit(function (e) {
    e.preventDefault();

    let datosFormulario={
        tituloEdit: $('#tituloEdit').val(),
        descripcionEdit: $('#descripcionEdit').val(),
    };


    let idEncuesta = $('#idEncuesta').val();
    $("#btnnuevo_encuestaEdit").prop("disabled", true);
    vaciar_errores("formnuevo_encuestaEdit");
    crud("admin/actualizarEncuesta", "PUT", idEncuesta, datosFormulario, function (error, response) {


        $("#btnnuevo_encuestaEdit").prop("disabled", false);

        // console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
      
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        //si todo esta correcto muestra el mensaje de correcto
        $('#modalEncuestaEdit').modal('hide');
        vaciar_formulario("formnuevo_encuestaEdit");
        mensajeAlerta(response.mensaje, response.tipo);
        actualizarTabla();

    })
});




$('#table_encuesta').on('click', '.cambiar_estado_encuesta', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página


    // Obtener el valor de data-id
    var dataId = $(this).data('id');

    // Separar el id y el estado
    var values = dataId.split(',');

    let datos =
    {
        id_afiliado: values[0],
        estado: values[1]
    }

    crud("admin/actualizarEstado", "PUT", values[0], datos, function (error, response) {
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje.estado[0], "error");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        mensajeAlerta(response.mensaje, response.tipo);

        actualizarTabla();


    });

});