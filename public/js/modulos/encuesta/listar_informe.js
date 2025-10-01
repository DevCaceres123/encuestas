import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

let permisosGlobal;
let tabla_encuesta;

$(document).ready(function () {
    let table_preguntas = $("#tabla_preguntas").DataTable({
        processing: true,
        responsive: true,
        pageLength: 5,

    });

    let table_preguntas_tabla = $("#tabla_preguntas_tabla").DataTable({
        processing: true,
        responsive: true,
        pageLength: 5,

    });
});


$(document).on("change", ".form-check-input", function () {
    let id = $(this).attr("name").match(/\d+/)[0]; // extraer el ID del name
    let estado = $(this).is(":checked") ? $(this).val() : "inactivo";

    let datos={
        'id':id,
        'estado':estado,
    }

    crud("admin/cambiarEstadoInforme", "PUT", id, datos, function (error, response) {
        
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        mensajeAlerta(response.mensaje, response.tipo);

         setTimeout(() => {
            location.reload();
        }, 1000);

    });
});
