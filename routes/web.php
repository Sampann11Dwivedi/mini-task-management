<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return view('login');
});

Route::get('/', function () {
    return view('login');
});

Route::get('tasks', function () {
    return view('task.front');
});
