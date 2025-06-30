import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

// editar_encuesta.js
let preguntas = [];

$(document).ready(function () {
  // Detectar tipo de pregunta o cambios
  $('#tipoPregunta').on('change', function () {
    mostrarVistaPrevia();
  });

  // Botón: + Añadir opción
  $('#agregarOpcionBtn').on('click', function () {
    agregarOpcion();
  });

  // Botón: Siguiente (mostrar vista previa)
  $('.btn-agregar-pregunta').on('click', function () {
    mostrarVistaPrevia();
  });

  // CREAR NUEVA PREGUNTA
  $('#agregarPreguntaFinal').on('click', function () {
    preguntas = obtenerPreguntas();
    if (!preguntas) {
      return;
    }
    let encuesta_id = $('#idEncuesta').val();
    // console.log(preguntas);
    crud('admin/guardarPregunta', 'PUT', encuesta_id, preguntas, function (error, response) {
      $('#btnnuevo_encuesta').prop('disabled', false);
      console.log(response);
      // Verificamos que no haya un error o que todos los campos sean llenados

      if (response.tipo === 'errores') {
        mensajeAlerta(response.mensaje, 'errores');
        return;
      }
      if (response.tipo != 'exito') {
        mensajeAlerta(response.mensaje, response.tipo);
        return;
      }

      //si todo esta correcto muestra el mensaje de correcto
      $('#modalEncuesta').modal('hide');

      mensajeAlerta(response.mensaje, response.tipo);
      limpiarCampos();
      preguntas = [];
      setTimeout(() => {
        location.reload();
      }, 1500);
    });
  });
});

// Boton: agregarOpcion
function agregarOpcion() {
  const index = $('#opcionesContainer .input-group').length;
  const nuevaOpcion = $(`
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="opcion[]" placeholder="Opción ${index + 1}">
            <button class="btn btn-outline-danger btn-sm" type="button">X</button>
        </div>
    `);

  nuevaOpcion.find('button').on('click', function () {
    $(this).parent().remove();
    mostrarVistaPrevia();
  });

  $('#opcionesContainer').append(nuevaOpcion);
  mostrarVistaPrevia();
}

// Mostrar la vista previa de la pregunta
// Esta función se llama cada vez que se cambia el tipo de pregunta o se agrega una opción

function mostrarVistaPrevia() {
  const tipo = $('#tipoPregunta').val();
  const descripcion = $('#descripcionPregunta').val();
  const $vista = $('#vistaPreviaPregunta');
  const $contenedorOpciones = $('#contenedorOpciones');

  $vista.html('');

  if (!tipo) return;

  let contenido = `<p><strong>${descripcion}</strong></p>`;

  if (tipo === 'texto') {
    $('#agregarPreguntaFinal').prop('disabled', false);
    $('#guardarPreguntasTabla').prop('disabled', true);
    contenido += '<input type="text" class="form-control" disabled placeholder="Respuesta de texto">';
    $contenedorOpciones.hide();
  } else if (tipo === 'numerico') {
    $('#agregarPreguntaFinal').prop('disabled', false);
    $('#guardarPreguntasTabla').prop('disabled', true);
    contenido += '<input type="number" class="form-control" disabled placeholder="Respuesta numérica">';
    $contenedorOpciones.hide();
  } else if (tipo === 'opcional') {
    $('#agregarPreguntaFinal').prop('disabled', false);
    $('#guardarPreguntasTabla').prop('disabled', true);
    $contenedorOpciones.show();
    const opciones = $('#opcionesContainer input');
    opciones.each(function (i, input) {
      const val = $(input).val() || `Opción ${i + 1}`;
      contenido += `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" disabled>
                    <label class="form-check-label">${val}</label>
                </div>`;
    });
  } else if (tipo === 'tabla') {
    $('#agregarPreguntaFinal').prop('disabled', true);
    $('#guardarPreguntasTabla').prop('disabled', false);

    $contenedorOpciones.hide();
    $vista.html(contenido);
    $('<div>').load(window.rutaConstructorTabla, function (response, status, xhr) {
      if (status === 'error') {
        console.error('Error al cargar la vista de tabla:', xhr.status + ' ' + xhr.statusText);
      } else {
        $vista.append(this); // Añade el contenido debajo del texto
      }
    });
  }

  $vista.html(contenido);
}

// Limpiar campos de entrada
function limpiarCampos() {
  $('#descripcionPregunta').val('');
  $('#tipoPregunta').val('');
  $('#opcionesContainer').empty();
  $('#contenedorOpciones').hide();
}



// Obtener las preguntas y sus opciones
function obtenerPreguntas() {
  const tipo = $('#tipoPregunta').val();
  const descripcion = $('#descripcionPregunta').val().trim();
  let obligatorio = $('#obligatorioPregunta').is(':checked') ? 'si' : 'no';
  let varias_respuestas = $('#varias_respuestas').is(':checked') ? 'si' : 'no';
  let opciones = [];

  if (!tipo || !descripcion) {
    mensajeAlerta('Debes completar todos los campos.', 'error');
    return;
  }

  if (tipo === 'opcional') {
    $('#opcionesContainer input').each(function () {
      if ($(this).val().trim() !== '') {
        opciones.push($(this).val().trim());
      }
    });

    if (opciones.length === 0) {
      mensajeAlerta('Debes de agregar al menos una opcion', 'error');
      return;
    }
  }
  preguntas = {
    descripcionPregunta: descripcion,
    tipoPregunta: tipo,
    opciones: opciones,
    obligatorio: obligatorio,
    varias_respuestas: varias_respuestas,
  };

  // console.log(preguntas);

  return preguntas;
}



// LA PARTE DONDE CONSTRUIMOS LA TABLA SUS FILAS Y COLUMNAS
let filaIndex = 0;
let columnaIndex = 0;

// AGREGAR NUEVA FILA
$(document).on('click', '.btn-agregar-fila', function () {
  const columnas = $('#encabezados th').length;
  let nuevaFila = `<tr>`;

  for (let i = 0; i < columnas; i++) {
    const th = $('#encabezados th').eq(i);
    const tipo = th.data('tipo');
    const opciones = th.data('opciones') || []; // Para columnas tipo "opcion"
    let inputHTML = '';

    switch (tipo) {
      case 'numero':
        inputHTML = `<input type="number" name="respuestas[${filaIndex}][col_${i}]" class="form-control-sm rounded">`;
        break;
      case 'texto':
        inputHTML = `<textarea name="respuestas[${filaIndex}][col_${i}]" class="form-control-sm rounded" placeholder="Ingresa tu respuesta"></textarea>`;
        break;
      case 'opcion':
        inputHTML = `<select name="respuestas[${filaIndex}][col_${i}]" class="form-select rounded">`;
        // inputHTML += `<option value="">--</option>`;

        // Agregar dinámicamente las opciones
        if (Array.isArray(opciones)) {
          opciones.forEach((opt) => {
            inputHTML += `<option value="${opt}">${opt}</option>`;
          });
        }

        inputHTML += `</select>`;
        break;
      case 'porcentaje':
        inputHTML = `<input type="number" name="respuestas[${filaIndex}][col_${i}]" class="form-control-sm rounded" min="0" max="100" placeholder="%">`;
        break;
      case 'pregunta':
        inputHTML = `<input type="text" name="respuestas[${filaIndex}][col_${i}]" class="form-control-sm rounded" min="5" max="50" placeholder="Ingresa tu pregunta">`;
        break;
    }

    nuevaFila += `<td>${inputHTML}</td>`;
  }
  nuevaFila += `<td><button type="button" class="btn btn-danger btn-sm btn-eliminar-fila">🗑 Eliminar</button></td>`;
  nuevaFila += `</tr>`;

  $('#cuerpo-tabla').append(nuevaFila);
  filaIndex++;
});



// MODAL NUEVA COLUMNA
$(document).on('click', '.btn-agregar-columna', function () {
  $('#modalPregunta').modal('hide');
  $('#modalAgregarColumna').modal('show');
});

// AGREAR NUEVA COLUMNA A LA TABLA
$('#btn_agregar_columna').on('click', function (e) {
  e.preventDefault();
  const tipo = $('input[name="tipoColumna"]:checked').val();
  const nombre = $('#nombreColumna').val();
  const sumar = $('input[name="esSumable"]:checked').val();

  if (!tipo || !nombre) {
    mensajeAlerta('Debes completar los campos  de obligatorios', 'warning');
    return;
  }

  let opciones = [];
  if (tipo === 'opcion') {
    opciones = [];
    $('.opcion-texto').each(function () {
      const val = $(this).val().trim();
      if (val !== '') {
        opciones.push(val);
      }
    });

    if (opciones.length === 0) {
      mensajeAlerta('Debes de agregar al menos una opción', 'warning');
      return; // Evita continuar si no hay opciones válidas
    }
  }

  const th = $(`<th class='position-relative' contenteditable="true" data-tipo="${tipo}" data-sumar="${sumar}" data-opciones='${JSON.stringify(opciones)}'>${nombre}
    <button type="button" class="btn btn-sm position-absolute top-0 end-0 btn-danger btn-eliminar-columna"><i class='fas fa-window-close'></i></button></th>`);
  $('#encabezados').append(th);

  $('#cuerpo-tabla tr').each(function (filaIdx) {
    let inputHTML = '';
    switch (tipo) {
      case 'numero':
        inputHTML = `<input type="number" name="respuestas[${filaIdx}][col_${columnaIndex}]" class="form-control-sm rounded">`;
        break;
      case 'texto':
        inputHTML = `<textarea name="respuestas[${filaIdx}][col_${columnaIndex}]" class="form-control-sm rounded"  placeholder="Ingresa tu respuesta"></textarea>`;
        break;
      case 'opcion':
        inputHTML =
          `<select name="respuestas[${filaIdx}][col_${columnaIndex}]" class="form-select">` + opciones.map((o) => `<option value="${o}">${o}</option>`).join('') + `</select>`;
        break;
      case 'porcentaje':
        inputHTML = `<input type="number" name="respuestas[${filaIdx}][col_${columnaIndex}]" class="form-control-sm rounded" min="0" max="100"  placeholder="%">`;
        break;
      case 'pregunta':
        inputHTML = `<input type="text" name="respuestas[${filaIdx}][col_${columnaIndex}]" class="form-control-sm rounded" min="5" max="50" placeholder="Ingresa tu pregunta">`;
        break;
    }

    $(this).append(`<td>${inputHTML}</td>`);
  });

  columnaIndex++;
  mensajeAlerta('Columna agregada correctamente', 'exito');
  setTimeout(() => {
    $('#modalAgregarColumna').modal('hide');
    $('#modalPregunta').modal('show');
    $('#formAgregarColumna')[0].reset();
  }, 500);
});

// AGREGAR PREGUNTA EN COLUMNA

$('.tipo-col').on('change', function () {
  const tipoSeleccionado = $('input[name="tipoColumna"]:checked').val();

  if (tipoSeleccionado === 'opcion') {
    $('#opciones_container').removeClass('d-none');
  } else {
    $('#opciones_container').addClass('d-none');
    $('#lista_opciones').html('');
  }
});

// AGREAR OPCIONES A LA COLUMNA
$('#btn_agregar_opcion').on('click', function () {
  $('#lista_opciones').append(`
        <div class="input-group mb-2 opcion-individual">
            <input type="text" class="form-control opcion-texto" placeholder="Opción">
            <button type="button" class="btn btn-danger btn-sm btn-quitar-opcion">✖</button>
        </div>
    `);
});

// ELIMINAR OPCION
$(document).on('click', '.btn-quitar-opcion', function () {
  $(this).closest('.opcion-individual').remove();
});

// VOLVER A CARGAR LA TABLA
$('#btn_ver_tabla').on('click', function (e) {
  e.preventDefault();
  $('#modalAgregarColumna').modal('hide');
  $('#modalPregunta').modal('show');
});

// ELIMINAR COLUMNA
$(document).on('click', '.btn-eliminar-columna', function () {
  const th = $(this).closest('th');
  const index = th.index(); // Obtener la posición de la columna
  th.remove(); // Eliminar encabezado

  $('#cuerpo-tabla tr').each(function () {
    $(this).find('td').eq(index).remove(); // Eliminar celda correspondiente en cada fila
  });

  
  columnaIndex--;
});

// ELIMINAR FILA
$(document).on('click', '.btn-eliminar-fila', function () {
  $(this).closest('tr').remove(); // Eliminar toda la fila
  // filaIndex-- (opcional si lo estás controlando)
});

// GUARDAR PREGUNTAS DE TABLA
$(document).on('click', '#guardarPreguntasTabla', function () {
  const estructuraTabla = obtenerEstructuraTabla();

  if (estructuraTabla.correcto === false) {
    return; // Si la validación falla, no continuar
  }

  if (estructuraTabla.columnas.length === 0) {
    mensajeAlerta('Debes de crear almenos una pregunta', 'warning');
    return;
  }

  let encuesta_id = $('#idEncuesta').val();

  crud('admin/guardarPreguntasTabla', 'PUT', encuesta_id, estructuraTabla, function (error, response) {
    if (response.tipo === 'errores') {
      mensajeAlerta(response.mensaje, 'errores');
      return;
    }

    if (response.tipo !== 'exito') {
      mensajeAlerta(response.mensaje, response.tipo);
      return;
    }

    mensajeAlerta(response.mensaje, response.tipo);
    limpiarCampos();
    mostrarVistaPrevia();

    setTimeout(() => {
      location.reload();
    }, 1500);
  });
});


// Función para obtener la estructura de la tabla
// Esta función recorre las columnas y filas de la tabla para construir la estructura
function obtenerEstructuraTabla() {
  let obligatorio = $('#obligatorioPregunta').is(':checked') ? 'si' : 'no';

  let estructura = {
    tipo: 'tabla',
    titulo: $('#descripcionPregunta').val().trim(),
    obligatorio: obligatorio,
    columnas: [],
    correcto: true,
  };

  let validacionCorrecta = true;

  // Paso 1: Construimos columnas con orden
  $('#encabezados th').each(function (index) {
    const tipo = $(this).data('tipo');
    const titulo = $(this).text().trim();
    const opciones = $(this).data('opciones') || [];

    let columna = {
      tipo,
      titulo,
      orden: index,
    };

    if (tipo === 'opcion') {
      columna.opciones = opciones;
    }

    if (tipo === 'pregunta') {
      columna.preguntas = []; // Inicializar array de preguntas
    }

    estructura.columnas.push(columna);
  });

  
  $('#cuerpo-tabla tr').each(function (filaIdx) {
    $(this)
      .find('td')
      .each(function (colIdx) {
        const tipoCol = estructura.columnas[colIdx]?.tipo;
        if (tipoCol === 'pregunta') {
          const input = $(this).find('input');
          const val = input.val()?.trim();
          if (val) {
            estructura.columnas[colIdx].preguntas.push({
              texto: val,
              orden_fila: filaIdx,
            });
          } else {
            mensajeAlerta(`La subpregunta en la fila ${filaIdx + 1}, columna ${colIdx + 1} está vacía.`, 'warning');
            estructura.correcto = false;
          }
        }
      });
  });

  

  return estructura;
}

// Boton para eliminar pregunta
$('.btnEliminarPRegunta').on('click', function () {
  let idPregunta = $(this).data('id');
  Swal.fire({
    title: 'NOTA!',
    text: '¿Está seguro de Eliminar la pregunta?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, Estoy seguro',
    cancelButtonText: 'Cancelar',
  }).then(async function (result) {
    if (result.isConfirmed) {
      crud('admin/encuestas', 'DELETE', idPregunta, null, function (error, response) {
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo === 'errores') {
          mensajeAlerta(response.mensaje, 'errores');
          return;
        }
        if (response.tipo != 'exito') {
          mensajeAlerta(response.mensaje, response.tipo);
          return;
        }
        // si todo esta correcto muestra el mensaje de correcto
        mensajeAlerta(response.mensaje, response.tipo);
        setTimeout(() => {
          location.reload();
        }, 1500);
      });
    } else {
      alerta_top('error', 'Se canceló la eliminacion');
    }
  });
});

// BOton EDITAR Pregunta
$('.btnEditarPregunta').on('click', function () {
  const id = $(this).data('id');
  const $card = $(`#pregunta_${id}`);

  $card.addClass('editando');
  $card.find('.vista-normal').addClass('d-none');
  $card.find('.vista-edicion').removeClass('d-none');

  $card.find('.btnCancelarEdicion').on('click', function () {
    $card.removeClass('editando');
    $card.find('.vista-edicion').addClass('d-none');
    $card.find('.vista-normal').removeClass('d-none');
  });
});

// Cancelar edición
$(document).on('click', '.btnCancelarEdicion', function () {
  const $card = $(this).closest('.pregunta-card');
  $card.removeClass('editando');
  $card.find('.vista-edicion').addClass('d-none');
  $card.find('.vista-normal').removeClass('d-none');
});

// agregar opciones
$(document).on('click', '.agregarOpcion', function () {
  const $contenedor = $(this).closest('.pregunta-card').find('.contenedor-opciones-editables');
  const nuevaOpcion = `
      <div class="input-group mb-1">
        <input type="text" name="opciones_editables[]" class="form-control" placeholder="Nueva opción" data-id="" value="">
        <button class="btn btn-outline-danger btn-sm btnEliminarOpcion" type="button">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    `;
  $contenedor.append(nuevaOpcion);
});

// Eliminar opción
$(document).on('click', '.btnEliminarOpcion', function () {
  $(this).closest('.input-group').remove();
});

// Guardar cambios de edición de pregunta
// Este evento se dispara cuando se hace clic en el botón "Guardar Edición" dentro de una tarjeta de pregunta editada
$(document).on('click', '.btnGuardarEdicion', function () {
  let datos = {};
  const $card = $(this).closest('.pregunta-card');

  const preguntaId = $card.attr('id').replace('pregunta_', '');

  const titulo = $card.find('input[name="tituloPreguntaEdit"]').val().trim();
  const obligatorio = $card.find('#obligatorioPreguntaEdit').is(':checked') ? 'si' : 'no';
  const masDeunaOpcion = $card.find('#masDeunaOpcion').is(':checked') ? 'si' : 'no';
  const tipo = $card.data('tipo'); // asumimos que tiene data-tipo="texto"/"numero"/"opcion"

  datos = {
    id: preguntaId,
    titulo: titulo,
    obligatorio: obligatorio,
    tipo: tipo,
    masDeunaOpcion: masDeunaOpcion,
  };

  if (tipo === 'opcional') {
    const opciones = [];

    $card.find('input[name="opciones_editables[]"]').each(function () {
      const texto = $(this).val().trim();
      const id = $(this).data('id'); // puede ser undefined si es nueva

      if (texto !== '') {
        opciones.push({
          id: id, // será undefined para nuevas
          texto: texto,
        });
      }
    });

    datos.opciones = opciones;
  }

  // PREGUNTA TIPO TABLA
  if (tipo === 'tabla') {
    const columnas = [];

    $card.find('.columna-tabla-edit').each(function (index) {
      const $columna = $(this);
      const tipoCol = $columna.data('tipo'); // texto, numero, opcion, pregunta
      const idColum = $columna.data('id'); // texto, numero, opcion, pregunta

      const columna = {
        id: idColum, // puede ser undefined si es nueva
        tipo: tipoCol,
        titulo: $columna.find('input[name="titulo_columna"]').val().trim(),
        orden: index,
      };

      if (tipoCol === 'opcion') {
        const opciones = [];
        $columna.find('input[name="opcion_columna[]"]').each(function () {
          const val = $(this).val().trim();
          const id = $(this).data('id'); // puede ser undefined si es nueva

          if (val !== '')
            opciones.push({
              id: id, // será undefined para nuevas
              texto: val,
            });
        });
        columna.opciones = opciones;
      }

      if (tipoCol === 'pregunta') {
        const preguntas = [];
        $columna.find('input[name="fila_columna[]"]').each(function (filaIndex) {
          const texto = $(this).val().trim();
          const id = $(this).data('id'); // puede ser undefined si es nueva
          if (texto !== '') {
            preguntas.push({ id: id, texto: texto, orden_fila: filaIndex });
          }
        });
        columna.preguntas = preguntas;
      }

      columnas.push(columna);
    });

    datos.columnas = columnas;
  }

  crud('admin/guardarPreguntaEditada', 'PUT', preguntaId, datos, function (error, response) {
    if (response.tipo != 'exito') {
      mensajeAlerta(response.mensaje, response.tipo);
      return;
    }

    $card.removeClass('editando');
    $card.find('.vista-edicion').addClass('d-none');
    $card.find('.vista-normal').removeClass('d-none');

    mensajeAlerta(response.mensaje, response.tipo);
    setTimeout(() => {
      location.reload();
    }, 1500);
  });
});

// agregar opciones en la tabla
$(document).on('click', '.agregarOpcionTabla', function () {
  const index = $(this).data('columna');
  const $contenedor = $(this).siblings(`.contenedor-opciones-tabla[data-columna="${index}"]`);

  // Contar cuántas opciones hay ya
  const nuevoIndex = $contenedor.find('input').length;

  const nuevoCampo = `
    <div class="input-group mb-1">
        <input type="text" class="form-control"
            name="opcion_columna[]" data-id="" value="">
        <button type="button" class="btn btn-outline-danger btn-sm btnEliminarOpcionTabla">
            <i class="fas fa-trash"></i>
        </button>
    </div>
  `;

  $contenedor.append(nuevoCampo);
});

// eliminar opciones de la tabla
$(document).on('click', '.btnEliminarOpcionTabla', function () {
  $(this).closest('.input-group').remove();
});

// Eliminar columna en edicion
$(document).on('click', '.btnEliminarColumnaEdit', function () {
  $(this).closest('.columna-tabla-edit').remove();
});

// Agregar nueva columna en edición
// Este evento se dispara cuando se hace clic en el botón "Agregar columna Edit"
$(document).on('click', '.btnAgregarColumnaEdit', function () {
  const nuevaColumna = `
  <div class="mb-3 p-3 border rounded border-info  border-3 bg-light columna-tabla-edit" data-tipo="texto">
      <div class="text-end mb-2">
        <button type="button" class="btn btn-sm btn-outline-danger btnEliminarColumna">
            <i class="fas fa-trash-alt"></i> Eliminar columna
        </button>
      </div>
      <div class="m-auto text-center mb-2">
        <label class="text-center text-uppercase">Nueva columna:</label>
      </div>
      <input type="text" class="form-control mb-2" name="titulo_columna" placeholder="Título de columna">

      <label class="mb-2">Tipo de columna:</label>
      <select class="form-select tipoColumnaSelect mb-3">
        <option value="texto">Texto</option>
        <option value="numero">Número</option>
        <option value="porcentaje">Porcentaje</option>
        <option value="opcion">Opción</option>
        <option value="pregunta">Pregunta</option>
      </select>

      <div class="contenido-columna-dinamico"></div>
  </div>
`;

  $(this).before(nuevaColumna);
});


// Cambiar contenido dinámico según tipo seleccionado
$(document).on('change', '.tipoColumnaSelect', function () {
  const tipo = $(this).val();
  const $contenedor = $(this).closest('.columna-tabla-edit');
  const $contenido = $contenedor.find('.contenido-columna-dinamico');

  $contenedor.attr('data-tipo', tipo); // actualiza el atributo data-tipo
  $contenido.empty(); // limpiamos lo anterior

  if (tipo === 'texto' || tipo === 'numero' || tipo === 'porcentaje') {
    // No se necesita contenido dinámico
    return;
  }

  if (tipo === 'opcion') {
    const html = `
            <label class="mb-2">Opciones:</label>
            <div class="contenedor-opciones-tabla mb-2">
                <div class="input-group mb-1">
                    <input type="text" class="form-control" name="opcion_columna[]" placeholder="Opción" data-id="">
                    <button type="button" class="btn btn-outline-danger btn-sm btnEliminarOpcionTabla">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-success btn-sm agregarOpcionTablaEdit">
                Agregar opción
            </button>
        `;
    $contenido.html(html);
  }

  if (tipo === 'pregunta') {
    const html = `
            <label class="mb-2">Filas:</label>
            <div class="contenedor-filas-tabla mb-2">
               <div class="input-group mb-1 fila-item">
            <input type="text" class="form-control" name="fila_columna[]" placeholder="Fila nueva" data-id="">
            <button type="button" class="btn btn-outline-danger btn-sm btnEliminarFila">
                <i class="fas fa-trash"></i>
            </button>
        </div>
                
            </div>
            <button type="button" class="btn btn-outline-success btn-sm agregarFilaTabla">
                Agregar fila
            </button>
        `;
    $contenido.html(html);
  }
});

// Agregar nueva opción en edición de tabla
// Este evento se dispara cuando se hace clic en el botón "Agregar opción" dentro de una columna de tabla en edición
$(document).on('click', '.agregarOpcionTablaEdit', function () {
  const $contenedor = $(this).siblings('.contenedor-opciones-tabla');
  const nueva = `
        <div class="input-group mb-1">
            <input type="text" class="form-control" name="opcion_columna[]" placeholder="Opción" data-id="">
            <button type="button" class="btn btn-outline-danger btn-sm btnEliminarOpcionTabla">
                <i class="fas fa-trash"></i>
            </button>
        </div>`;
  $contenedor.append(nueva);
});


// Eliminar una opción de la tabla (visual, solo en frontend)
$(document).on('click', '.btnEliminarOpcionTabla', function () {
  $(this).closest('.input-group').remove();
});

// Agregar nueva fila
$(document).on('click', '.agregarFilaTabla', function () {
  const $contenedor = $(this).siblings('.contenedor-filas-tabla');

  const nuevaFila = `
        <div class="input-group mb-1 fila-item">
            <input type="text" class="form-control" name="fila_columna[]" placeholder="Fila nueva" data-id="">
            <button type="button" class="btn btn-outline-danger btn-sm btnEliminarFila">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

  $contenedor.append(nuevaFila);
});

// Eliminar una fila (visual, solo en frontend)
$(document).on('click', '.btnEliminarFila', function () {
  $(this).closest('.fila-item').remove();
});

let preguntaMoverId = null; // Se define fuera

// Cuando se abre el modal (establece el id)
$(document).on('click', '.btn-mover-manual', function () {
  preguntaMoverId = $(this).data('id');
  $('#mover_id').val(preguntaMoverId);
  $('#modalMoverPregunta').modal('show');
});

// Confirmar movimiento
$('#btnConfirmarMover').on('click', function () {
  const destinoId = $('#destino_id').val();
  const accion = $('input[name="accion"]:checked').val();
  const encuesta_id = $('#idEncuesta').val();

  const datos = {
    preguntaMoverId: preguntaMoverId,
    destinoId: destinoId,
    accion: accion
  };

  crud('admin/moverPregunta', 'PUT', encuesta_id, datos, function (error, response) {
    if (response.tipo !== 'exito') {
      mensajeAlerta(response.mensaje, response.tipo);
      return;
    }

    mensajeAlerta(response.mensaje, response.tipo);
    setTimeout(() => location.reload(), 1500);
  });
});


// // GUARDAR CAMBIOS DE PREGUNTA
// $('#formEditarPregunta').on('submit', function (e) {
//   e.preventDefault();
//   let preguntas = $('#formEditarPregunta').serialize();
//   crud('admin/editarPregunta', 'POST', null, preguntas, function (error, response) {
//     // Verificamos que no haya un error o que todos los campos sean llenados
//     if (response.tipo === 'errores') {
//       mensajeAlerta(response.mensaje, 'errores');
//       return;
//     }
//     if (response.tipo != 'exito') {
//       mensajeAlerta(response.mensaje, response.tipo);
//       return;
//     }
//     // si todo esta correcto muestra el mensaje de correcto
//     mensajeAlerta(response.mensaje, response.tipo);
//     setTimeout(() => {
//       location.reload();
//     }, 1500);
//   });
// });
