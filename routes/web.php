<?php

use Azuriom\Plugin\Votingwallet\Controllers\VotingwalletHomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your plugin. These
| routes are loaded by the RouteServiceProvider of your plugin within
| a group which contains the "web" middleware group and your plugin name
| as prefix. Now create something great!
|
*/

Route::get('/', [VotingwalletHomeController::class, 'index'])->name('index');
Route::post('/withdraw', [VotingwalletHomeController::class, 'withdraw'])->name('withdraw');
Route::get('/statistical', [VotingwalletHomeController::class, 'statistical'])->name('statistical');
Route::get('/history', [VotingwalletHomeController::class, 'history'])->name('history');
