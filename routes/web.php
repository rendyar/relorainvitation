<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\TypeInvitationController;
use GuzzleHttp\Middleware;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Route;

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


Route::middleware('guest')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('auth.login');
        Route::post('login', [LoginController::class, 'login'])->name('auth.login');
        Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');
    });
});

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::prefix('invitation')->middleware('auth')->group(function () {
    Route::get('', [InvitationController::class, 'index'])->name('invitation.index');
    Route::get('data', [InvitationController::class, 'data'])->name('invitation.data');
    Route::get('create', [InvitationController::class, 'create'])->name('invitation.create');
    Route::post('store', [InvitationController::class, 'store'])->name('invitation.store');
    Route::get('edit/{id}', [InvitationController::class, 'edit'])->name('invitation.edit');
    Route::post('update/{id}', [InvitationController::class, 'update'])->name('invitation.update');
    Route::delete('delete/{id}', [InvitationController::class, 'delete'])->name('invitation.delete');
    Route::get('select2', [InvitationController::class, 'select2'])->name('invitation.select2');
});

Route::prefix('souvenir')->middleware('auth')->group(function () {
    Route::get('', [SouvenirController::class, 'index'])->name('souvenir.index');
    Route::get('data', [SouvenirController::class, 'data'])->name('souvenir.data');
    Route::get('create', [SouvenirController::class, 'create'])->name('souvenir.create');
    Route::post('store', [SouvenirController::class, 'store'])->name('souvenir.store');
    Route::get('edit/{id}', [SouvenirController::class, 'edit'])->name('souvenir.edit');
    Route::post('update/{id}', [SouvenirController::class, 'update'])->name('souvenir.update');
    Route::delete('delete/{id}', [SouvenirController::class, 'delete'])->name('souvenir.delete');
    Route::get('select2', [SouvenirController::class, 'select2'])->name('souvenir.select2');
});

Route::prefix('type-invitation')->middleware('auth')->group(function () {
    Route::get('', [TypeInvitationController::class, 'index'])->name('type-invitation.index');
    Route::get('data', [TypeInvitationController::class, 'data'])->name('type-invitation.data');
    Route::get('create', [TypeInvitationController::class, 'create'])->name('type-invitation.create');
    Route::post('store', [TypeInvitationController::class, 'store'])->name('type-invitation.store');
    Route::get('edit/{id}', [TypeInvitationController::class, 'edit'])->name('type-invitation.edit');
    Route::post('update/{id}', [TypeInvitationController::class, 'update'])->name('type-invitation.update');
    Route::delete('delete/{id}', [TypeInvitationController::class, 'delete'])->name('type-invitation.delete');
    Route::get('select2', [TypeInvitationController::class, 'select2'])->name('type-invitation.select2');
    Route::get('template', [TypeInvitationController::class, 'downloadTemplate'])->name('type-invitation.template');
    Route::post('import', [TypeInvitationController::class, 'import'])->name('type-invitation.import');
    Route::get('export', [TypeInvitationController::class, 'export'])->name('type-invitation.export');
});

Route::prefix('attendance')->middleware('auth')->group(function () {
    Route::get('', [AttendanceController::class, 'index'])->name('attendance.index');

    Route::get('data-check-in', [AttendanceController::class, 'dataCheckIn'])->name('attendance.data-check-in');
    Route::get('data-check-out', [AttendanceController::class, 'dataCheckOut'])->name('attendance.data-check-out');

    Route::get('create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('edit/{id}', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::post('update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('delete/{id}', [AttendanceController::class, 'delete'])->name('type-invitation.delete');
    Route::get('select2', [AttendanceController::class, 'select2'])->name('type-invitation.select2');
});

Route::prefix('check-in-qr')->middleware('auth')->group(function () {
    Route::get('', [CheckInController::class, 'index'])->name('check-in-qr.index');
});

Route::prefix('check-in-manual')->middleware('auth')->group(function () {
    Route::get('', [CheckInController::class, 'manual'])->name('check-in-manual.index');
    Route::get('data-check-in', [CheckInController::class, 'dataCheckIn'])->name('check-in-manual.data-check-in');
    Route::get('data-check-out', [CheckInController::class, 'dataCheckOut'])->name('check-in-manual.data-check-out');
});


Route::post('checkin', [CheckInController::class, 'checkin'])->name('check-in');
Route::post('checkout', [CheckInController::class, 'checkout'])->name('check-out');


Route::prefix('data')->middleware('auth')->group(function () {
    Route::get('total-guests', [DataController::class, 'totalGuests'])->name('data.total-guests');
});
