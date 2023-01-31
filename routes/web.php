<?php

use App\Http\Controllers\CalendarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::prefix('/calendar')->name('calendar.')->middleware('auth')->group(function(){
    Route::get('/index', [CalendarController::class, 'index'])->name('index');
    Route::get('/select_ajax', [CalendarController::class, 'select_ajax'])->name('select_ajax');
    Route::get('/select_ajax', [CalendarController::class, 'select_ajax'])->name('select_ajax');
    Route::get('/color_select_ajax', [CalendarController::class, 'color_select_ajax'])->name('color_select_ajax');
    Route::get('/search_ajax', [CalendarController::class, 'search_ajax'])->name('search_ajax');
    Route::post('/delete_ajax', [CalendarController::class, 'delete_ajax'])->name('delete_ajax');
    Route::post('/color_delete_ajax', [CalendarController::class, 'color_delete_ajax'])->name('color_delete_ajax');
    Route::post('/update',[CalendarController::class, 'update'])->name('update');
    Route::post('/color_update',[CalendarController::class, 'color_update'])->name('color_update');
});