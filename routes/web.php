<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ExpenseController;
use App\Http\Controllers\Owner\FinancialController;
use App\Http\Controllers\Owner\IngredientController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\RecipeController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\StockMovementController;
use App\Http\Controllers\Owner\WorkerController;
use App\Http\Controllers\Worker\PosController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isOwner()) {
            return redirect()->route('owner.dashboard');
        }
        return redirect()->route('worker.pos');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Owner Routes
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('products', ProductController::class);

        Route::resource('ingredients', IngredientController::class)->except(['create', 'show', 'edit']);
        Route::get('products/{product}/recipes', [RecipeController::class, 'show'])->name('products.recipes.show');
        Route::post('products/{product}/recipes', [RecipeController::class, 'store'])->name('products.recipes.store');
        Route::delete('products/{product}/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('products.recipes.destroy');

        Route::get('/inventory', [StockMovementController::class, 'index'])->name('inventory.index');
        Route::post('/inventory', [StockMovementController::class, 'store'])->name('inventory.store');

        Route::resource('workers', WorkerController::class)->except(['create', 'show', 'edit']);
        Route::patch('workers/{worker}/toggle-status', [WorkerController::class, 'toggleStatus'])->name('workers.toggle-status');

        Route::resource('expenses', ExpenseController::class)->except(['create', 'show', 'edit', 'update']);
        Route::get('/financials', [FinancialController::class, 'index'])->name('financials.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('workers', [WorkerController::class, 'index'])->name('workers.index');
        Route::post('workers', [WorkerController::class, 'store'])->name('workers.store');
        Route::patch('workers/{worker}/toggle-status', [WorkerController::class, 'toggleStatus'])->name('workers.toggleStatus');
        Route::delete('workers/{worker}', [WorkerController::class, 'destroy'])->name('workers.destroy');
    });

    // Worker Routes (POS & History)
    Route::middleware('role:worker,owner')->prefix('worker')->name('worker.')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
        Route::get('/history', [PosController::class, 'history'])->name('history');
    });
});