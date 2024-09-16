<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Data\DataStudentController;
use App\Http\Controllers\Data\UserControlController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['guest'])->group(function () {
    Route::view('/', 'front-page.index');

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('/verify/{verify_key}', [LoginController::class, 'verify']);
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('user:admin');
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('user:user');

    Route::prefix('student')->group(function () {
        Route::get('/data', [DataStudentController::class, 'index'])->name('student.data');
        Route::get('/add', [DataStudentController::class, 'create'])->name('student.create');
        Route::get('/edit/{id}', [DataStudentController::class, 'edit'])->name('student.edit');
        Route::post('/add-student', [DataStudentController::class, 'store'])->name('student.store');
        Route::post('/update/{id}', [DataStudentController::class, 'update'])->name('student.update');
        Route::post('/delete/{id}', [DataStudentController::class, 'destroy'])->name('data.destroy');
    });

    Route::resource('/user-control', UserControlController::class)->middleware('user:admin');
});
