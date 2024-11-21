@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')


  <div class="container mt-5">
    <div class="row">
      <!-- Sección de Creación -->
      <div class="col-md-6">
        <h2 class="mb-4">Crear Encuesta</h2>
        <form id="createSurveyForm">
          <!-- Título -->
          <div class="mb-3">
            <label for="surveyTitle" class="form-label">Título de la Encuesta</label>
            <input type="text" class="form-control" id="surveyTitle" placeholder="Introduce el título">
          </div>

          <!-- Descripción -->
          <div class="mb-3">
            <label for="surveyDescription" class="form-label">Descripción</label>
            <textarea class="form-control" id="surveyDescription" rows="3" placeholder="Describe tu encuesta"></textarea>
          </div>

          <!-- Contenedor de Preguntas -->
          <div id="questionsContainer">
            <div class="mb-3 question-item">
              <label class="form-label">Pregunta 1</label>
              <input type="text" class="form-control mb-2" placeholder="Introduce tu pregunta">
              <label class="form-label">Opciones</label>
              <div class="d-flex align-items-center mb-2">
                <input type="text" class="form-control me-2" placeholder="Opción 1">
                <button type="button" class="btn btn-danger btn-sm remove-option">Eliminar</button>
              </div>
              <button type="button" class="btn btn-secondary btn-sm add-option">Añadir Opción</button>
            </div>
          </div>

          <button type="button" id="addQuestion" class="btn btn-primary mt-3">Añadir Pregunta</button>
          <button type="button" id="saveSurvey" class="btn btn-success mt-3">Guardar Encuesta</button>
        </form>
      </div>

      <!-- Sección de Visualización -->
      <div class="col-md-6">
        <h2 class="mb-4">Vista Previa</h2>
        <div id="surveyPreview" class="border p-3 rounded bg-light">
          <p class="text-muted">La encuesta aparecerá aquí una vez guardada.</p>
        </div>
      </div>
    </div>
  </div>

<!-- Agrega tu archivo de JavaScript local -->
  <script>
    // Lógica para crear la encuesta y visualizarla
    document.addEventListener('DOMContentLoaded', () => {
      const questionsContainer = document.getElementById('questionsContainer');
      const addQuestionButton = document.getElementById('addQuestion');
      const saveSurveyButton = document.getElementById('saveSurvey');
      const surveyPreview = document.getElementById('surveyPreview');

      // Añadir nueva pregunta
      addQuestionButton.addEventListener('click', () => {
        const questionCount = document.querySelectorAll('.question-item').length + 1;

        const newQuestion = document.createElement('div');
        newQuestion.classList.add('mb-3', 'question-item');
        newQuestion.innerHTML = `
          <label class="form-label">Pregunta ${questionCount}</label>
          <input type="text" class="form-control mb-2" placeholder="Introduce tu pregunta">
          <label class="form-label">Opciones</label>
          <div class="d-flex align-items-center mb-2">
            <input type="text" class="form-control me-2" placeholder="Nueva Opción">
            <button type="button" class="btn btn-danger btn-sm remove-option">Eliminar</button>
          </div>
          <button type="button" class="btn btn-secondary btn-sm add-option">Añadir Opción</button>
        `;
        questionsContainer.appendChild(newQuestion);
      });

      // Lógica para manejar las opciones de cada pregunta
      questionsContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains('add-option')) {
          const optionInput = document.createElement('div');
          optionInput.classList.add('d-flex', 'align-items-center', 'mb-2');
          optionInput.innerHTML = `
            <input type="text" class="form-control me-2" placeholder="Nueva Opción">
            <button type="button" class="btn btn-danger btn-sm remove-option">Eliminar</button>
          `;
          event.target.parentElement.insertBefore(optionInput, event.target);
        }

        if (event.target.classList.contains('remove-option')) {
          event.target.parentElement.remove();
        }
      });

      // Guardar encuesta y mostrar vista previa
      saveSurveyButton.addEventListener('click', () => {
        const title = document.getElementById('surveyTitle').value;
        const description = document.getElementById('surveyDescription').value;
        const questions = Array.from(document.querySelectorAll('.question-item')).map((question, index) => {
          const questionText = question.querySelector('input').value;
          const options = Array.from(question.querySelectorAll('.form-control')).slice(1).map(input => input.value);
          return { text: questionText, options };
        });

        // Mostrar vista previa
        surveyPreview.innerHTML = `
          <h4>${title}</h4>
          <p>${description}</p>
          <ol>
            ${questions.map(q => `
              <li>
                <strong>${q.text}</strong>
                <ul>
                  ${q.options.map(option => `<li>${option}</li>`).join('')}
                </ul>
              </li>
            `).join('')}
          </ol>
        `;
      });
    });
  </script>







    {{-- CREAR DISTRITO --}}

    {{-- <div class="modal fade" id="modalAfiliado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="">
                        <span class="badge badge-outline-primary rounded">AGREGAR DISTRITO</span>
                    </h4>
                    <span class="ms-3 text-light">Campos obligatorios <strong class="text-danger">(*)</strong></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formnuevo_afiliado">
                        <div class="row">
                            <div class="row">
                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">CARNET DE INDENTIDAD <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="ci" id="ci"
                                            required>
                                        <div id="_ci">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-12 col-md-6 col-lg-3">
                                    <label for="" class="form-label">COMPLEMENTO</label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="complemento"
                                            id="complemento">
                                        <div id="_complemento">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-12 col-md-6 col-lg-3">
                                    <label for="" class="form-label">EXPEDIDO <strong
                                            class="text-danger">(*)</strong></label>

                                    <div class="container-validation" id="group_usuarioReset">
                                        <select class="form-select" aria-label="Default select example" id="expedido_id"
                                            name="expedido_id">
                                            <option disabled selected>Seleccionar</option>
                                            @foreach ($expedidos as $item)
                                                <option value="{{ $item->id }}">{{ $item->departamento }}</option>
                                            @endforeach


                                        </select>

                                        <div id="_expedido_id">

                                        </div>
                                    </div>
                                </div>



                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">NOMBRES <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="nombres" id="nombres"
                                            required>
                                        <div id="_nombres">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">PATERNO <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="paterno" id="paterno"
                                            required>
                                        <div id="_paterno">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">MATERNO <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="materno" id="materno"
                                            required>
                                        <div id="_materno">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                    <label for="" class="form-label">COMUNIDAD <strong
                                            class="text-danger">(*)</strong></label>
                                    <div class="container-validation" id="group_usuarioReset">

                                        <select class="form-select" aria-label="Default select example" id="comunidad_id"
                                            name="comunidad_id">
                                            <option disabled selected>Seleccionar</option>
                                            @foreach ($comunidadades as $item)
                                                <option value="{{ $item->id }}">{{ $item->titulo }}</option>
                                            @endforeach


                                        </select>
                                        <div id="_comunidad_id">

                                        </div>
                                    </div>
                                </div>
                                <label for="" class="form-label">DATOS FAMILIA </label>
                                <div class="col-12 ms-2 row border border-3 rounded m-auto">

                                    <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                        <label for="" class="form-label">INTEGRANTES MUJERES <strong
                                                class="text-danger">(*)</strong></label>
                                        <div class="container-validation" id="group_usuarioReset">
                                            <input type="number" class="form-control rounded" name="mujeres"
                                                id="mujeres" required>
                                            <div id="_mujeres">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-2 col-12 col-md-6 col-lg-6">
                                        <label for="" class="form-label">INTEGRANTES HOMBRES <strong
                                                class="text-danger">(*)</strong></label>
                                        <div class="container-validation" id="group_usuarioReset">
                                            <input type="number" class="form-control rounded" name="hombres"
                                                id="hombres" required>
                                            <div id="_hombres">

                                            </div>
                                        </div>
                                    </div>

                                  
                                </div>


                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                                    class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success rounded btn-sm" id="btnnuevo_afiliado"><i
                                    class="ri-save-3-line me-1 align-middle"></i> guardar</button>
                        </div>


                    </form>
                </div>
            </div>


        </div>

    </div> --}}









@endsection


@section('scripts')

    {{-- <script src="{{ asset('js/modulos/afiliado/afiliado.js') }}" type="module"></script> --}}
@endsection
