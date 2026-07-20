<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/countries', [ApiController::class, 'countries']);
Route::get('/risk', [ApiController::class, 'risk']);
Route::get('/ports', [ApiController::class, 'ports']);
Route::get('/news', [ApiController::class, 'news']);
Route::get('/currency', [ApiController::class, 'currency']);
