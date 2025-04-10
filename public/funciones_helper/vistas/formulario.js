export function mensajeInputs(mensaje, color, campo) {

    $(`#error_${campo} spam b`).val = "";
    $(`#error_${campo} spam b`).html(mensaje).css("color", color);
}


export function vaciar_errores(nombre_formulario) {

    try {
        // Seleccionar el formulario
        const form = document.getElementById(nombre_formulario);
        if (!form) {
            throw new Error(`Formulario con ID '${nombre_formulario}' no encontrado`);
        }

        // Seleccionar solo los campos de tipo input, textarea y select
        const elements = form.querySelectorAll("input:not([type=hidden]), textarea, select");
        
        const fieldNames = Array.from(elements).map(element => element.name);
        
        fieldNames.forEach(name => {
            const errorElement = document.getElementById("_" + name);
            
            if (errorElement || errorElement.id =="_") {
                errorElement.innerHTML = '';
            } else {
                console.warn(`Elemento de error con ID '_${name}' no encontrado`);
            }
        });
    } catch (error) {
        console.error("Error en el procesamiento del formulario:", error);
    }

}


//para vaciar los input, textrarea y select
export function vaciar_formulario(formulario) {

    document.getElementById(formulario).reset();
}