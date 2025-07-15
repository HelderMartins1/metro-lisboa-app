<?php

use App\Http\Controllers\InfoController;
use Illuminate\Support\Facades\Route;

Route::get('lines', [InfoController::class, 'allLines']);
Route::get('destinations', [InfoController::class, 'allDestinations']);
Route::get('{line}/stations', [InfoController::class, 'stations']);
Route::get('{station}/trains', [InfoController::class, 'trains']);
