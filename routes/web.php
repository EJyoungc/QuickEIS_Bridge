<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Tenants;
use App\Livewire\Connections;
use App\Livewire\Invoices;
use App\Livewire\Logs;

Route::get('/', Tenants::class)->name('home');
Route::get('/tenants', Tenants::class)->name('tenants');
Route::get('/connections', Connections::class)->name('connections');
Route::get('/invoices', Invoices::class)->name('invoices');
Route::get('/logs', Logs::class)->name('logs');

Route::post('/qbwc', [App\Http\Controllers\QbwcSoapController::class, 'handle']);

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
