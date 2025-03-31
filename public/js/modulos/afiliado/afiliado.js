import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

let permisosGlobal;
let tabla_afiliado;

$(document).ready(function () {

    listar_afiliado();
});

function listar_afiliado() {

    tabla_afiliado = $('#table_afiliado').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: 'listarAfiliado', // Ruta que recibe la solicitud en el servidor
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
                data: 'ci',
                className: 'table-td text-uppercase',
                render: function (data) {
                    return `                            
                        ${data}
                    `;
                }
            },
            {
                data: 'nombres',
                className: 'table-td text-uppercase',
                render: function (data) {
                    return data;
                }
            },
            {
                data: 'paterno',
                className: 'table-td text-uppercase',
                render: function (data) {
                    return data;
                }
            },

            {
                data: 'materno',
                className: 'table-td text-uppercase',
                render: function (data) {
                    return data;
                }
            },

            {
                data: 'numero_familia',
                className: 'table-td',
                render: function (data) { 
                    // console.log(data);
                    let total_integrantes=data == null ? "Ninguno" : data.total_integrantes+"<span class='text-muted'> Integrantes</span>";
                    return total_integrantes;
                }
            },
            {
                data: null,
                className: 'table-td text-end',
                render: function (data, type, row) {
                   
                    return ` <div class="d-flex justify-content-center">

                         ${permisosGlobal.editar ?
                            `
                        <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center eliminar_distrito me-1" data-id="${row.id}">
                            <i class="fas fa-window-close fs-16"></i>
                        </a>
                            `
                            : ``
                        }
                      
                             ${permisosGlobal.eliminar ?
                            ` <a class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center editar_distrito" data-id="${row.id}">
                            <i class="fas fa-pencil-alt fs-16"></i>
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
                            <a class="cambiar_estado_afiliado" data-id="${row.id},${row.estado}">
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

    tabla_afiliado.ajax.reload(null, false); // Recarga los datos sin resetear el paginado
}


// NUEVO AFILIADO

$('#formnuevo_afiliado').submit(function (e) {
    e.preventDefault();
    let datosFormulario = $('#formnuevo_afiliado').serialize();
    $("#btnnuevo_afiliado").prop("disabled", true);
    vaciar_errores("formnuevo_afiliado");
    crud("admin/afiliado", "POST", null, datosFormulario, function (error, response) {

        
        $("#btnnuevo_afiliado").prop("disabled", false);
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
        $('#modalAfiliado').modal('hide');
        vaciar_formulario("formnuevo_afiliado");
        mensajeAlerta(response.mensaje, response.tipo);
        actualizarTabla();

    })
});



// eliminar distrito
$('#table_afiliado').on('click', '.eliminar_distrito', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let afiliado_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de Eliminar el Afiliado?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Estoy seguro",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/afiliado", "DELETE", afiliado_id, null, function (error, response) {

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
                // si todo esta correcto muestra el mensaje de correcto
                mensajeAlerta(response.mensaje, response.tipo);
                actualizarTabla();
            })
        } else {
            alerta_top('error', 'Se canceló la eliminacion');
        }
    })
});


// CAMBIAR ESTADO USAURIO


$('#table_afiliado').on('click', '.cambiar_estado_afiliado', function (e) {
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

    crud("admin/afiliado", "PUT", values[0], datos, function (error, response) {
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