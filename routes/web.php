<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
})->name('main');

Route::group(['prefix' => '/home'], function () {
    Route::get('/', [
        'uses' => 'App\Http\Controllers\HomeController@index',
        'as' => 'home'
    ]);
    
    Route::post('/reservar', [
        'uses' => 'App\Http\Controllers\HomeController@reservar',
        'as' => 'reservar'
    ]);

    Route::get('/reserva/{id}', [
        'uses' => 'App\Http\Controllers\HomeController@reserva',
        'as' => 'reserva'
    ]);

    Route::post('/reserva/cancelar', [
        'uses' => 'App\Http\Controllers\HomeController@reservaCancelar',
        'as' => 'reservaCancelar'
    ]);

    Route::post('/reserva/editar', [
        'uses' => 'App\Http\Controllers\HomeController@reservaEditar',
        'as' => 'reservaEditar'
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('appIndex');
    })->name('dashboard');

    Route::group(['prefix' => '/app'], function () {
        Route::group(['prefix' => '/reservas'], function () {
            Route::get('/', [
                'uses' => 'App\Http\Controllers\AppController@reservas',
                'as' => 'appIndex'
            ]);
        });

        Route::group(['prefix' => '/mesas'], function () {
            Route::get('/', [
                'uses' => 'App\Http\Controllers\AppController@mesas',
                'as' => 'appMesas'
            ]);

            Route::post('/editar', [
                'uses' => 'App\Http\Controllers\AppController@editarMesas',
                'as' => 'appMesasEdit'
            ]);

            Route::delete('/delete/{id}', [
                'uses' => 'App\Http\Controllers\AppController@eliminarMesas',
                'as' => 'appMesasDelete'
            ]);
        });
    });
});
