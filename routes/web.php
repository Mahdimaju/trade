<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TradingJournalController;

// Dashboard & Journal Index Route
Route::get('/', [TradingJournalController::class, 'index'])->name('journal.index');

// Trade Management Routes
Route::post('/trades', [TradingJournalController::class, 'storeTrade'])->name('trades.store');
Route::put('/trades/{trade}', [TradingJournalController::class, 'updateTrade'])->name('trades.update');
Route::delete('/trades/{trade}', [TradingJournalController::class, 'destroyTrade'])->name('trades.destroy');

// Balance & Transaction Routes
Route::post('/transactions', [TradingJournalController::class, 'storeTransaction'])->name('transactions.store');
Route::delete('/transactions/{transaction}', [TradingJournalController::class, 'destroyTransaction'])->name('transactions.destroy');

