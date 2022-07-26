<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/add', [CurrencyController::class, 'index'])->name('get-currency');
Route::get('/all', [CurrencyController::class, 'getByDateAndValuteId'])->name('get-currencies');

Route::post('/create', [CurrencyController::class, 'createCurrency'])->name('create-currency');
Route::put('/update', [CurrencyController::class, 'updateCurrency'])->name('update-currency');
