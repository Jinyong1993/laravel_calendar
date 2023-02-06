<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Member;
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

Route::prefix('/auth')->name('auth.')->middleware('auth')->group(function(){
    Route::get('/user_info', [Member::class, 'user_info'])->name('user_info');
    Route::post('/user_update', [Member::class, 'user_update'])->name('user_update');
});

Route::prefix('/board')->name('board.')->middleware('auth')->group(function(){
    Route::get('/index', [BoardController::class, 'index'])->name('index');
    Route::get('/content', [BoardController::class, 'content'])->name('content');
    Route::get('/create_view', [BoardController::class, 'create_view'])->name('create_view');
    Route::get('/comment_content', [BoardController::class, 'comment_content'])->name('comment_content');
    Route::post('/create', [BoardController::class, 'create'])->name('create');
    Route::post('/comment_update_ajax', [BoardController::class, 'comment_update_ajax'])->name('comment_update_ajax');
    Route::post('/comment_create', [BoardController::class, 'comment_create'])->name('comment_create');
    Route::post('/delete', [BoardController::class, 'delete'])->name('delete');
    Route::post('/comment_delete', [BoardController::class, 'comment_delete'])->name('comment_delete');
});

Route::prefix('/calendar')->name('calendar.')->middleware('auth')->group(function(){
    Route::get('/index', [CalendarController::class, 'index'])->name('index');
    Route::get('/select_ajax', [CalendarController::class, 'select_ajax'])->name('select_ajax');
    Route::get('/color_select_ajax', [CalendarController::class, 'color_select_ajax'])->name('color_select_ajax');
    Route::get('/search_ajax', [CalendarController::class, 'search_ajax'])->name('search_ajax');
    Route::post('/delete_ajax', [CalendarController::class, 'delete_ajax'])->name('delete_ajax');
    Route::post('/color_delete_ajax', [CalendarController::class, 'color_delete_ajax'])->name('color_delete_ajax');
    Route::post('/update',[CalendarController::class, 'update'])->name('update');
    Route::post('/color_update',[CalendarController::class, 'color_update'])->name('color_update');
});