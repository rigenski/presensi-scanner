<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
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

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/store', [AuthController::class, 'login'])->name('login.store');

Route::group(['middleware' => ['auth']], function () {
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::get('/admin', [AdminController::class, 'index'])->name('admin');
  Route::get('/admin/siswa', [SiswaController::class, 'index'])->name('admin.siswa');
  Route::post('/admin/siswa/store', [SiswaController::class, 'store'])->name('admin.siswa.store');
  Route::post('/admin/siswa/{id}/update', [SiswaController::class, 'update'])->name('admin.siswa.update');
  Route::get('/admin/siswa/{id}/destroy', [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');

  Route::get('/admin/presensi', [PresensiController::class, 'index'])->name('admin.presensi');
  Route::get('/admin/presensi/print', [PresensiController::class, 'print'])->name('admin.presensi.print');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pulang', [HomeController::class, 'indexPulang'])->name('pulang');

Route::post('/check', [HomeController::class, 'check'])->name('check');

Route::get('/detail', [HomeController::class, 'indexDetail'])->name('detail');
Route::get('/grafik', [HomeController::class, 'indexGrafik'])->name('grafik');
Route::get('/print', [HomeController::class, 'print'])->name('print');
