import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

let permisosGlobal;
let tabla_afiliado;

$(document).ready(function () {
  listar_formularo();
});

function listar_formularo() {
  tabla_afiliado = $('#table_formulario').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: 'listarFormularios', // Ruta que recibe la solicitud en el servidor
      type: 'GET', // Método de la solicitud (GET o POST)
      dataSrc: function (json) {
        permisosGlobal = json.permisos;
        // console.log(permisosGlobal); // Guardar los permisos para usarlos en las columnas
        return json.data; // Data que se pasará al DataTable
      },
    },
    columns: [
      {
        data: null,
        className: 'table-td',
        render: function (data, type, row, meta) {
          return meta.row + 1; // Usar meta.row para obtener el índice de la fila
        },
      },
      {
        data: 'titulo_formulario',
        className: 'table-td text-capitalize',
        render: function (data) {
          return `                            
                        ${data}
                    `;
        },
      },
      {
        data: 'descripcion_formulario',
        className: 'table-td text-capitalize',
        render: function (data) {
          return data;
        },
      },
      {
        data: 'created_at_formateado',
        className: 'table-td text-uppercase',
        render: function (data) {
          return data;
        },
      },

      {
        data: 'estado',
        className: 'table-td text-capitalize text-start',
        render: function (data) {
          if (data === 'proceso') {
            return `<span class="badge bg-warning text-light fs-5">${data}</span>`;
          }
          if (data === 'terminado') {
            return `<span class="badge bg-primary fs-5">${data}</span>`;
          } else {
            return data; // o agregar un estilo por defecto si deseas
          }
        },
      },

      {
        data: null,
        className: 'table-td text-end',
        render: function (data, type, row) {
          return ` <div class="d-flex justify-content-center">

                         ${
                           permisosGlobal.eliminar
                             ? `
                        <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center eliminar_formulario me-1" data-id="${row.id}" title="Eliminar Formulario">
                            <i class="fas fa-window-close fs-16"></i>
                        </a>
                            `
                             : ``
                         }
                      
                             ${
                               permisosGlobal.editar
                                 ? ` <a class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center me-1 editar_afiliado" data-id="${row.id}" title="Editar Formulario">
                            <i class="fas fa-pencil-alt fs-16"></i>
                        </a>`
                                 : ``
                             }

                         ${
                           permisosGlobal.responer_formulario
                             ? ` <a class="btn btn-sm btn-outline-info px-2 d-inline-flex align-items-center me-1 responderFormulario" data-id="${row.id}" title="Responer Formulario">
                            <i class="fas fa-clipboard-check fs-16"></i>
                        </a>`
                             : ``
                         }
                        ${
                          permisosGlobal.ver_respuestas
                            ? ` <a class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center editar_afiliado" data-id="${row.id}" title="Ver respuestas Afiliados">
                            <i class="fas fa-users fs-16"></i>
                        </a>`
                            : ``
                        }                                                                     
                  </div>`;
        },
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
  tabla_afiliado.ajax.reload(null, false); // Recarga los datos sin resetear el paginado
}

// Responder formulario
$('#table_formulario').on('click', '.responderFormulario', function (e) {
  e.preventDefault(); // Evitar que el enlace recargue la página
  let formulario_id = $(this).data('id'); // Obtener el id del afiliado desde el data-id
  $('#btnIniciarFormulario').data('formulario-id', formulario_id);

  $('#modalBuscarAfiliado').modal('show');
});

// BUSCAR AFILIADO
$('#buscarAfiliado').on('input', function () {
  let query = $(this).val().trim();

  if (query.length < 2) {
    $('#resultadosBusqueda').addClass('d-none').empty();
    return;
  }

  crud('admin/buscarAfiliado', 'GET', query, null, function (error, response) {
    // Limpiar siempre antes de mostrar resultados
    $('#resultadosBusqueda').empty();

    if (response.tipo === 'errores') {
      mensajeAlerta(response.mensaje, 'errores');
      $('#resultadosBusqueda').addClass('d-none');
      return;
    }

    // if (response.tipo !== 'exito') {
    //   mensajeAlerta(response.mensaje, response.tipo);
    //   $('#resultadosBusqueda').addClass('d-none');
    //   return;
    // }

    // console.log(response.tipo);
    // Si no encontró resultados, mostrar “No encontrado”
    if (response.tipo === 'no_encontrado') {
      $('#resultadosBusqueda').append(`
            <button type="button" class="list-group-item list-group-item-action seleccionar-afiliado text-capitalize text-danger text-center"                      
                data-ci="No encontrado"
                data-nombre="No encontrado">
                <i class='fas fa-user-times  fs-16 me-1'></i> Ningun afiliado encontrado
            </button>
        `);
      $('#resultadosBusqueda').removeClass('d-none');
    }

    if (response.tipo === 'exito') {
      // Si encontró afiliados, mostrarlos
      response.mensaje.forEach((afiliado) => {
        $('#resultadosBusqueda').append(`
            <button type="button" class="list-group-item list-group-item-action seleccionar-afiliado text-capitalize"
                data-id="${afiliado.id}"
                data-ci="${afiliado.ci}"
                data-nombre="${afiliado.nombres}"
                data-apellidos="${afiliado.paterno} ${afiliado.materno}">
                ${afiliado.ci} : ${afiliado.nombres} ${afiliado.paterno} ${afiliado.materno}
            </button>
        `);
      });

      $('#resultadosBusqueda').removeClass('d-none');
    }
  });
});

// Seleccionar afiliado
$(document).on('click', '.seleccionar-afiliado', function () {
  const id = $(this).data('id');
  const ci = $(this).data('ci');
  const nombre = $(this).data('nombre');
  const apellidos = $(this).data('apellidos');

  $('#nombreAfiliado').text(nombre + ' ' + apellidos);
  $('#ciAfiliado').text(ci);

  $('#detalleAfiliado').removeClass('d-none');
  $('#btnIniciarFormulario').prop('disabled', false).data('afiliado-id', id);

  $('#resultadosBusqueda').addClass('d-none').empty();
  $('#buscarAfiliado').val('');
});

// Empezar el formulario
$('#btnIniciarFormulario').on('click', function () {
    const afiliadoId = $(this).data('afiliado-id');
    const formularioId = $(this).data('formulario-id');

    if (!afiliadoId || !formularioId) {
        mensajeAlerta('Debe seleccionar un afiliado y un formulario antes de iniciar.', 'errores');
        return;
    }

    // Redirección GET con parámetros en URL
    window.location.href = `/admin/responderFormulario/${formularioId}/${afiliadoId}`;
});



// CREAR NUEVO FORMULARIO


// NUEVO FORMULARIO

$('#formCrearFormulario').submit(function (e) {
    e.preventDefault();
    let datosFormulario = $('#formCrearFormulario').serialize();
    $("#btn_guaradarFormulario").prop("disabled", true);
    vaciar_errores("formCrearFormulario");
    crud("admin/formulario", "POST", null, datosFormulario, function (error, response) {


        $("#btn_guaradarFormulario").prop("disabled", false);
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
        $('#modalCrearFormulario').modal('hide');
        vaciar_formulario("formCrearFormulario");
        mensajeAlerta(response.mensaje, response.tipo);
        actualizarTabla();

    })
});



// eliminar formulario
$('#table_formulario').on('click', '.eliminar_formulario', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let id_dato = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de Eliminar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Estoy seguro",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/formulario", "DELETE", id_dato, null, function (error, response) {

                //console.log(response);
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
