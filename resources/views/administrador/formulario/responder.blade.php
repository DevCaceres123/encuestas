@extends('principal')
@section('titulo', 'Responder Formulario')
@section('contenido')

    <div class="container mt-3">
        <div class="card">
            <div class="card-body">

                <h4 class="text-center mb-2">Formulario: {{ $formulario->titulo }}</h4>
                <p class="text-center text-muted mb-0">
                    Afiliado: <strong>{{ $afiliado->nombres }}</strong> (CI: {{ $afiliado->ci }})
                </p>
                <p class="text-center text-muted" id="indicadorPregunta"></p>

                <!-- Navegación rápida -->
                <div class="text-center mb-2">
                    <div id="navegacionRapida" class="btn-group flex-wrap"></div>
                </div>

                <div id="contenedorPreguntas" class="mt-3"></div>

                <div class="text-center mt-3">
                    <button id="btnAnterior" class="btn btn-secondary me-2" disabled>
                        <i class="fas fa-arrow-left"></i> Anterior
                    </button>
                    <button id="btnSiguiente" class="btn btn-primary">
                        Siguiente <i class="fas fa-arrow-right"></i>
                    </button>
                    <button id="btnFinalizar" class="btn btn-success d-none">
                        <i class="fas fa-check"></i> Finalizar
                    </button>
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
                    btn.className = 'btn btn-outline-secondary btn-sm m-1';
                    btn.textContent = index + 1;
                    btn.addEventListener('click', () => {
                        guardarRespuestaActual();
                        indiceActual = index;
                        renderPregunta(indiceActual);
                        actualizarBotones();
                    });
                    navegacionRapida.appendChild(btn);
                });
            }

            function renderPregunta(indice) {
                const pregunta = preguntas[indice];
                contenedor.innerHTML = '';

                const card = document.createElement('div');
                card.className = 'card';

                const cardBody = document.createElement('div');
                cardBody.className = 'card-body';

                const titulo = document.createElement('h5');
                titulo.textContent = pregunta.titulo;
                cardBody.appendChild(titulo);

                const detalles = document.createElement('p');
                detalles.innerHTML = `
            <small class="text-muted">
                ${pregunta.obligatorio === 'si' ? '<span class="badge bg-danger me-1">Obligatorio</span>' : '<span class="badge bg-secondary me-1">Opcional</span>'}
                ${pregunta.varias_respuestas === 'si' ? '<span class="badge bg-info">Permite múltiples respuestas</span>' : ''}
            </small>`;
                cardBody.appendChild(detalles);

                if (pregunta.tipo === 'opcional') {
                    pregunta.opciones.forEach(op => {
                        const div = document.createElement('div');
                        div.className = 'form-check';

                        const input = document.createElement('input');
                        input.className = 'form-check-input';
                        input.type = pregunta.varias_respuestas === 'si' ? 'checkbox' : 'radio';
                        input.name = 'pregunta';
                        input.value = op.id;

                        if (respuestas[pregunta.id]) {
                            if (pregunta.varias_respuestas === 'si' && respuestas[pregunta.id].includes(op
                                    .id)) {
                                input.checked = true;
                            } else if (pregunta.varias_respuestas === 'no' && respuestas[pregunta.id] == op
                                .id) {
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
                    const table = document.createElement('table');
                    table.className = 'table table-bordered';

                    const thead = document.createElement('thead');
                    const headerRow = document.createElement('tr');

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

                                if (col.tipo === 'pregunta') {
                                    td.textContent = fila.texto;
                                } else if (col.tipo === 'numero') {
                                    const input = document.createElement('input');
                                    input.className = 'form-control';
                                    input.type = 'number';
                                    input.placeholder = 'Ingrese solo números';
                                    input.name = `respuesta[${pregunta.id}][${col.id}][${fila.id}]`;
                                    td.appendChild(input);
                                } else if (col.tipo === 'texto') {
                                    const input = document.createElement('input');
                                    input.className = 'form-control';
                                    input.type = 'text';
                                    input.placeholder = 'Ingrese solo texto';
                                    input.name = `respuesta[${pregunta.id}][${col.id}][${fila.id}]`;
                                    td.appendChild(input);
                                } else if (col.tipo === 'opcion' && col.opciones) {
                                    col.opciones.forEach(opt => {
                                        const div = document.createElement('div');
                                        div.className = 'form-check';

                                        const input = document.createElement('input');
                                        input.className = 'form-check-input';
                                        input.type = pregunta.varias_respuestas === 'si' ?
                                            'checkbox' : 'radio';
                                        input.name =
                                            `respuesta[${pregunta.id}][${col.id}][${fila.id}]${pregunta.varias_respuestas === 'si' ? '[]' : ''}`;
                                        input.value = opt.id;

                                        const label = document.createElement('label');
                                        label.className = 'form-check-label';
                                        label.textContent = opt.opcion;

                                        div.appendChild(input);
                                        div.appendChild(label);
                                        td.appendChild(div);
                                    });
                                } else if (col.tipo === 'porcentaje') {
                                    const input = document.createElement('input');
                                    input.className = 'form-control';
                                    input.type = 'number';
                                    input.min = 0;
                                    input.max = 100;
                                    input.placeholder = 'Ingrese un porcentaje (0-100)';
                                    input.name = `respuesta[${pregunta.id}][${col.id}][${fila.id}]`;
                                    td.appendChild(input);
                                }

                                tr.appendChild(td);
                            });
                            tbody.appendChild(tr);
                        });
                    }

                    table.appendChild(tbody);
                    cardBody.appendChild(table);
                } else {
                    const input = document.createElement('input');
                    input.className = 'form-control';
                    input.type = pregunta.tipo === 'numerico' ? 'number' : 'text';
                    input.placeholder = pregunta.tipo === 'numerico' ? 'Ingrese solo números' :
                        'Ingrese solo texto';
                    input.name = 'pregunta';
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
                            'input[name="pregunta"]:checked')).map(el => parseInt(el.value));
                        respuestas[pregunta.id] = seleccionadas;
                    } else {
                        const seleccionada = document.querySelector('input[name="pregunta"]:checked');
                        respuestas[pregunta.id] = seleccionada ? parseInt(seleccionada.value) : null;
                    }
                } else if (pregunta.tipo === 'numerico' || pregunta.tipo === 'texto') {
                    const valor = document.querySelector('input[name="pregunta"]').value;
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
                }
            });

            btnSiguiente.addEventListener('click', function() {
                guardarRespuestaActual();
                if (indiceActual < preguntas.length - 1) {
                    indiceActual++;
                    renderPregunta(indiceActual);
                    actualizarBotones();
                }
            });

            btnFinalizar.addEventListener('click', function() {
                guardarRespuestaActual();
                console.log('Respuestas:', respuestas);
                // Aquí puedes enviar las respuestas usando fetch/axios a tu backend
            });

            renderNavegacionRapida();
            renderPregunta(indiceActual);
            actualizarBotones();
        });
    </script>
@endsection
