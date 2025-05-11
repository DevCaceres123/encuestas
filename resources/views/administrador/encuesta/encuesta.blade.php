@extends('principal')
@section('titulo', 'ENCUESTAS')
@section('contenido')

<h2>Evalúa los siguientes aspectos del curso</h2>

<form method="POST" action="">
    @csrf

    <table id="tabla-pregunta">
        <thead>
            <tr id="encabezados">
                <th>Aspecto</th>
                <th data-tipo="numero" data-sumar="true">Calificación</th>
                <th data-tipo="texto">Comentario</th>
                <th data-tipo="opcion">Nivel de acuerdo</th>
                <th data-tipo="porcentaje" data-sumar="true">Avance (%)</th>
            </tr>
        </thead>
        <tbody id="cuerpo-tabla">
            <tr>
                <td><input type="text" name="filas[]" value="Claridad del contenido"></td>
                <td><input type="number" name="respuestas[0][col_1]" class="suma-col-1"></td>
                <td><textarea name="respuestas[0][col_2]"></textarea></td>
                <td>
                    <select name="respuestas[0][col_3]">
                        <option value="">--</option>
                        <option value="1">Totalmente en desacuerdo</option>
                        <option value="2">En desacuerdo</option>
                        <option value="3">Neutral</option>
                        <option value="4">De acuerdo</option>
                        <option value="5">Totalmente de acuerdo</option>
                    </select>
                </td>
                <td><input type="number" name="respuestas[0][col_4]" class="suma-col-4" min="0" max="100"> %</td>
            </tr>
        </tbody>
        <tfoot>
            <tr id="fila-suma">
                <td>Total</td>
                <td id="suma-col-1">0</td>
                <td></td>
                <td></td>
                <td id="suma-col-4">0%</td>
            </tr>
        </tfoot>
    </table>

    <br>
    <button type="button" onclick="agregarFila()">➕ Agregar fila</button>
    <button type="button" onclick="agregarColumna()">➕ Agregar columna</button>
    <br><br>

    <button type="submit">Enviar respuestas</button>
</form>

<script>
  let filaIndex = 1;
  let columnaIndex = 5;
  let opcionesColumnas = {}; // Almacena las opciones definidas por columna

  function agregarColumna() {
      const tipo = prompt("Tipo de columna (numero/texto/opcion/porcentaje):");
      const nombre = prompt("Nombre de la columna:");
      if (!tipo || !['numero', 'texto', 'opcion', 'porcentaje'].includes(tipo)) return;

      let sumar = false;
      if (tipo === 'numero' || tipo === 'porcentaje') {
          sumar = confirm("¿Quieres que esta columna tenga sumatoria?");
      }

      let opciones = [];
      if (tipo === 'opcion') {
          let inputOpciones = prompt("Escribe las opciones, separadas por coma (,):", "Sí,No");
          if (inputOpciones) {
              opciones = inputOpciones.split(',').map(o => o.trim()).filter(o => o !== '');
          }
          opcionesColumnas[columnaIndex] = opciones;
      }

      $('#encabezados').append(`<th data-tipo="${tipo}" data-sumar="${sumar}">${nombre}</th>`);
      $('#fila-suma').append(`<td id="suma-col-${columnaIndex}">${sumar ? (tipo === 'porcentaje' ? '0%' : '0') : ''}</td>`);

      $('#cuerpo-tabla tr').each(function (filaIdx) {
          let inputHTML = '';
          let clase = sumar ? ` class="suma-col-${columnaIndex}"` : '';
          switch(tipo) {
              case 'numero':
                  inputHTML = `<input type="number" name="respuestas[${filaIdx}][col_${columnaIndex}]"${clase}>`;
                  break;
              case 'porcentaje':
                  inputHTML = `<input type="number" name="respuestas[${filaIdx}][col_${columnaIndex}]"${clase} min="0" max="100"> %`;
                  break;
              case 'texto':
                  inputHTML = `<textarea name="respuestas[${filaIdx}][col_${columnaIndex}]"></textarea>`;
                  break;
              case 'opcion':
                  inputHTML = `<select name="respuestas[${filaIdx}][col_${columnaIndex}]">`;
                  opciones.forEach(op => {
                      inputHTML += `<option value="${op}">${op}</option>`;
                  });
                  inputHTML += `</select>`;
                  break;
          }
          $(this).append(`<td>${inputHTML}</td>`);
      });

      columnaIndex++;
      calcularSuma();
  }

  function calcularSuma() {
      $('[id^=suma-col-]').each(function() {
          const colNum = this.id.replace('suma-col-', '');
          let total = 0;
          $(`.suma-col-${colNum}`).each(function() {
              const val = parseFloat($(this).val());
              if (!isNaN(val)) total += val;
          });
          const th = $(`#encabezados th`).eq(colNum);
          const tipo = th.data('tipo');
          $(this).text(tipo === 'porcentaje' ? total + '%' : total);
      });
  }

  $(document).on('input', 'input[type=number]', calcularSuma);
</script>





@endsection


@section('scripts')

    {{-- <script src="{{ asset('js/modulos/afiliado/afiliado.js') }}" type="module"></script> --}}
@endsection

