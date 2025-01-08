<?php

use Core\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('pages.home');

Route::get('/login', function () {
    return view('pages.login');
})->name('pages.login');
