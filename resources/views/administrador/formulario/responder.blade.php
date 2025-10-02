@extends('principal')
@section('titulo', 'Responder Formulario')
@section('contenido')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-danger text-white text-center p-2">
                        <h4 class="mb-0 fw-bold text-uppercase fs-5">{{ $formulario->titulo }}</h4>
                        <p class="mb-0 mt-2 fs-5 text-capitalize">
                            <i class="fas fa-user-circle me-1"></i>
                            Afiliado: <strong>{{ $afiliado->nombres }}</strong> (CI: {{ $afiliado->ci }})
                        </p>
                    </div>
                    <div class="card-body p-4">

                        <div class="d-flex flex-column">
                            <p class="text-center text-muted mb-3 fs-6" id="indicadorPregunta"></p>
                            <div id="navegacionRapida" class="btn-group flex-wrap shadow-sm" role="group" aria-label="Navegación Rápida"></div>
                        </div>

                        <div id="contenedorPreguntas" class="my-4"></div>

                        <div class="d-flex justify-content-center mt-5">
                            <button id="btnAnterior" class="btn btn-outline-secondary btn-lg me-3 px-4" disabled>
                                <i class="fas fa-arrow-left me-2"></i> Anterior
                            </button>
                            <button id="btnSiguiente" class="btn btn-primary btn-lg px-4">
                                Siguiente <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <button id="btnFinalizar" class="btn btn-success btn-lg px-4 d-none">
                                <i class="fas fa-check me-2"></i> Finalizar
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const preguntas = @json($preguntas);
            let indiceActual = 0;
            const respuestas = {};

            const contenedor = document.getElementById('contenedorPreguntas');
            const btnAnterior = document.getElementById('btnAnterior');
            const btnSiguiente = document.getElementById('btnSiguiente');
            const btnFinalizar = document.getElementById('btnFinalizar');
            const indicadorPregunta = document.getElementById('indicadorPregunta');
            const navegacionRapida = document.getElementById('navegacionRapida');

            function actualizarIndicador() {
                indicadorPregunta.textContent = `Pregunta ${indiceActual + 1} de ${preguntas.length}`;                
            }

            function renderNavegacionRapida() {
                navegacionRapida.innerHTML = '';
                preguntas.forEach((_, index) => {
                    const btn = document.createElement('button');
                    btn.className = `btn btn-sm m-1 ${index === indiceActual ? 'btn-danger' : 'btn-outline-secondary'}`;
                    btn.textContent = index + 1;
                    btn.addEventListener('click', () => {
                        guardarRespuestaActual();
                        indiceActual = index;
                        renderPregunta(indiceActual);
                        actualizarBotones();
                        renderNavegacionRapida(); // Se redibuja para actualizar el botón activo
                    });
                    navegacionRapida.appendChild(btn);
                });
            }

            function renderPregunta(indice) {
                const pregunta = preguntas[indice];
                contenedor.innerHTML = '';

                const card = document.createElement('div');
                card.className = 'card shadow-sm border-0 rounded-3';

                const cardBody = document.createElement('div');
                cardBody.className = 'card-body p-4';

                const titulo = document.createElement('h5');
                titulo.className = 'fw-bold mb-2 text-uppercase';
                titulo.textContent = (indiceActual+1)+'. '+pregunta.titulo;
                cardBody.appendChild(titulo);

                const detalles = document.createElement('p');
                detalles.innerHTML = `
            <small class="text-muted">
                ${pregunta.obligatorio === 'si' ? '<span class="badge bg-danger me-1 py-1 fs-7">Obligatorio</span>' : '<span class="badge bg-secondary me-1  py-1 fs-7">Opcional</span>'}
                ${pregunta.varias_respuestas === 'si' ? '<span class="badge bg-info  py-1 fs-7">Múltiples respuestas</span>' : ''}
            </small>`;
                cardBody.appendChild(detalles);

                if (pregunta.tipo === 'opcional') {
                    pregunta.opciones.forEach(op => {
                        const div = document.createElement('div');
                        div.className = 'form-check mb-2 text-capitalize';

                        const input = document.createElement('input');
                        input.className = 'form-check-input';
                        input.type = pregunta.varias_respuestas === 'si' ? 'checkbox' : 'radio';
                        input.name = `pregunta_${pregunta.id}`;
                        input.value = op.id;

                        if (respuestas[pregunta.id]) {
                            if (pregunta.varias_respuestas === 'si' && respuestas[pregunta.id].includes(op.id)) {
                                input.checked = true;
                            } else if (pregunta.varias_respuestas === 'no' && respuestas[pregunta.id] == op.id) {
                                input.checked = true;
                            }
                        }

                        const label = document.createElement('label');
                        label.className = 'form-check-label';
                        label.textContent = op.opcion;

                        div.appendChild(input);
                        div.appendChild(label);
                        cardBody.appendChild(div);
                    });
                } else if (pregunta.tipo === 'tabla') {
                    const tableWrapper = document.createElement('div');
                    tableWrapper.className = 'table-responsive';
                    
                    const table = document.createElement('table');
                    table.className = 'table table-hover table-striped align-middle';

                    const thead = document.createElement('thead');
                    const headerRow = document.createElement('tr');
                    headerRow.className = 'bg-light text-capitalize';
                    pregunta.columnas.forEach(col => {
                        const th = document.createElement('th');
                        th.textContent = col.titulo;
                        headerRow.appendChild(th);
                    });
                    thead.appendChild(headerRow);
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');

                    const colPreguntas = pregunta.columnas.find(c => c.tipo === 'pregunta');
                    if (colPreguntas) {
                        colPreguntas.preguntas.forEach(fila => {
                            const tr = document.createElement('tr');

                            pregunta.columnas.forEach(col => {
                                const td = document.createElement('td');
                                td.className='text-capitalize'
                                if (col.tipo === 'pregunta') {
                                    td.textContent = fila.texto;
                                } else {
                                    td.appendChild(crearInputSegunColumna(col, pregunta, fila.id));
                                }
                                tr.appendChild(td);
                            });
                            tbody.appendChild(tr);
                        });
                    } else {
                        const tr = document.createElement('tr');
                        pregunta.columnas.forEach(col => {
                            const td = document.createElement('td');
                            td.appendChild(crearInputSegunColumna(col, pregunta, null));
                            tr.appendChild(td);
                        });
                        tbody.appendChild(tr);
                    }

                    table.appendChild(tbody);
                    tableWrapper.appendChild(table);
                    cardBody.appendChild(tableWrapper);
                } else {
                    const input = document.createElement('input');
                    input.className = 'form-control form-control-lg';
                    input.type = pregunta.tipo === 'numerico' ? 'number' : 'text';
                    input.placeholder = pregunta.tipo === 'numerico' ? 'Ingrese solo números' :
                        'Ingrese solo texto';
                    input.name = `pregunta_${pregunta.id}`;
                    if (respuestas[pregunta.id]) {
                        input.value = respuestas[pregunta.id];
                    }
                    cardBody.appendChild(input);
                }

                card.appendChild(cardBody);
                contenedor.appendChild(card);
                actualizarIndicador();
            }

            function guardarRespuestaActual() {
                const pregunta = preguntas[indiceActual];
                if (pregunta.tipo === 'opcional') {
                    if (pregunta.varias_respuestas === 'si') {
                        const seleccionadas = Array.from(document.querySelectorAll(
                            `input[name="pregunta_${pregunta.id}"]:checked`)).map(el => parseInt(el.value));
                        respuestas[pregunta.id] = seleccionadas;
                    } else {
                        const seleccionada = document.querySelector(`input[name="pregunta_${pregunta.id}"]:checked`);
                        respuestas[pregunta.id] = seleccionada ? parseInt(seleccionada.value) : null;
                    }
                } else if (pregunta.tipo === 'numerico' || pregunta.tipo === 'texto') {
                    const valor = document.querySelector(`input[name="pregunta_${pregunta.id}"]`).value;
                    respuestas[pregunta.id] = valor;
                }
            }

            function actualizarBotones() {
                btnAnterior.disabled = indiceActual === 0;
                if (indiceActual === preguntas.length - 1) {
                    btnSiguiente.classList.add('d-none');
                    btnFinalizar.classList.remove('d-none');
                } else {
                    btnSiguiente.classList.remove('d-none');
                    btnFinalizar.classList.add('d-none');
                }
            }

            btnAnterior.addEventListener('click', function() {
                guardarRespuestaActual();
                if (indiceActual > 0) {
                    indiceActual--;
                    renderPregunta(indiceActual);
                    actualizarBotones();
                    renderNavegacionRapida();
                }
            });

            btnSiguiente.addEventListener('click', function() {
                guardarRespuestaActual();
                if (indiceActual < preguntas.length - 1) {
                    indiceActual++;
                    renderPregunta(indiceActual);
                    actualizarBotones();
                    renderNavegacionRapida();
                }
            });

            btnFinalizar.addEventListener('click', function() {
                guardarRespuestaActual();
                console.log('Respuestas:', respuestas);
                // Aquí puedes enviar las respuestas usando fetch/axios a tu backend
            });

            // Llamadas iniciales
            renderNavegacionRapida();
            renderPregunta(indiceActual);
            actualizarBotones();
        });

        function crearInputSegunColumna(col, pregunta, filaId = null) {
            const name = filaId ? `respuesta[${pregunta.id}][${col.id}][${filaId}]` : `respuesta[${pregunta.id}][${col.id}]`;
            if (col.tipo === 'numero') {
                const input = document.createElement('input');
                input.className = 'form-control form-control-md';
                input.type = 'number';
                input.placeholder = 'Solo numeros';
                input.name = name;
                return input;
            }

            if (col.tipo === 'texto') {
                const input = document.createElement('input');
                input.className = 'form-control form-control form-control-md';
                input.type = 'text';
                input.placeholder = 'Solo texto';
                input.name = name;
                return input;
            }

            if (col.tipo === 'opcion' && col.opciones) {
                const wrapper = document.createElement('div');
                col.opciones.forEach(opt => {
                    const div = document.createElement('div');
                    div.className = 'form-check text-capitalize';

                    const input = document.createElement('input');
                    input.className = 'form-check-input';
                    input.type = 'radio'; // En las tablas, generalmente se usa un solo radio
                    input.name = name;
                    input.value = opt.id;

                    const label = document.createElement('label');
                    label.className = 'form-check-label';
                    label.textContent = opt.opcion;

                    div.appendChild(input);
                    div.appendChild(label);
                    wrapper.appendChild(div);
                });
                return wrapper;
            }
            
            if (col.tipo === 'porcentaje') {
                const input = document.createElement('input');
                input.className = 'form-control';
                input.type = 'number';
                input.min = 0;
                input.max = 100;
                input.placeholder = '%';
                input.name = name;
                return input;
            }

            return document.createTextNode('');
        }
    </script>
@endsection