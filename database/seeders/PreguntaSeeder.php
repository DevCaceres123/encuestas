<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pregunta;
use App\Models\Encuesta;
use App\Models\Pregunta_opciones;
use App\Models\Pregunta_columna;

class PreguntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encuesta = new Encuesta();
        $encuesta->titulo = 'Encuesta de agricultura y agronomia';
        $encuesta->descripcion = 'Preguntas que se refieron al area de agricultura y agronomia';
        $encuesta->estado = 'activo';
        $encuesta->user_id = 1;
        $encuesta->save();

        $pregunta1 = new Pregunta();
        $pregunta1->titulo = 'Tierra cultivada (Has)';
        $pregunta1->tipo = 'numerico';
        $pregunta1->obligatorio = 'no';
        $pregunta1->orden = 1;
        $pregunta1->encuesta_id = $encuesta->id;
        $pregunta1->save();

        $pregunta2 = new Pregunta();
        $pregunta2->titulo = 'Tierra cultivada en descanso (Has)';
        $pregunta2->tipo = 'numerico';
        $pregunta2->obligatorio = 'no';
        $pregunta2->orden = 2;
        $pregunta2->encuesta_id = $encuesta->id;
        $pregunta2->save();

        $pregunta3 = new Pregunta();
        $pregunta3->titulo = 'Tierra cultivada bajo riego (Has)';
        $pregunta3->tipo = 'numerico';
        $pregunta3->obligatorio = 'no';
        $pregunta3->orden = 3;
        $pregunta3->encuesta_id = $encuesta->id;
        $pregunta3->save();

        $pregunta4 = new Pregunta();
        $pregunta4->titulo = 'Tierra en pastoreo (Has)';
        $pregunta4->tipo = 'numerico';
        $pregunta4->obligatorio = 'no';
        $pregunta4->orden = 4;
        $pregunta4->encuesta_id = $encuesta->id;
        $pregunta4->save();

        $pregunta5 = new Pregunta();
        $pregunta5->titulo = 'Tierra sin uso (Has)';
        $pregunta5->tipo = 'numerico';
        $pregunta5->obligatorio = 'no';
        $pregunta5->orden = 5;
        $pregunta5->encuesta_id = $encuesta->id;
        $pregunta5->save();

        //pregunta con ocpiones multiples
        $pregunta6 = Pregunta::create([
            'titulo' => 'Cual es la actividad principal que desarrolla?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 6,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta6->pregunta_opciones()->createMany([
           ['opcion' => 'ganaderia'],
           ['opcion' => 'agricola'],
           ['opcion' => 'comercio'],
           ['opcion' => 'transporte'],
           ['opcion' => 'artesanal'],
           ['opcion' => 'mineria'],
        ]);


        $pregunta7 = Pregunta::create([
           'titulo' => 'Que cultivos produce?',
           'tipo' => 'opcional',
           'obligatorio' => 'si',
           'varias_respuestas' => 'si',
           'orden' => 7,
           'encuesta_id' => $encuesta->id,
        ]);

        $pregunta7->pregunta_opciones()->createMany([
           ['opcion' => 'papa'],
           ['opcion' => 'avena'],
           ['opcion' => 'alfalfa'],
        ]);



        $pregunta8 = new Pregunta();
        $pregunta8->titulo = 'Ziembra Manual (Has)';
        $pregunta8->tipo = 'numerico';
        $pregunta8->obligatorio = 'no';
        $pregunta8->orden = 8;
        $pregunta8->encuesta_id = $encuesta->id;
        $pregunta8->save();


        $pregunta9 = new Pregunta();
        $pregunta9->titulo = 'Ziembra Mecanica (Has)';
        $pregunta9->tipo = 'numerico';
        $pregunta9->obligatorio = 'no';
        $pregunta9->orden = 9;
        $pregunta9->encuesta_id = $encuesta->id;
        $pregunta9->save();


        $pregunta10 = new Pregunta();
        $pregunta10->titulo = 'Cosecha Manual (Has)';
        $pregunta10->tipo = 'numerico';
        $pregunta10->obligatorio = 'no';
        $pregunta10->orden = 10;
        $pregunta10->encuesta_id = $encuesta->id;
        $pregunta10->save();



        $pregunta11 = new Pregunta();
        $pregunta11->titulo = 'Cosecha Mecanica (Has)';
        $pregunta11->tipo = 'numerico';
        $pregunta11->obligatorio = 'no';
        $pregunta11->orden = 11;
        $pregunta11->encuesta_id = $encuesta->id;
        $pregunta11->save();



        //pregunta con ocpiones multiples
        $pregunta12 = Pregunta::create([
            'titulo' => 'Tipo de abono utilizado',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 12,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta12->pregunta_opciones()->createMany([
           ['opcion' => 'estiercol'],
           ['opcion' => 'compost'],
           ['opcion' => 'humus de lombriz'],
           ['opcion' => 'quimico'],
        ]);


        //pregunta con ocpiones multiples
        $pregunta13 = Pregunta::create([
            'titulo' => 'Tipo de plagas que se presentan',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 13,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta13->pregunta_opciones()->createMany([
         ['opcion' => 'gorgojo'],
         ['opcion' => 'Pulgón'],
        ]);


        //pregunta con ocpiones multiples
        $pregunta14 = Pregunta::create([
            'titulo' => 'Tipo de enfermedades que se presentan',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 14,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta14->pregunta_opciones()->createMany([
         ['opcion' => 'Kasawi'],
         ['opcion' => 'Antracnosis'],
        ]);


        //pregunta con ocpiones multiples
        $pregunta15 = Pregunta::create([
            'titulo' => 'Se presento sequia?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'orden' => 15,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta15->pregunta_opciones()->createMany([
         ['opcion' => 'si'],
         ['opcion' => 'no'],
        ]);


        //pregunta con ocpiones multiples
        $pregunta16 = Pregunta::create([
            'titulo' => 'se presento helada?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'orden' => 16,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta16->pregunta_opciones()->createMany([
         ['opcion' => 'si'],
         ['opcion' => 'no'],
        ]);

        //pregunta con ocpiones multiples
        $pregunta17 = Pregunta::create([
            'titulo' => 'que problema tiene con sus suelos?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 17,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta17->pregunta_opciones()->createMany([
         ['opcion' => 'sobre pastoreo'],
         ['opcion' => 'humedad'],
         ['opcion' => 'deficiente'],
        ]);


        //pregunta con ocpiones multiples
        $pregunta18 = Pregunta::create([
            'titulo' => 'a que cultivos aplica riego?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 18,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta18->pregunta_opciones()->createMany([
         ['opcion' => 'papa'],
         ['opcion' => 'alfalfa'],
         ['opcion' => 'avena'],
        ]);


        //pregunta de tipo tabla
        $pregunta19 = Pregunta::create([
            'titulo' => 'cantidad cultivada? (has)',
            'tipo' => 'tabla',
            'obligatorio' => 'si',
            'orden' => 19,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta19->columnas()->createMany([
         ['pregunta' => 'papa','tipo' => 'numero','orden' => 0],
         ['pregunta' => 'avena','tipo' => 'numero','orden' => 1],
         ['pregunta' => 'alfalfa','tipo' => 'numero','orden' => 2],
        ]);


        //pregunta de tipo tabla
        $pregunta20 = Pregunta::create([
            'titulo' => 'cantidad cosechada? (has)',
            'tipo' => 'tabla',
            'obligatorio' => 'si',
            'orden' => 20,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta20->columnas()->createMany([
         ['pregunta' => 'papa','tipo' => 'numero','orden' => 0],
         ['pregunta' => 'avena','tipo' => 'numero','orden' => 1],
         ['pregunta' => 'alfalfa','tipo' => 'numero','orden' => 2],
        ]);


        //pregunta con ocpiones multiples
        $pregunta21 = Pregunta::create([
            'titulo' => 'como conserva sus productos?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 21,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta21->pregunta_opciones()->createMany([
         ['opcion' => 'papa seleccionada'],
         ['opcion' => 'gusanada para semilla'],
         ['opcion' => 'cuarto oscuro'],
        ]);



        //pregunta con ocpiones multiples
        $pregunta22 = Pregunta::create([
            'titulo' => 'que se necesita a futuro para mejorar la produccion?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 22,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta22->pregunta_opciones()->createMany([
         ['opcion' => 'semilla certificada'],
         ['opcion' => 'desparazitacion'],
         ['opcion' => 'vitaminizacion'],
        ]);


        //pregunta de tipo tabla
        $pregunta23 = Pregunta::create([
            'titulo' => 'produccion pecuaria',
            'tipo' => 'tabla',
            'obligatorio' => 'si',
            'orden' => 23,
            'encuesta_id' => $encuesta->id,
        ]);

        $columnas=$pregunta23->columnas()->createMany([
         ['pregunta' => 'especie','tipo' => 'pregunta','orden' => 0],
         ['pregunta' => 'machos','tipo' => 'numero','orden' => 1],
         ['pregunta' => 'hembras','tipo' => 'numero','orden' => 2],
         ['pregunta' => 'crias','tipo' => 'numero','orden' => 3],
        ]);


        // Identificar columna de tipo 'pregunta' para vincular filas
        $columnaPregunta = $columnas->firstWhere('tipo', 'pregunta');

        // Crear filas vinculadas a esa columna
        $pregunta23->filas()->createMany([
            ['pregunta' => 'bovino', 'orden' => 0, 'columna_id' => $columnaPregunta->id],
            ['pregunta' => 'ovino', 'orden' => 1, 'columna_id' => $columnaPregunta->id],
            ['pregunta' => 'porcino', 'orden' => 2, 'columna_id' => $columnaPregunta->id],
        ]);


         //pregunta con ocpiones multiples
        $pregunta24 = Pregunta::create([
            'titulo' => 'que plagas se presentaron?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 24,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta24->pregunta_opciones()->createMany([
         ['opcion' => 'piojo'],
         ['opcion' => 'opcion2'],
         ['opcion' => 'opcion3'],
        ]);


           //pregunta con ocpiones multiples
        $pregunta25 = Pregunta::create([
            'titulo' => 'que enfermedades se presentaron?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 25,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta25->pregunta_opciones()->createMany([
         ['opcion' => 'opcion1'],
         ['opcion' => 'opcion2'],
         ['opcion' => 'opcion3'],
        ]);


           //pregunta con ocpiones multiples
        $pregunta26 = Pregunta::create([
            'titulo' => 'que se necesita para mejorar la produccion pecuaria?',
            'tipo' => 'opcional',
            'obligatorio' => 'si',
            'varias_respuestas' => 'si',
            'orden' => 25,
            'encuesta_id' => $encuesta->id,
        ]);

        $pregunta26->pregunta_opciones()->createMany([
         ['opcion' => 'mas cultivo de alfalfa'],
         ['opcion' => 'mas semillas'],
         ['opcion' => 'opcion3'],
        ]);

    }
}
