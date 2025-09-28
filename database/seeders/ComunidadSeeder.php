<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comunidad;

class ComunidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comunidades = [
            // Distrito 1
            ['titulo' => 'Comunidad A1-1', 'distrito_id' => 1, 'descripcion' => 'Comunidad ubicada en el Distrito 1, sector norte.'],
            ['titulo' => 'Comunidad A1-2', 'distrito_id' => 1, 'descripcion' => 'Zona residencial con población en crecimiento.'],
            ['titulo' => 'Comunidad A1-3', 'distrito_id' => 1, 'descripcion' => 'Área con actividades comerciales y vecinales.'],

            // Distrito 2
            ['titulo' => 'Comunidad A2-1', 'distrito_id' => 2, 'descripcion' => 'Barrio tradicional del Distrito 2.'],
            ['titulo' => 'Comunidad A2-2', 'distrito_id' => 2, 'descripcion' => 'Zona con infraestructura educativa y de salud.'],
            ['titulo' => 'Comunidad A2-3', 'distrito_id' => 2, 'descripcion' => 'Área de expansión urbana en el distrito.'],

            // Distrito 3
            ['titulo' => 'Sopocachi', 'distrito_id' => 3, 'descripcion' => 'Barrio céntrico con gran actividad cultural.'],
            ['titulo' => 'San Pedro', 'distrito_id' => 3, 'descripcion' => 'Zona residencial con comercio activo.'],
            ['titulo' => 'Tembladerani', 'distrito_id' => 3, 'descripcion' => 'Área conocida por su cercanía al estadio.'],

            // Distrito 4
            ['titulo' => 'Alto Tacagua', 'distrito_id' => 4, 'descripcion' => 'Comunidad en zona alta con vistas panorámicas.'],
            ['titulo' => 'Faro Murillo', 'distrito_id' => 4, 'descripcion' => 'Barrio residencial con accesos principales.'],
            ['titulo' => 'San Juan Cotahuma', 'distrito_id' => 4, 'descripcion' => 'Zona en expansión del Distrito 4.'],

            // Distrito 5
            ['titulo' => 'Niño Kollo', 'distrito_id' => 5, 'descripcion' => 'Área urbana con población joven.'],
            ['titulo' => 'Pasankeri', 'distrito_id' => 5, 'descripcion' => 'Zona residencial con comercio local.'],
            ['titulo' => 'Bajo Llojeta', 'distrito_id' => 5, 'descripcion' => 'Área ubicada en la parte baja del distrito.'],

            // Distrito 6
            ['titulo' => 'Llojeta', 'distrito_id' => 6, 'descripcion' => 'Zona residencial cercana a áreas naturales.'],
            ['titulo' => 'Tacagua Baja', 'distrito_id' => 6, 'descripcion' => 'Área en desarrollo con acceso vial.'],
            ['titulo' => 'Casa Cota', 'distrito_id' => 6, 'descripcion' => 'Comunidad reconocida en el Distrito 6.'],

            // Distrito 7
            ['titulo' => 'Villa Victoria', 'distrito_id' => 7, 'descripcion' => 'Zona popular con gran actividad social.'],
            ['titulo' => 'Pura Pura', 'distrito_id' => 7, 'descripcion' => 'Área con espacios recreativos y deportivos.'],
            ['titulo' => 'Ciudadela Ferroviaria', 'distrito_id' => 7, 'descripcion' => 'Barrio tradicional del Distrito 7.'],

            // Distrito 8
            ['titulo' => 'El Tejar', 'distrito_id' => 8, 'descripcion' => 'Zona céntrica con importante movimiento comercial.'],
            ['titulo' => 'Alto Tejar', 'distrito_id' => 8, 'descripcion' => 'Área en altura con urbanización creciente.'],
            ['titulo' => 'San Sebastián', 'distrito_id' => 8, 'descripcion' => 'Comunidad con presencia histórica.'],

            // Distrito 9
            ['titulo' => 'Gran Poder', 'distrito_id' => 9, 'descripcion' => 'Zona cultural conocida por su fiesta patronal.'],
            ['titulo' => 'Miraflores', 'distrito_id' => 9, 'descripcion' => 'Área residencial y de servicios médicos.'],
            ['titulo' => 'San Jorge', 'distrito_id' => 9, 'descripcion' => 'Barrio céntrico con edificios modernos.'],

            // Distrito 10
            ['titulo' => 'Cota Cota', 'distrito_id' => 10, 'descripcion' => 'Zona residencial con urbanizaciones privadas.'],
            ['titulo' => 'Irpavi', 'distrito_id' => 10, 'descripcion' => 'Área con mezcla de comercio y viviendas.'],
            ['titulo' => 'Achumani', 'distrito_id' => 10, 'descripcion' => 'Zona residencial y comercial en expansión.'],

            // Distrito 11
            ['titulo' => 'Comunidad 11-1', 'distrito_id' => 11, 'descripcion' => 'Pequeña comunidad del Distrito 11.'],
            ['titulo' => 'Comunidad 11-2', 'distrito_id' => 11, 'descripcion' => 'Área semiurbana en el Distrito 11.'],
            ['titulo' => 'Comunidad 11-3', 'distrito_id' => 11, 'descripcion' => 'Barrio en crecimiento en la periferia.'],

            // Distrito 12
            ['titulo' => 'Comunidad 12-1', 'distrito_id' => 12, 'descripcion' => 'Zona rural con actividades agrícolas.'],
            ['titulo' => 'Comunidad 12-2', 'distrito_id' => 12, 'descripcion' => 'Área en transición a lo urbano.'],
            ['titulo' => 'Comunidad 12-3', 'distrito_id' => 12, 'descripcion' => 'Comunidad con población dispersa.'],

            // Distrito 13
            ['titulo' => 'Comunidad 13-1', 'distrito_id' => 13, 'descripcion' => 'Zona de importancia comunal.'],
            ['titulo' => 'Comunidad 13-2', 'distrito_id' => 13, 'descripcion' => 'Área con fuerte identidad barrial.'],
            ['titulo' => 'Comunidad 13-3', 'distrito_id' => 13, 'descripcion' => 'Zona en proceso de urbanización.'],

            // Distrito 14
            ['titulo' => 'Pampahasi', 'distrito_id' => 14, 'descripcion' => 'Zona reconocida en el Distrito 14.'],
            ['titulo' => 'San Antonio', 'distrito_id' => 14, 'descripcion' => 'Área con intensa actividad comercial.'],
            ['titulo' => 'Callapa', 'distrito_id' => 14, 'descripcion' => 'Zona residencial y semiurbana.'],

            // Distrito 15
            ['titulo' => 'Villa Armonía', 'distrito_id' => 15, 'descripcion' => 'Barrio residencial en expansión.'],
            ['titulo' => 'La Florida', 'distrito_id' => 15, 'descripcion' => 'Zona con urbanizaciones modernas.'],
            ['titulo' => 'Següencoma', 'distrito_id' => 15, 'descripcion' => 'Área residencial de clase media.'],

            // Distrito 16
            ['titulo' => 'Comunidad 16-1', 'distrito_id' => 16, 'descripcion' => 'Zona rural vinculada a la agricultura.'],
            ['titulo' => 'Comunidad 16-2', 'distrito_id' => 16, 'descripcion' => 'Área con población dispersa.'],
            ['titulo' => 'Comunidad 16-3', 'distrito_id' => 16, 'descripcion' => 'Sector en desarrollo urbano.'],

            // Distrito 17
            ['titulo' => 'Comunidad 17-1', 'distrito_id' => 17, 'descripcion' => 'Comunidad reconocida del Distrito 17.'],
            ['titulo' => 'Comunidad 17-2', 'distrito_id' => 17, 'descripcion' => 'Área semiurbana en expansión.'],
            ['titulo' => 'Comunidad 17-3', 'distrito_id' => 17, 'descripcion' => 'Zona residencial emergente.'],

            // Distrito 18
            ['titulo' => 'Obrajes', 'distrito_id' => 18, 'descripcion' => 'Barrio con actividad estudiantil y comercial.'],
            ['titulo' => 'Bolognia', 'distrito_id' => 18, 'descripcion' => 'Zona residencial del sur de La Paz.'],
            ['titulo' => 'Ovejuyo', 'distrito_id' => 18, 'descripcion' => 'Área con población creciente.'],

            // Distrito 19
            ['titulo' => 'Irpavi', 'distrito_id' => 19, 'descripcion' => 'Barrio residencial y comercial.'],
            ['titulo' => 'Cota Cota', 'distrito_id' => 19, 'descripcion' => 'Zona con áreas residenciales privadas.'],
            ['titulo' => 'Calacoto', 'distrito_id' => 19, 'descripcion' => 'Zona sur con gran actividad económica.'],

            // Distrito 20
            ['titulo' => 'Charoplaya', 'distrito_id' => 20, 'descripcion' => 'Área rural con vocación agrícola.'],
            ['titulo' => 'Buenos Aires', 'distrito_id' => 20, 'descripcion' => 'Zona barrial de población activa.'],
            ['titulo' => 'Yurumani', 'distrito_id' => 20, 'descripcion' => 'Comunidad en expansión del distrito.'],

            // Distrito 21
            ['titulo' => 'Palcoma', 'distrito_id' => 21, 'descripcion' => 'Zona rural con paisaje montañoso.'],
            ['titulo' => 'Chacaltaya', 'distrito_id' => 21, 'descripcion' => 'Área cercana a la cordillera.'],
            ['titulo' => 'Milluni', 'distrito_id' => 21, 'descripcion' => 'Zona reconocida por su entorno natural.'],

            // Distrito 22
            ['titulo' => 'Comunidad 22-1', 'distrito_id' => 22, 'descripcion' => 'Barrio emergente del Distrito 22.'],
            ['titulo' => 'Comunidad 22-2', 'distrito_id' => 22, 'descripcion' => 'Área residencial en formación.'],
            ['titulo' => 'Comunidad 22-3', 'distrito_id' => 22, 'descripcion' => 'Zona rural con acceso limitado.'],

            // Distrito 23
            ['titulo' => 'Comunidad 23-1', 'distrito_id' => 23, 'descripcion' => 'Pequeña comunidad del Distrito 23.'],
            ['titulo' => 'Comunidad 23-2', 'distrito_id' => 23, 'descripcion' => 'Zona residencial en expansión.'],
            ['titulo' => 'Comunidad 23-3', 'distrito_id' => 23, 'descripcion' => 'Área con presencia vecinal organizada.'],

            // Distrito 24
            ['titulo' => 'Comunidad 24-1', 'distrito_id' => 24, 'descripcion' => 'Comunidad ubicada en el extremo sur.'],
            ['titulo' => 'Comunidad 24-2', 'distrito_id' => 24, 'descripcion' => 'Zona con población rural y urbana.'],
            ['titulo' => 'Comunidad 24-3', 'distrito_id' => 24, 'descripcion' => 'Área con proyección de urbanización.'],
        ];

        foreach ($comunidades as $comunidad) {
            Comunidad::create($comunidad);
        }
    }
}
