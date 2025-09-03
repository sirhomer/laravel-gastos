<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomesController;
use Illuminate\Support\Facades\Route;


Route::post('/expenses', [ExpenseController::class, 'store']);
Route::post('/incomes', [IncomesController::class, 'store']);
Route::get('/expenses/current-month', [ExpenseController::class, 'currentMonthExpenses']);