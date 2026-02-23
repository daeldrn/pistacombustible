<?php

use Illuminate\Support\Facades\Route;


// Redirigir la raíz al frontend estático
Route::get('/', function () {
    return redirect('/app/login.html');
});

