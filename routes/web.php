<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BusinessController;



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

Auth::routes(['verify' => true]);
Route::get('/items', [\App\Http\Controllers\ItemController::class, 'index'])->name('item.index');
Route::get('/index', [\App\Http\Controllers\ItemController::class, 'index'])->name('item.index');
Route::get('/index/create', [\App\Http\Controllers\ItemController::class, 'create'])->name('item.add');
Route::post('/index/store', [\App\Http\Controllers\ItemController::class, 'store'])->name('item.store');
Route::post('/delete-items', [\App\Http\Controllers\ItemController::class, 'deleteItems'])->name('delete.items');
Route::get('item/{id}/view', [\App\Http\Controllers\ItemController::class, 'view'])->name('item.view');
Route::delete('/item/{id}', [\App\Http\Controllers\ItemController::class, 'destroy'])->name('item.destroy');
Route::get('item/{id}/edit', [\App\Http\Controllers\ItemController::class, 'edit'])->name('item.edit');
Route::put('/item/{id}', [\App\Http\Controllers\ItemController::class, 'update'])->name('item.update');
Route::post('/items/delete-selected', [\App\Http\Controllers\ItemController::class, 'deleteSelected'])->name('item.deleteSelected');

Route::post('/items/export', [\App\Http\Controllers\ItemController::class, 'export'])->name('item.export');
// Route::get('import', function () {
//     return view('import');
// })->name('import.form');
// Route::post('/import', [\App\Http\Controllers\ItemController::class, 'import'])->name('item.import');
Route::post('/import-items', [\App\Http\Controllers\ItemController::class, 'import'])->name('import.items');


Route::get('/profile', [\App\Http\Controllers\ItemController::class, 'show'])->name('item.profile');
Route::get('/profile/settings', [\App\Http\Controllers\ItemController::class, 'profilesetting'])->name('pages-profile-settings');
Route::post('/profile/settings/update', [\App\Http\Controllers\ItemController::class, 'profileupdate'])->name('profile.settings.update');



Route::resource('mains', \App\Http\Controllers\ItemController::class);
Route::delete('/index/bulk-delete', [\App\Http\Controllers\ItemController::class, 'bulkDelete'])->name('main.bulkDelete');
// Route::get('/index/export', [\App\Http\Controllers\ItemController::class, 'export'])->name('main.export');




Route::get('index/{locale}',[App\Http\Controllers\HomeController::class, 'lang']);
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');


Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/', function () {
    return view('home');
})->name('home');

// Route::get('/businesses', [App\Http\Controllers\BusinessController::class, 'index'])->name('business.index');
// Route::get('/business/{id}', [App\Http\Controllers\BusinessController::class, 'show'])->name('business.show');

