<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\BalanceTransaction;
use Illuminate\Http\Request;

class TradingJournalController extends Controller
{
    /**
     * Display the Trading Journal Dashboard.
     */
    public function index()
    {
        // 1. Calculate Account Metrics using precise float operations
        $totalTopup = (float) BalanceTransaction::where('type', 'topup')->sum('amount');
        $totalWithdrawal = (float) BalanceTransaction::where('type', 'withdrawal')->sum('amount');
        $totalProfitLoss = (float) Trade::where('status', 'closed')->sum('profit_loss');

        // Equity = Total Top-up - Total Withdrawal + Total Profit/Loss (closed trades)
        $equity = $totalTopup - $totalWithdrawal + $totalProfitLoss;

        // 2. Win Rate = (Jumlah trade profit / Total trade closed) * 100%
        $closedTradesCount = Trade::where('status', 'closed')->count();
        $winningTradesCount = Trade::where('status', 'closed')->where('profit_loss', '>', 0)->count();
        $winRate = $closedTradesCount > 0 ? round(($winningTradesCount / $closedTradesCount) * 100, 2) : 0.00;

        // 3. Trade General Count
        $totalTrades = Trade::count();
        $openTradesCount = Trade::where('status', 'open')->count();

        // 4. Average RRR (Risk Reward Ratio) for trades that have a valid calculation
        $tradesWithRrr = Trade::all()->filter(function ($trade) {
            return $trade->risk_reward_ratio > 0;
        });
        $avgRrr = $tradesWithRrr->count() > 0 ? round($tradesWithRrr->avg('risk_reward_ratio'), 2) : 0.00;

        // 5. Generate Chronological Equity Curve Chart Data
        $transactions = BalanceTransaction::orderBy('created_at', 'asc')->orderBy('id', 'asc')->get();
        
        $chartLabels = ['Awal'];
        $chartData = [0.00];
        $cumulative = 0.00;

        foreach ($transactions as $tx) {
            $amount = (float) $tx->amount;
            if ($tx->type === 'withdrawal') {
                $cumulative -= $amount;
            } else {
                $cumulative += $amount;
            }
            $chartLabels[] = $tx->created_at->format('d M H:i');
            $chartData[] = round($cumulative, 2);
        }

        // Fetch records for tables
        $trades = Trade::orderBy('created_at', 'desc')->get();
        $txs = BalanceTransaction::orderBy('created_at', 'desc')->take(15)->get();

        return view('journal.index', compact(
            'equity',
            'totalTopup',
            'totalWithdrawal',
            'totalProfitLoss',
            'winRate',
            'winningTradesCount',
            'totalTrades',
            'openTradesCount',
            'closedTradesCount',
            'avgRrr',
            'chartLabels',
            'chartData',
            'trades',
            'txs'
        ));
    }

    /**
     * Store a new trade in database.
     */
    public function storeTrade(Request $request)
    {
        $validated = $request->validate([
            'pair' => 'required|string|max:50',
            'type' => 'required|in:buy,sell',
            'entry_price' => 'required|numeric|min:0.00001',
            'stop_loss' => 'required|numeric|min:0.00001',
            'take_profit' => 'required|numeric|min:0.00001',
            'lot_size' => 'required|numeric|min:0.01',
            'status' => 'required|in:open,closed',
            'profit_loss' => 'nullable|numeric',
        ]);

        if ($validated['status'] === 'open') {
            $validated['profit_loss'] = 0.00;
        } else {
            $validated['profit_loss'] = $validated['profit_loss'] ?? 0.00;
        }

        Trade::create($validated);

        return redirect()->route('journal.index')->with('success', 'Posisi trade berhasil ditambahkan!');
    }

    /**
     * Update an existing trade's details or status.
     */
    public function updateTrade(Request $request, Trade $trade)
    {
        $validated = $request->validate([
            'pair' => 'required|string|max:50',
            'type' => 'required|in:buy,sell',
            'entry_price' => 'required|numeric|min:0.00001',
            'stop_loss' => 'required|numeric|min:0.00001',
            'take_profit' => 'required|numeric|min:0.00001',
            'lot_size' => 'required|numeric|min:0.01',
            'status' => 'required|in:open,closed',
            'profit_loss' => 'nullable|numeric',
        ]);

        if ($validated['status'] === 'open') {
            $validated['profit_loss'] = 0.00;
        } else {
            $validated['profit_loss'] = $validated['profit_loss'] ?? 0.00;
        }

        $trade->update($validated);

        return redirect()->route('journal.index')->with('success', 'Detail trade berhasil diperbarui!');
    }

    /**
     * Store a balance transaction (Topup / Withdrawal).
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:topup,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        // Safety check: Avoid negative balance during withdrawals
        if ($validated['type'] === 'withdrawal') {
            $totalTopup = (float) BalanceTransaction::where('type', 'topup')->sum('amount');
            $totalWithdrawal = (float) BalanceTransaction::where('type', 'withdrawal')->sum('amount');
            $totalProfitLoss = (float) Trade::where('status', 'closed')->sum('profit_loss');
            $equity = $totalTopup - $totalWithdrawal + $totalProfitLoss;

            if ($validated['amount'] > $equity) {
                return redirect()->back()
                    ->withErrors(['amount' => 'Saldo (Equity) tidak mencukupi untuk melakukan penarikan sebesar Rp ' . number_format($validated['amount'], 2)])
                    ->withInput();
            }
        }

        BalanceTransaction::create([
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? ($validated['type'] === 'topup' ? 'Top-up Akun' : 'Penarikan Dana'),
        ]);

        return redirect()->route('journal.index')->with('success', 'Transaksi saldo berhasil dicatat!');
    }

    /**
     * Delete an existing trade.
     */
    public function destroyTrade(Trade $trade)
    {
        $trade->delete();
        return redirect()->route('journal.index')->with('success', 'Catatan trade berhasil dihapus.');
    }

    /**
     * Delete a balance transaction (restrict to topups/withdrawals).
     */
    public function destroyTransaction(BalanceTransaction $transaction)
    {
        if ($transaction->type === 'trade_result') {
            return redirect()->back()->withErrors(['transaction' => 'Hasil trade tidak dapat dihapus secara langsung. Silakan hapus atau ubah status trade yang bersangkutan.']);
        }

        $transaction->delete();
        return redirect()->route('journal.index')->with('success', 'Transaksi saldo berhasil dihapus.');
    }
}
