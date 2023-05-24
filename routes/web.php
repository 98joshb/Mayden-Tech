<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserProfileController;
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

Route::redirect('/', '/shopping_list');

Route::get('/shopping_list', [ShoppingListController::class, 'index'])->name('shopping_list.index')->middleware('auth');
Route::post('/shopping_list', [ShoppingListController::class, 'store'])->name('shopping_list.store');
Route::post('/shopping_list/{id}/check', [ShoppingListController::class, 'checkItem'])->name('shopping_list.check');
Route::post('/shopping_list/reorder', [ShoppingListController::class, 'reorder'])->name('shopping_list.reorder');
Route::delete('/shopping_list/{id}', [ShoppingListController::class, 'destroy'])->name('shopping_list.destroy');
Route::post('/shopping_list/send_email', [ShoppingListController::class, 'sendEmail'])->name('shopping_list.send_email');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');




