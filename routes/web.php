<?php

use Core\Route;

Route::get('/', function () {
    return view('pages.home');
});
