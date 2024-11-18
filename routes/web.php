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
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
