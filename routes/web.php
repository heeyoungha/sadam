<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/board', [BoardController::class,'index'])->name('board_index');
Route::get('/board/create', [BoardController::class,'create'])->name('board_create');
Route::post('/board', [BoardController::class,'store'])->name('board_store');
Route::get('/board/{board_id}', [BoardController::class,'show'])->name('board_show');
Route::put('/board/{board_id}', [BoardController::class,'update'])->name('board_update');
Route::delete('/board/{board_id}', [BoardController::class,'destroy'])->name('board_destroy');

//OAuth 공급자로 리다이렉션
Route::get('/auth/redirect', [LoginController::class,'redirectToProvider']);

//공급자로부터 콜백을 수신
Route::get('/auth/github/callback', [LoginController::class,'handleProviderCallback']);