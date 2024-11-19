<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

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
        Route::get('/', [
            'uses' => 'App\Http\Controllers\AppController@reservas',
            'as' => 'appIndex'
        ]);

        Route::get('/mesas', [
            'uses' => 'App\Http\Controllers\AppController@mesas',
            'as' => 'appMesas'
        ]);
    });

});
