<?php

use App\Http\Controllers\afiliado\Controlador_afiliado;
use App\Http\Controllers\distrito\Controlador_distrito;
use App\Http\Controllers\encuesta\Controlador_encuesta;
use App\Http\Controllers\formulario\Controlador_formulario;
use App\Http\Controllers\Usuario\Controlador_login;
use App\Http\Controllers\Usuario\Controlador_permisos;
use App\Http\Controllers\Usuario\Controlador_rol;
use App\Http\Controllers\Usuario\Controlador_usuario;
use App\Http\Middleware\Autenticados;
use App\Http\Middleware\No_autenticados;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->middleware([No_autenticados::class])->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('login');

    Route::get('/login', function () {
        return view('login', ['fromHome' => true]);
    })->name('login_home');

    Route::controller(Controlador_login::class)->group(function () {
        Route::post('ingresar', 'ingresar')->name('log_ingresar');
    });
});


Route::prefix('/admin')->middleware([Autenticados::class])->group(function () {
    Route::controller(Controlador_login::class)->group(function () {
        Route::get('inicio', 'inicio')->name('inicio');
        Route::post('cerrar_session', 'cerrar_session')->name('salir');
    });

    // PARA EL USUARIO
    Route::controller(Controlador_usuario::class)->group(function () {
        Route::get('perfil', 'perfil')->name('perfil');
        Route::get('listarUsuarios', 'listar');
        Route::post('pwd_guardar', 'password_guardar')->name('pwd_guardar');
        Route::resource('/usuarios', Controlador_usuario::class);
        Route::put('resetar_usuario/{id_usuario}', 'resetar_usuario');
        Route::put('editar_rol/{id_usuario}', 'editar_rol');
    });

    //PARA LOS PERMISOS
    Route::resource('permisos', Controlador_permisos::class);
    Route::post('/permisos/listar', [Controlador_permisos::class, 'listar'])->name('permisos.listar');

    //PARA EL ROL
    Route::resource('roles', Controlador_rol::class);


    //PARA DISTRITO Y COMUNIDAD
    Route::controller(Controlador_distrito::class)->group(function () {

        Route::resource('distrito', Controlador_distrito::class);
        Route::get('listarDistrito', 'listarDistrito')->name('distrito.listar');
        Route::get('listarComunidad', 'listarComunidad')->name('distrito.listar');
        Route::post('nuevaComunidad', 'nuevaComunidad')->name('comunidad.nuevo');
        Route::delete('eliminar_comunidad/{id_comunidad}', 'eliminar_comunidad')->name('comunidad.eliminar');
    });

    // PARA LA ENCUESTA
    Route::controller(Controlador_encuesta::class)->group(function () {

        Route::resource('encuestas', Controlador_encuesta::class);
        Route::get('listarEncuesta', 'listarEncuesta')->name('encuesta.listar');
        Route::get('verEncuesta/{id_encuesta}', 'verEncuesta')->name('encuesta.verEncuesta');
        Route::put('guardarPregunta/{id_encuesta}', 'guardarPregunta')->name('encuesta.guardarPregunta');
        Route::put('guardarPreguntasTabla/{id_encuesta}', 'guardarPreguntaTabla')->name('encuesta.guardarPreguntaTabla');
        Route::post('editarPregunta', 'editarPregunta')->name('encuesta.editarPregunta');
        Route::get('obtenerPreguntaEncuesta/{id_encuesta}', 'obtenerPreguntaEncuesta')->name('encuesta.listar');
        Route::put('guardarPreguntaEditada/{id_encuesta}', 'guardarPreguntaEditada')->name('encuesta.listar');
        Route::put('moverPregunta/{id_encuesta}', 'cambiarOrden')->name('encuesta.cambiarOrden');
        Route::delete('eliminarEncuesta/{id_encuesta}', 'eliminarEncuesta')->name('encuesta.eliminarEncuesta');
        Route::put('actualizarEncuesta/{id_encuesta}', 'actualizarEncuesta')->name('encuesta.actualizarEncuesta');
        Route::put('actualizarEstado/{id_encuesta}', 'actualizarEstado')->name('encuesta.actualizarEstado');


        // SECCION DE INFORME
        Route::get('verInforme/{id_encuesta}', 'verInforme')->name('encuesta.verEncuesta');
    });

    // Ruta para la vista del constructor de tabla
    Route::get('constructorTabla', function () {
        return view('administrador.encuesta.constructor_tabla');
    })->name('preguntas.constructor_tabla');

    // PARA EL FORMULARIO

    Route::controller(Controlador_formulario::class)->group(function () {

        Route::resource('formulario', Controlador_formulario::class);
        Route::get('listarFormularios', 'listarFormularios')->name('formulario.listarFormularios');
        Route::get('buscarAfiliado/{id_afiliado}', 'buscarAfiliado')->name('formulario.buscarAfiliado');
        Route::get('responderFormulario/{id_formulario}/{id_afiliado}', 'responderFormulario')->name('formulario.responderFormulario');

    });

    // PARA EL AFILIADO
    Route::controller(Controlador_afiliado::class)->group(function () {

        Route::resource('afiliado', Controlador_afiliado::class);
        Route::get('listarAfiliado', 'listarAfiliado')->name('afiliado.listar');
        Route::put('actualizarAfiliado/{id_afiliado}', 'actualizarAfiliado')->name('afiliado.actualizarAfiliado');
    });
});
