export function mensajeInputs(mensaje, color, campo) {

    $(`#error_${campo} spam b`).val = "";
    $(`#error_${campo} spam b`).html(mensaje).css("color", color);
}