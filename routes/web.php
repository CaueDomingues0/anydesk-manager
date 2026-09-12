<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AcessoAnydeskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('acessos.index');
    
});

Route::get('/logs', [AcessoAnydeskController::class, 'logs'])->name('acessos.logs');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AcessoAnydeskController::class, 'index'])->name('dashboard');
    
    // Rotas do AnyDesk Manager
    Route::get('/painel', [AcessoAnydeskController::class, 'index'])->name('acessos.index');
    Route::post('/acessos', [AcessoAnydeskController::class, 'store'])->name('acessos.store');
    Route::get('/acessos/{id}/edit', [AcessoAnydeskController::class, 'edit'])->name('acessos.edit');
    Route::put('/acessos/{id}', [AcessoAnydeskController::class, 'update'])->name('acessos.update');
    Route::delete('/acessos/{id}', [AcessoAnydeskController::class, 'destroy'])->name('acessos.destroy');
    Route::patch('/acessos/{id}/favorito', [AcessoAnydeskController::class, 'toggleFavorito'])->name('acessos.favorito');

    // Perfil do usuário (padrão do Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';