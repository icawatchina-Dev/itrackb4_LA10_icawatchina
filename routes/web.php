<?php
use App\Http\Controllers\BrgyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/whoami', function () {
    return 'China Icawat| Block 4C| ITRACKB4 Laravel 12';
});

Route::get('/brgy/filter/{municipality}', function (string $municipality) {
    return redirect()->route('brgys.index', ['municipality' => $municipality]);
})->name('brgys.filter');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('brgys', BrgyController::class)
    ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'auth');
 
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/register',[RegisterController::class,'create'])->name('register.create');

Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
