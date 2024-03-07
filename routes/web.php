<?php

use Illuminate\Support\Facades\Route;

Route::get('/first-page', 'FirstPageController@index');
Route::get('/second-page', 'SecondPageController@index');
