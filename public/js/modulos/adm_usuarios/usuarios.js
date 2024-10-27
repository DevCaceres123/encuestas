import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
let tablaUser;

$(document).ready(function () {
    tablaUser = $("#table_user").DataTable({
        processing: true,
        responsive: true,
    });
    listar_usuarios();
});



function listar_usuarios() {


    crud("admin/listarUsuarios", "GET", null, null, function (error, respuesta) {

        if (error != null) {
            mensajeAlerta(error, "error");
        }


        let i = 1;

        $('#table_user').DataTable({
            responsive: true,
            data: respuesta,
            columns: [
                {
                    data: null,
                    className: 'table-td',
                    render: function (data) {


                        return 1;
                    }
                },
                {
                    data: 'nombres',
                    className: 'table-td',
                    render: function (data, type, row) {

                        return `
                         <td><img src="/admin_template/images/logos/lang-logo/slack.png"
                             alt="" class="rounded-circle thumb-md me-1 d-inline">
                            ${data}
                        </td>
                        
                        `;
                    }
                },
                {
                    data: 'paterno',
                    className: 'table-td',
                    render: function (data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'materno',
                    className: 'table-td',
                    render: function (data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'roles',
                    render: function (data, type, row) {
                        let contenido = "";
                        if (data.length != 0) {

                            data.forEach(element => {
                                contenido = ` <span class="badge bg-success fs-5">
                                                            ${element.name}    
                                                 </span>`;
                            });

                        } else {
                            contenido = ` <span class="badge bg-success fs-5">
                            Sin roles asignados 
                            </span>`;
                        }

                        return contenido;
                    }
                },
                {
                    data: 'cod_targeta',
                    render: function (data, type, row) {
                        let contenido = "";
                        if (data == null) {
                            contenido = ` <span class="badge bg-danger fs-5">
                                                            Sin asignar
                                                        </span>`;
                        }
                        else {
                            contenido = `<span class="badge bg-success fs-5">
                                                            ${data}
                                                        </span>`;
                        }
                        return contenido;
                    }
                },
                {
                    data: null,
                    className: 'table-td',
                    render: function (data, type, row, meta) {
                        return `
                  <div class="text-end">
                   <td >
                       <a
                        class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center desactivar_usuario" data-id="${row.id}">
                           <i class="iconoir-trash fs-16"  ></i>

                        </a>
                        <a
                        class="btn btn-sm btn-outline-info px-2 d-inline-flex align-items-center"  data-id="${row.id}">
                            <i class="fas fa-pencil-alt fs-16" ></i>

                        </a>

                        <a
                        class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center asignar_targeta" data-id="${row.id}">
                            <i class="fas fa-id-card fs-16"  ></i>

                        </a>
                    </td>
                </div>
                    `;
                    }
                },
            ],
            destroy: true
        });
    });
}

// REGISTRAR  USUARIO
$('#formularioUsuario').submit(function (e) {

    e.preventDefault();
    let datosFormulario = $('#formularioUsuario').serialize();

    crud("admin/usuarios", "POST", null, datosFormulario, function (error, response) {

        console.log(response);
        // if (error != null) {
        //     mensajeAlerta(error, "error");
        //     return;
        // }
        if (response.tipo === "errores") {
            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }


        listar_usuarios();
        mensajeAlerta(response.mensaje, response.tipo);
        $('#ModalTargeta').modal('hide');

    });

});




// Agregar el evento de clic después de que la tabla haya sido creada
$('#table_user').on('click', '.desactivar_usuario', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página

    Swal.fire({
        title: '¿Eliminar Registro?',
        text: "Estas seguro que quiere eliminar el registro!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si,estoy seguro!'
    }).then((result) => {
        if (result.isConfirmed) {
            let id_alumno = $(this).data('id'); // Obtener el id del alumno desde el data-id
            // console.log(id_alumno);
            crud("eliminarAlumno", "DELETE", id_alumno, null, function (error, response) {

                if (error != null) {
                    mensajeAlerta(error, "error");
                    return;
                }
                if (response.tipo != "exito") {
                    mensajeAlerta(response.mensaje, response.tipo);
                    return;
                }

                listar_usuarios();
                mensajeAlerta(response.mensaje, response.tipo);

            });
        }
    })
});


$('#myTable').on('click', '.editar_alumno', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    alert("xd");
});



// asignar targeta
$('#table_user').on('click', '.asignar_targeta', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    $('#ModalTargeta').modal('show');
    let id_alumno = $(this).data('id'); // Obtener el id del alumno desde el data-id
    // console.log(id_alumno);
    crud("admin/usuarios", "GET", id_alumno, null, function (error, response) {

        if (error != null) {
            mensajeAlerta(error, "error");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        // console.log(response);
        $('#id_usuario_targeta').val(response.user.id);
        $('#codigo_targeta').val(response.codigo_targeta);
        //listar_usuarios();


    });

});

// registrar codigo de targeta
$('#registrtarCodigoTargeta').submit(function (e) {
    e.preventDefault();

    let datosFormulario = $('#registrtarCodigoTargeta').serialize();


    console.log(datosFormulario);

    crud("admin/asignar_targeta", "POST", null, datosFormulario, function (error, response) {

        if (error != null) {
            mensajeAlerta(error, "error");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        listar_usuarios();
        mensajeAlerta(response.mensaje, response.tipo);
        $('#ModalTargeta').modal('hide');

    });
})


$('#alumno_form_nuevo').submit(function (e) {
    e.preventDefault();
    const datosFormulario = $('#alumno_form_nuevo').serialize();
    console.log(datosFormulario);
    crud("nuevoAlumno", "POST", null, datosFormulario, function (error, respuesta) {

    });
});




