<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Librarian;
use App\Http\Middleware\Warehouseman;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//bárki által elérhető
Route::post('/register',[RegisteredUserController::class, 'store']);
Route::post('/login',[AuthenticatedSessionController::class, 'store']);
ROUTE::get('/hard-covered-books', [LendingController::class, 'hardCoveredBooks']);

Route::patch('update-password/{id}', [UserController::class, "updatePassword"]);

//autentikált útvonal, simple user is
Route::middleware(['auth:sanctum'])
    ->group(function () {
        //profil elérése, mód-a
        Route::get('/auth-user', [UserController::class, 'show']);
        Route::patch('/auth-user', [UserController::class, 'update']);
        //hány kölcsönzése volt idáig
        Route::get('/lendings-count', [LendingController::class, 'lendingCount']);
        //hány aktív kölcsönzése van?
        Route::get('/active-lending-count', [LendingController::class, 'activeLendingCount']);
        //hány könyvet kölcsönzött idáig?
        Route::get('/lendings-books-count', [LendingController::class, 'lendingsBooksCount']);
        //kikölcsönzött könyvek adatai
        Route::get('lendings-books-data', [LendingController::class, 'lendingsBooksData']);
        Route::get('/lendings-copies', [LendingController::class, "lendingsWithCopies"]);
        Route::get('/userlendings', [UserController::class, "userLendings"]);
        Route::get('/reserved-books',[ReservationController::class,'reservedBooks']);
        Route::get('/librarian/users-and-reservations',[UserController::class,'usersWithReservation']);
        Route::get('/librarian/reserved-count',[Reservation::class,'reservedCount']);

        // Kijelentkezés útvonal
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    });

//admin
Route::middleware(['auth:sanctum', Admin::class])
->group(function () {
    //összes kérés
    Route::apiResource('/admin/users', UserController::class);
    Route::get('/admin/specific-date', [LendingController::class, "dateSpecific"]);
    Route::get('/admin/specific-copy/{copy_id}', [LendingController::class, "copySpecific"]);
    
});

//librarian
Route::middleware(['auth:sanctum', Librarian::class])
->group(function () {
    Route::get('/librarian/books-copies', [BookController::class, "booksWithCopies"]);
    Route::apiResource('/librarian/reservation', ReservationController::class);
});

//warehouseman
Route::middleware(['auth:sanctum', Warehouseman::class])
->group(function () {
    //útvonalak
});

