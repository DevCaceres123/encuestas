<script src="{{ asset('admin_template/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin_template/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('admin_template/data/stock-prices.js') }}"></script>
<script src="{{ asset('admin_template/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('admin_template/libs/jsvectormap/maps/world.js') }}"></script>
<script src="{{ asset('admin_template/js/app.js') }}"></script>


<!-- Sweet-Alert  -->
<script src="{{ asset('admin_template/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('admin_template/js/pages/sweet-alert.init.js') }}"></script>

<script src="{{ asset('admin_template/libs/jquery/jquery-3.7.1.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/r-3.0.3/sb-1.8.0/sl-2.1.0/datatables.js">
</script>

<script>
    //token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    //MIXIN alerta
    function alerta_top(tipo, mensaje) {
        Swal.mixin({
            toast: !0,
            position: "top-end",
            showConfirmButton: !1,
            timer: 1500,
            timerProgressBar: !0,
            didOpen: e => {
                e.addEventListener("mouseenter", Swal.stopTimer), e.addEventListener("mouseleave", Swal
                    .resumeTimer)
            }
        }).fire({
            icon: tipo,
            title: mensaje,
        })
    }

    //para cerrar la session
    let btn_cerrar_session = document.getElementById("btn-cerrar-session");
    btn_cerrar_session.addEventListener("click", async () => {
        await cerrar_session_cam();
    });

    //PARA CERRAR LA CESSION DESPUES DE HABER CAMBIADO
    async function cerrar_session_cam() {
        let datos = Object.fromEntries(new FormData(document.getElementById('formulario_salir')).entries());
        try {
            let respuesta = await fetch("{{ route('salir') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datos)
            });
            let dato = await respuesta.json();
            alerta_top(dato.tipo, dato.mensaje);
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        } catch (error) {
            console.log('Ocurrio un error: ' + error);
        }
    }

    //para validar el boton
    function validar_boton(valor, msj, boton) {
        let boton_env = boton;
        boton_env.disabled = valor;
        boton_env.innerHTML = '';
        if (valor) {
            boton_env.innerHTML = `<span class="spinner-border spinner-border-sm"></span>&nbsp;${msj}`;
        } else {
            boton_env.innerHTML = msj;
        }
    }

    //para vaciar los errores
    function vaciar_errores(array) {
        let nuevo_array = array;
        // console.log(nuevo_array);
        nuevo_array.forEach(element => {
            document.getElementById(element).innerHTML = '';
        });
    }

    //para mostrar los errores/ iterramos
    function mostrarErrores(obj) {
       
        for (let key in obj) {
            document.getElementById('_' + key).innerHTML = `<p class="text-danger">${obj[key]}</p>`;
        }
    }

    //para vaciar los input, textrarea y select
    function vaciar_formulario(formulario) {
        Array.from(formulario.elements).forEach(elemento => {
            // Verifica si es un campo de formulario (input, textarea, select)
            if (['input', 'textarea', 'select'].includes(elemento.tagName.toLowerCase())) {
                // Limpia el valor del campo
                elemento.value = '';
            }
        });
    }

    //para desabilitar el enter
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[type=text]').forEach(node => node.addEventListener('keypress', e => {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        }))
    });
</script>
