// Define las funciones de notificación utilizando SweetAlert
const notificaciones = {
  'exito': (mensaje = "", titulo = "") => {
    Swal.fire({
      position: "top-end",
      icon: 'success',
      title: titulo,
      text: mensaje,
      showConfirmButton: false,
      timer: 1800,
    });
  },
  'error': (mensaje = "", titulo = "") => {
    Swal.fire({
      position: "top-end",
      icon: 'error',
      title: titulo,
      text: mensaje,
      showConfirmButton: false,
      timer: 1800,
    });
  },
  'warning': (mensaje = "", titulo = "") => {
    Swal.fire({
      position: "top-end",
      icon: 'warning',
      title: titulo,
      text: mensaje,
      showConfirmButton: false,
      timer: 1800,
    });
  },

  'error_validacion': (mensaje = "", titulo = "") => {

    Command: toastr["error"](mensaje);
  },
  'errores': (obj) => {

 
    for (let key in obj) {
      document.getElementById('_' + key).innerHTML = `<p class="text-danger">${obj[key]}</p>`;
    }
  }
  // Puedes agregar más tipos según sea necesario
};


export function mensajeAlerta(mensaje = "", titulo = "") {

    if (notificaciones.hasOwnProperty(titulo)) {
      notificaciones[titulo](mensaje, titulo);
    }
  


  

}


