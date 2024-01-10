<?php

use App\Http\Controllers\TaskController;
use App\Task;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
    return redirect('/tasks');
});
Auth::routes();

Route::get('/home', function () {
    return redirect()->route('tasks.index');
})->name('home');

    Route::get('tasks', 'TaskController@index')->name('tasks.index');
    Route::post('tasks', 'TaskController@store')->name('tasks.store');
    Route::put('tasks/sync', 'TaskController@sync')->name('tasks.sync');
    Route::put('tasks/{task}', 'TaskController@update')->name('tasks.update');

    Route::post('statuses', 'StatusController@store')->name('statuses.store');
    Route::delete('statuses/{status}','StatusController@destroy')->name('statuses.destroy');


Route::get('db-dump','HomeController@dbDump');


