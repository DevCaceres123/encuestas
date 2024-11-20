import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

$(document).ready(function () {
    let table_distrito = $("#table_distrito").DataTable({
        processing: true,
        responsive: true,
    });

    let table_comunidad = $("#table_comunidad").DataTable({
        processing: true,
        responsive: true,
    });
    listar_distrito();
    listar_comunidad();
});


function listar_distrito() {
    crud("admin/listarDistrito", "GET", null, null, function (error, respuesta) {
        // console.log(respuesta);
        if (error != null) {
            mensajeAlerta(error, "error");
            return; // Agregar un return para evitar ejecutar el resto si hay un error
        }

        let distrito = respuesta.distrito;
        let permissions = respuesta.permisos;



        $('#table_distrito').DataTable({
            responsive: true,
            data: distrito,
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
                    className: 'table-td ',
                    render: function (data) {
                        return `                            
                            ${data}
                        `;
                    }
                },
                {
                    data: 'descripcion',
                    className: 'table-td',
                    render: function (data) {
                        return data;
                    }
                },


                {
                    data: null,
                    className: 'table-td text-end',
                    render: function (data, type, row) {

                        return ` <div class="d-flex justify-content-around">

                             ${permissions['editar'] ?
                                `
                            <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center eliminar_distrito" data-id="${row.id}">
                                <i class="fas fa-window-close fs-16"></i>
                            </a>
                                `
                                : ``
                            }
                          
                                 ${permissions['eliminar'] ?
                                ` <a class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center editar_distrito" data-id="${row.id}">
                                <i class="fas fa-pencil-alt fs-16"></i>
                            </a>`
                                : ``
                            }
                          
                             
                            </div>`;


                    }
                },
            ],
            destroy: true
        });
    });
}



function listar_comunidad() {
    crud("admin/listarComunidad", "GET", null, null, function (error, respuesta) {
        // console.log(respuesta);
        if (error != null) {
            mensajeAlerta(error, "error");
            return; // Agregar un return para evitar ejecutar el resto si hay un error
        }

        let comunidad = respuesta.comunidades;
        let permissions = respuesta.permisos;



        $('#table_comunidad').DataTable({
            responsive: true,
            data: comunidad,
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
                    className: 'table-td ',
                    render: function (data) {
                        return `                            
                            ${data}
                        `;
                    }
                },
                {
                    data: 'descripcion',
                    className: 'table-td',
                    render: function (data) {
                        return data;
                    }
                },


                {
                    data: 'distrito',
                    className: 'table-td',
                    render: function (data) {
                        console.log(data);
                        let titulo = data == null ? "sin asignar" : data.titulo;
                        return titulo;
                    }
                },
                {
                    data: null,
                    className: 'table-td text-end',
                    render: function (data, type, row) {

                        return ` <div class="d-flex justify-content-around">

                             ${permissions['editar'] ?
                                `
                            <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center eliminar_comunidad" data-id="${row.id}">
                                <i class="fas fa-window-close fs-16"></i>
                            </a>
                                `
                                : ``
                            }
                          
                            
                          
                             
                            </div>`;


                    }
                },
            ],
            destroy: true
        });
    });
}

// CREAR NUEVO DISTRITO
$('#formnuevo_distrito').submit(function (e) {
    e.preventDefault();
    let datosFormulario = $('#formnuevo_distrito').serialize();
    $("#btnDistrito_nuevo").prop("disabled", true);
    vaciar_errores("formnuevo_distrito");
    crud("admin/distrito", "POST", null, datosFormulario, function (error, response) {
        $("#btnDistrito_nuevo").prop("disabled", false);
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
        vaciar_formulario("formnuevo_distrito");
        mensajeAlerta(response.mensaje, response.tipo);
        setTimeout(() => {
            location.reload();
        }, 1500);
        $('#modalNuevoDistrito').modal('hide');

    })
});


// CREAR COMUNIDAD
$('#formnuevo_comunidad').submit(function (e) {
    e.preventDefault();
    let datosFormulario = $('#formnuevo_comunidad').serialize();
    $("#btnComunidad_nuevo").prop("disabled", true);
    vaciar_errores("formnuevo_comunidad");
    crud("admin/nuevaComunidad", "POST", null, datosFormulario, function (error, response) {
        $("#btnComunidad_nuevo").prop("disabled", false);
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
        vaciar_formulario("formnuevo_comunidad");
        mensajeAlerta(response.mensaje, response.tipo);

        $('#modalNuevaComunidad').modal('hide');

        listar_comunidad();


    })
});

// eliminar distrito
$('#table_distrito').on('click', '.eliminar_distrito', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de Eliminar el distrito?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Estoy seguro",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/distrito", "DELETE", lector_id, null, function (error, response) {

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
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
        } else {
            alerta_top('error', 'Se canceló la eliminacion');
        }
    })
});


// editar distrito
$('#table_distrito').on('click', '.editar_distrito', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id


    crud("admin/distrito", "GET", lector_id, null, function (error, response) {

        console.log(response);

        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        $('#titulo_distrito-edit').val(response.mensaje.titulo);
        $('#descripcion_distrito-edit').val(response.mensaje.descripcion);
        $('#distrito_id').val(response.mensaje.id);

        $('#modalEditarDistrito').modal('show')
        // si todo esta correcto muestra el mensaje de correcto
    })

});


// ACTUALIZAR DISTRITO

$('#formEditar_distrito').submit(function (e) {
    e.preventDefault();
    let datosFormulario = {
        'titulo': $('#titulo_distrito-edit').val(),
        'descripcion': $('#descripcion_distrito-edit').val(),

    };
    let distrito_id = $('#distrito_id').val();
    $("#btnDistrito_editar").prop("disabled", true);
    vaciar_errores("formnuevo_comunidad");
    crud("admin/distrito", "PUT", distrito_id, datosFormulario, function (error, response) {
        $("#btnDistrito_editar").prop("disabled", false);
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
        vaciar_formulario("formnuevo_comunidad");
        mensajeAlerta(response.mensaje, response.tipo);

        $('#modalEditarDistrito').modal('hide');

        setTimeout(() => {
            location.reload();
        }, 1500);


    })
});



// eliminar comunidad
$('#table_comunidad').on('click', '.eliminar_comunidad', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de Eliminar el comunidad?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Estoy seguro",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/eliminar_comunidad", "DELETE", lector_id, null, function (error, response) {

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
               listar_comunidad;
            })
        } else {
            alerta_top('error', 'Se canceló la eliminacion');
        }
    })
});
