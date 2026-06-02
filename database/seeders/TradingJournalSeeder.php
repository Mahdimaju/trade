<?php

namespace Database\Seeders;

use App\Models\Trade;
use App\Models\BalanceTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TradingJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records to ensure a fresh, clean mock environment
        Trade::truncate();
        BalanceTransaction::truncate();

        // 1. Initial Deposit (Topup)
        BalanceTransaction::create([
            'type' => 'topup',
            'amount' => 100000000.00,
            'description' => 'Deposit Modal Awal Jurnal Trading',
            'created_at' => Carbon::now()->subDays(25),
        ]);

        // 2. Closed Trade 1 (Profit Buy)
        $t1 = Trade::create([
            'pair' => 'EURUSD',
            'type' => 'buy',
            'entry_price' => 1.08500,
            'stop_loss' => 1.08000,
            'take_profit' => 1.09500,
            'lot_size' => 1.00,
            'profit_loss' => 4500000.00,
            'status' => 'closed',
            'created_at' => Carbon::now()->subDays(20),
        ]);
        // Adjust transaction timestamps to align chronologically
        $t1->balanceTransaction()->update([
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20),
        ]);

        // 3. Closed Trade 2 (Loss Sell)
        $t2 = Trade::create([
            'pair' => 'GBPUSD',
            'type' => 'sell',
            'entry_price' => 1.26500,
            'stop_loss' => 1.27000,
            'take_profit' => 1.25000,
            'lot_size' => 1.00,
            'profit_loss' => -2500000.00,
            'status' => 'closed',
            'created_at' => Carbon::now()->subDays(15),
        ]);
        $t2->balanceTransaction()->update([
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now()->subDays(15),
        ]);

        // 4. Closed Trade 3 (Profit Buy)
        $t3 = Trade::create([
            'pair' => 'XAUUSD',
            'type' => 'buy',
            'entry_price' => 2330.00000,
            'stop_loss' => 2320.00000,
            'take_profit' => 2355.00000,
            'lot_size' => 0.50,
            'profit_loss' => 8000000.00,
            'status' => 'closed',
            'created_at' => Carbon::now()->subDays(10),
        ]);
        $t3->balanceTransaction()->update([
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10),
        ]);

        // 5. Withdrawal (Tarik Saldo)
        BalanceTransaction::create([
            'type' => 'withdrawal',
            'amount' => 5000000.00,
            'description' => 'Penarikan Profit Berkala (WD #1)',
            'created_at' => Carbon::now()->subDays(8),
        ]);

        // 6. Closed Trade 4 (Profit Sell)
        $t4 = Trade::create([
            'pair' => 'AUDUSD',
            'type' => 'sell',
            'entry_price' => 0.66500,
            'stop_loss' => 0.67000,
            'take_profit' => 0.65500,
            'lot_size' => 1.50,
            'profit_loss' => 3200000.00,
            'status' => 'closed',
            'created_at' => Carbon::now()->subDays(5),
        ]);
        $t4->balanceTransaction()->update([
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);

        // 7. Open Trade (Floating Position)
        Trade::create([
            'pair' => 'USDJPY',
            'type' => 'buy',
            'entry_price' => 156.20000,
            'stop_loss' => 155.00000,
            'take_profit' => 158.50000,
            'lot_size' => 2.00,
            'profit_loss' => 0.00,
            'status' => 'open',
            'created_at' => Carbon::now()->subDays(1),
        ]);
    }
}
