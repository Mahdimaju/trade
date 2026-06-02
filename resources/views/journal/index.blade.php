<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neo Trade Journal - Premium Trading Portfolio</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (Vite Entry or CDN Fallback to guarantee styles render instantly) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            background-color: #0b0f19;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .neon-border-green {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .neon-border-blue {
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen pb-12 selection:bg-indigo-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-40 w-full glass-panel border-b border-slate-800/80 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-chart-line text-lg text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">NEO TRADE</h1>
                <p class="text-[10px] font-semibold tracking-wider text-indigo-400 uppercase">Premium Trading Ledger</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Portofolio Aktif
            </span>
            <div class="h-8 w-[1px] bg-slate-800"></div>
            <div class="text-right hidden sm:block">
                <p class="text-xs text-slate-400 font-medium">Selamat Datang</p>
                <p class="text-sm font-semibold text-slate-200">Trader Profesional</p>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-8">

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3 animate-fade-in">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 space-y-1">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span class="text-sm font-semibold">Ada kesalahan input:</span>
                </div>
                <ul class="list-disc pl-8 text-xs font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Action Panel -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 glass-panel p-6 rounded-2xl">
            <div>
                <h2 class="text-lg font-bold text-white">Ringkasan Finansial</h2>
                <p class="text-xs text-slate-400">Pantau pertumbuhan ekuitas, win rate, dan rasio risiko jurnal Anda secara berkala.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="toggleModal('transaction-modal')" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700/80 border border-slate-700 transition duration-200 flex items-center gap-2 text-sm font-semibold text-slate-200 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-wallet text-indigo-400"></i>
                    Top Up / Withdraw
                </button>
                <button onclick="toggleModal('trade-modal')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 transition duration-200 flex items-center gap-2 text-sm font-semibold text-white shadow-lg shadow-indigo-600/15 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Catat Trade Baru
                </button>
            </div>
        </div>

        <!-- 1. DASHBOARD UTAMA (METRIC CARDS) -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- CARD 1: SALDO SAAT INI (EQUITY) -->
            <div class="glass-panel p-6 rounded-2xl relative overflow-hidden flex flex-col justify-between h-40 group hover:border-indigo-500/30 transition duration-300">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 rounded-full blur-xl group-hover:bg-indigo-500/10 transition duration-300"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold tracking-wider text-slate-400 uppercase">Ekuitas Jurnal (Equity)</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-scale-balanced text-indigo-400 text-xs"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-bold tracking-tight text-white mt-4 @if($equity < 0) text-rose-400 @elseif($equity > 0) text-emerald-400 @endif">
                        Rp {{ number_format($equity, 2, ',', '.') }}
                    </h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] text-slate-400 font-medium">Top Up: Rp {{ number_format($totalTopup, 0, ',', '.') }}</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-700"></span>
                        <span class="text-[10px] text-slate-400 font-medium">WD: Rp {{ number_format($totalWithdrawal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- CARD 2: WIN RATE % -->
            <div class="glass-panel p-6 rounded-2xl relative overflow-hidden flex flex-col justify-between h-40 group hover:border-emerald-500/30 transition duration-300">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl group-hover:bg-emerald-500/10 transition duration-300"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold tracking-wider text-slate-400 uppercase">Win Rate %</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-trophy text-emerald-400 text-xs"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black tracking-tight text-emerald-400 mt-3">
                        {{ number_format($winRate, 2, ',', '.') }}%
                    </h3>
                    <div class="w-full bg-slate-800 rounded-full h-1.5 mt-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $winRate }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">
                        {{ $winningTradesCount }} Profit dari {{ $closedTradesCount }} Posisi Closed
                    </p>
                </div>
            </div>

            <!-- CARD 3: TOTAL TRADES -->
            <div class="glass-panel p-6 rounded-2xl relative overflow-hidden flex flex-col justify-between h-40 group hover:border-violet-500/30 transition duration-300">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-violet-500/5 rounded-full blur-xl group-hover:bg-violet-500/10 transition duration-300"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold tracking-wider text-slate-400 uppercase">Total Posisi</span>
                    <div class="w-8 h-8 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-folder-open text-violet-400 text-xs"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-white mt-4">
                        {{ $totalTrades }} <span class="text-xs text-slate-400 font-semibold">Posisi</span>
                    </h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-500/10 border border-indigo-500/20 text-indigo-400">
                            {{ $openTradesCount }} Open
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                            {{ $closedTradesCount }} Closed
                        </span>
                    </div>
                </div>
            </div>

            <!-- CARD 4: AVERAGE RISK REWARD RATIO -->
            <div class="glass-panel p-6 rounded-2xl relative overflow-hidden flex flex-col justify-between h-40 group hover:border-amber-500/30 transition duration-300">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/5 rounded-full blur-xl group-hover:bg-amber-500/10 transition duration-300"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold tracking-wider text-slate-400 uppercase">Average RRR</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-calculator text-amber-400 text-xs"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-amber-400 mt-4">
                        1 : {{ number_format($avgRrr, 2, ',', '.') }}
                    </h3>
                    <p class="text-[10px] text-slate-400 mt-2 font-medium">
                        Rasio Risk/Reward Rata-rata Portofolio
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. CHART PERFORMA (EQUITY CURVE) -->
        <section class="glass-panel p-6 rounded-2xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-area text-indigo-400"></i>
                        Grafik Ekuitas Jurnal (Equity Curve)
                    </h3>
                    <p class="text-xs text-slate-400">Perkembangan kumulatif portofolio Anda secara kronologis dari setiap transaksi.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 font-medium">Hasil Bersih Trade:</span>
                    <p class="text-sm font-semibold @if($totalProfitLoss >= 0) text-emerald-400 @else text-rose-400 @endif">
                        {{ $totalProfitLoss >= 0 ? '+' : '' }}Rp {{ number_format($totalProfitLoss, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="w-full h-80">
                <canvas id="equityChart"></canvas>
            </div>
        </section>

        <!-- 3. DATA TABLES (TRADES JOURNAL & BALANCE TRANSACTIONS) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- TABLE: TRADES JOURNAL (Takes 2/3 of grid on desktop) -->
            <div class="lg:col-span-2 glass-panel rounded-2xl overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-800/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-book text-indigo-400"></i>
                            Buku Jurnal Trading
                        </h3>
                        <p class="text-xs text-slate-400">Daftar lengkap transaksi posisi trading Anda.</p>
                    </div>
                </div>

                <div class="overflow-x-auto w-full hide-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800/60 bg-slate-900/30 text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                <th class="p-4">Pair</th>
                                <th class="p-4">Tipe</th>
                                <th class="p-4">Entry / Lots</th>
                                <th class="p-4">SL / TP</th>
                                <th class="p-4">RRR</th>
                                <th class="p-4">Profit / Loss</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-xs">
                            @forelse($trades as $trade)
                                <tr class="hover:bg-slate-900/40 transition duration-150">
                                    <!-- Pair -->
                                    <td class="p-4">
                                        <div class="font-bold text-slate-100">{{ strtoupper($trade->pair) }}</div>
                                        <div class="text-[10px] text-slate-500">{{ $trade->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <!-- Type -->
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded font-bold text-[10px] uppercase {{ $trade->type === 'buy' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/25' : 'bg-rose-500/10 text-rose-400 border border-rose-500/25' }}">
                                            {{ $trade->type }}
                                        </span>
                                    </td>
                                    <!-- Entry & Lots -->
                                    <td class="p-4">
                                        <div class="font-medium text-slate-300">{{ number_format($trade->entry_price, 5) }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ number_format($trade->lot_size, 2) }} Lot</div>
                                    </td>
                                    <!-- SL / TP -->
                                    <td class="p-4">
                                        <div class="text-rose-400/90 font-medium">SL: {{ number_format($trade->stop_loss, 5) }}</div>
                                        <div class="text-emerald-400/90 font-medium">TP: {{ number_format($trade->take_profit, 5) }}</div>
                                    </td>
                                    <!-- RRR -->
                                    <td class="p-4 font-semibold text-slate-300">
                                        1 : {{ number_format($trade->risk_reward_ratio, 2) }}
                                    </td>
                                    <!-- Profit / Loss -->
                                    <td class="p-4 font-bold">
                                        @if($trade->status === 'closed')
                                            <span class="{{ $trade->profit_loss > 0 ? 'text-emerald-400' : ($trade->profit_loss < 0 ? 'text-rose-400' : 'text-slate-400') }}">
                                                {{ $trade->profit_loss >= 0 ? '+' : '' }}Rp {{ number_format($trade->profit_loss, 2, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-slate-500 italic font-normal">Floating</span>
                                        @endif
                                    </td>
                                    <!-- Status -->
                                    <td class="p-4">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $trade->status === 'open' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/25' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $trade->status === 'open' ? 'bg-indigo-400 animate-pulse' : 'bg-slate-400' }}"></span>
                                            {{ ucfirst($trade->status) }}
                                        </span>
                                    </td>
                                    <!-- Action Buttons -->
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2.5">
                                            <!-- Edit Button -->
                                            <button 
                                                onclick="openEditTradeModal({{ json_encode($trade) }})"
                                                class="w-7 h-7 rounded bg-slate-800 hover:bg-slate-700/80 border border-slate-700 flex items-center justify-center transition cursor-pointer"
                                                title="Edit Trade"
                                            >
                                                <i class="fa-solid fa-pen text-indigo-400 text-[10px]"></i>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('trades.destroy', $trade->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan trade ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded bg-slate-800 hover:bg-rose-500/20 border border-slate-700 hover:border-rose-500/30 flex items-center justify-center transition cursor-pointer" title="Hapus Trade">
                                                    <i class="fa-solid fa-trash text-rose-400 text-[10px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-slate-500 italic">
                                        <div class="flex flex-col items-center justify-center py-6">
                                            <i class="fa-solid fa-receipt text-3xl text-slate-700 mb-3"></i>
                                            <span>Belum ada catatan trade. Mulai catat untuk melihat performa Anda!</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLE: RECENT TRANSACTIONS (Takes 1/3 of grid on desktop) -->
            <div class="glass-panel rounded-2xl overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-800/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-history text-indigo-400"></i>
                            Mutasi Transaksi
                        </h3>
                        <p class="text-xs text-slate-400">Aktivitas top-up, withdrawal & hasil trade.</p>
                    </div>
                </div>

                <div class="overflow-y-auto max-h-[450px] hide-scrollbar">
                    <ul class="divide-y divide-slate-800/40">
                        @forelse($txs as $tx)
                            <li class="p-4 hover:bg-slate-900/20 transition flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center border text-xs
                                        @if($tx->type === 'topup') bg-indigo-500/10 text-indigo-400 border-indigo-500/20
                                        @elseif($tx->type === 'withdrawal') bg-rose-500/10 text-rose-400 border-rose-500/20
                                        @else bg-emerald-500/10 text-emerald-400 border-emerald-500/20 @endif">
                                        @if($tx->type === 'topup')
                                            <i class="fa-solid fa-arrow-down-long"></i>
                                        @elseif($tx->type === 'withdrawal')
                                            <i class="fa-solid fa-arrow-up-long"></i>
                                        @else
                                            <i class="fa-solid fa-scale-balanced"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-200 leading-snug">
                                            {{ $tx->description }}
                                        </p>
                                        <span class="text-[10px] text-slate-500 font-medium">
                                            {{ $tx->created_at->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold leading-none
                                        @if($tx->type === 'topup') text-indigo-400
                                        @elseif($tx->type === 'withdrawal') text-rose-400
                                        @else
                                            {{ $tx->amount >= 0 ? 'text-emerald-400' : 'text-rose-400' }}
                                        @endif">
                                        @if($tx->type === 'topup') + @elseif($tx->type === 'withdrawal') - @else {{ $tx->amount >= 0 ? '+' : '' }} @endif
                                        Rp {{ number_format(abs($tx->amount), 2, ',', '.') }}
                                    </span>

                                    <!-- Only allow deleting deposit or withdrawal manually, trade results are system handled -->
                                    @if($tx->type !== 'trade_result')
                                        <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-500 hover:text-rose-400 p-1 rounded transition cursor-pointer">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="p-8 text-center text-slate-500 italic text-xs">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-wallet text-2xl text-slate-700 mb-2"></i>
                                    <span>Belum ada riwayat transaksi.</span>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- ============================================== -->
    <!-- MODAL: INPUT TRADE BARU (TAMBAH TRADE)          -->
    <!-- ============================================== -->
    <div id="trade-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden transition-all duration-300 opacity-0">
        <div class="w-full max-w-xl glass-panel rounded-2xl border border-slate-800 shadow-2xl p-6 relative flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-800/80">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-chart-line text-indigo-400"></i>
                    <h3 class="text-base font-bold text-white">Catat Trade Baru</h3>
                </div>
                <button onclick="toggleModal('trade-modal')" class="text-slate-400 hover:text-white transition p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Form Body -->
            <form action="{{ route('trades.store') }}" method="POST" class="space-y-4 pt-4 overflow-y-auto pr-1 hide-scrollbar">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Pair -->
                    <div>
                        <label for="pair" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Trading Pair / Simbol</label>
                        <input type="text" name="pair" id="pair" placeholder="contoh: EURUSD, BTCUSDT, XAUUSD" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-bold">
                    </div>

                    <!-- Position Type -->
                    <div>
                        <label for="type" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Tipe Posisi</label>
                        <select name="type" id="type" required
                                class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-semibold">
                            <option value="buy">BUY (Posisi Panjang)</option>
                            <option value="sell">SELL (Posisi Pendek)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <!-- Entry Price -->
                    <div>
                        <label for="entry_price" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Harga Entry</label>
                        <input type="number" step="any" name="entry_price" id="entry_price" placeholder="1.08500" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium">
                    </div>

                    <!-- Stop Loss (SL) -->
                    <div>
                        <label for="stop_loss" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Stop Loss (SL)</label>
                        <input type="number" step="any" name="stop_loss" id="stop_loss" placeholder="1.08000" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium text-rose-300">
                    </div>

                    <!-- Take Profit (TP) -->
                    <div>
                        <label for="take_profit" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Take Profit (TP)</label>
                        <input type="number" step="any" name="take_profit" id="take_profit" placeholder="1.09500" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium text-emerald-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Lot Size -->
                    <div>
                        <label for="lot_size" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Lot Size</label>
                        <input type="number" step="0.01" name="lot_size" id="lot_size" placeholder="0.10" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Status Trade</label>
                        <select name="status" id="status" onchange="toggleProfitLossField(this.value, 'profit-loss-container')" required
                                class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-semibold">
                            <option value="open">Open (Floating)</option>
                            <option value="closed">Closed (Selesai)</option>
                        </select>
                    </div>
                </div>

                <!-- Profit & Loss (Dynamic) -->
                <div id="profit-loss-container" class="hidden">
                    <label for="profit_loss" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Profit / Loss (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">Rp</span>
                        <input type="number" step="0.01" name="profit_loss" id="profit_loss" placeholder="Gunakan tanda minus (-) untuk rugi"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-bold">
                    </div>
                    <span class="text-[10px] text-slate-500 mt-1 block">Tuliskan nominal profit Anda. Jika rugi, tambahkan minus di awal (misal: -150000).</span>
                </div>

                <!-- Footer buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800/80">
                    <button type="button" onclick="toggleModal('trade-modal')" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 text-xs font-semibold cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/10 cursor-pointer">Simpan Posisi</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ============================================== -->
    <!-- MODAL: EDIT TRADE EXISTING                      -->
    <!-- ============================================== -->
    <div id="edit-trade-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden transition-all duration-300 opacity-0">
        <div class="w-full max-w-xl glass-panel rounded-2xl border border-slate-800 shadow-2xl p-6 relative flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-800/80">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-pen-to-square text-indigo-400"></i>
                    <h3 class="text-base font-bold text-white">Perbarui Detail Trade</h3>
                </div>
                <button onclick="toggleModal('edit-trade-modal')" class="text-slate-400 hover:text-white transition p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Form Body -->
            <form id="edit-trade-form" action="" method="POST" class="space-y-4 pt-4 overflow-y-auto pr-1 hide-scrollbar">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Pair -->
                    <div>
                        <label for="edit_pair" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Trading Pair / Simbol</label>
                        <input type="text" name="pair" id="edit_pair" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-bold">
                    </div>

                    <!-- Position Type -->
                    <div>
                        <label for="edit_type" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Tipe Posisi</label>
                        <select name="type" id="edit_type" required
                                class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-semibold">
                            <option value="buy">BUY (Posisi Panjang)</option>
                            <option value="sell">SELL (Posisi Pendek)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <!-- Entry Price -->
                    <div>
                        <label for="edit_entry_price" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Harga Entry</label>
                        <input type="number" step="any" name="entry_price" id="edit_entry_price" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium">
                    </div>

                    <!-- Stop Loss (SL) -->
                    <div>
                        <label for="edit_stop_loss" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Stop Loss (SL)</label>
                        <input type="number" step="any" name="stop_loss" id="edit_stop_loss" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium text-rose-300">
                    </div>

                    <!-- Take Profit (TP) -->
                    <div>
                        <label for="edit_take_profit" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Take Profit (TP)</label>
                        <input type="number" step="any" name="take_profit" id="edit_take_profit" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium text-emerald-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Lot Size -->
                    <div>
                        <label for="edit_lot_size" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Lot Size</label>
                        <input type="number" step="0.01" name="lot_size" id="edit_lot_size" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="edit_status" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Status Trade</label>
                        <select name="status" id="edit_status" onchange="toggleProfitLossField(this.value, 'edit-profit-loss-container')" required
                                class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-semibold">
                            <option value="open">Open (Floating)</option>
                            <option value="closed">Closed (Selesai)</option>
                        </select>
                    </div>
                </div>

                <!-- Profit & Loss (Dynamic) -->
                <div id="edit-profit-loss-container" class="hidden">
                    <label for="edit_profit_loss" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Profit / Loss (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">Rp</span>
                        <input type="number" step="0.01" name="profit_loss" id="edit_profit_loss"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-bold">
                    </div>
                    <span class="text-[10px] text-slate-500 mt-1 block">Tuliskan nominal profit Anda. Jika rugi, tambahkan minus di awal (misal: -150000).</span>
                </div>

                <!-- Footer buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800/80">
                    <button type="button" onclick="toggleModal('edit-trade-modal')" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 text-xs font-semibold cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/10 cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ============================================== -->
    <!-- MODAL: INPUT TRANSAKSI SALDO (TOPUP / WD)        -->
    <!-- ============================================== -->
    <div id="transaction-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden transition-all duration-300 opacity-0">
        <div class="w-full max-w-md glass-panel rounded-2xl border border-slate-800 shadow-2xl p-6 relative flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-800/80">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-wallet text-indigo-400"></i>
                    <h3 class="text-base font-bold text-white">Transaksi Saldo Jurnal</h3>
                </div>
                <button onclick="toggleModal('transaction-modal')" class="text-slate-400 hover:text-white transition p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Form Body -->
            <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4 pt-4">
                @csrf
                
                <!-- Type Selection Buttons (Topup / Withdrawal) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase mb-2">Tipe Transaksi</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center p-3 rounded-xl border border-slate-800 bg-slate-900/60 cursor-pointer select-none hover:bg-slate-900 transition">
                            <input type="radio" name="type" value="topup" checked class="sr-only peer" onchange="updateTransactionTheme(this.value)">
                            <div class="peer-checked:text-indigo-400 flex items-center gap-2 text-xs font-bold text-slate-400">
                                <i class="fa-solid fa-arrow-down text-[10px]"></i>
                                Top-Up (Deposit)
                            </div>
                            <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-indigo-500 pointer-events-none"></div>
                        </label>
                        <label class="relative flex items-center justify-center p-3 rounded-xl border border-slate-800 bg-slate-900/60 cursor-pointer select-none hover:bg-slate-900 transition">
                            <input type="radio" name="type" value="withdrawal" class="sr-only peer" onchange="updateTransactionTheme(this.value)">
                            <div class="peer-checked:text-rose-400 flex items-center gap-2 text-xs font-bold text-slate-400">
                                <i class="fa-solid fa-arrow-up text-[10px]"></i>
                                Withdrawal (Tarik)
                            </div>
                            <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-rose-500 pointer-events-none"></div>
                        </label>
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Nominal (Rupiah)</label>
                    <div class="relative">
                        <span id="amount-prefix" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-indigo-400">Rp</span>
                        <input type="number" step="0.01" name="amount" id="amount" placeholder="10000000" required
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-bold">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Keterangan / Catatan</label>
                    <input type="text" name="description" id="description" placeholder="contoh: Deposit Modal Awal, WD Profit Minggu ini"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition font-medium">
                </div>

                <!-- Footer buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800/80">
                    <button type="button" onclick="toggleModal('transaction-modal')" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 text-xs font-semibold cursor-pointer">Batal</button>
                    <button type="submit" id="tx-submit-btn" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/10 cursor-pointer">Kirim Transaksi</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ============================================== -->
    <!-- SCRIPTS: INTERACTIVE & METRIC CHART SCRIPT      -->
    <!-- ============================================== -->
    <script>
        // Modal toggling with animatable fades
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                }, 10);
            } else {
                modal.classList.add('opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Show profit/loss only if closed trade status selected
        function toggleProfitLossField(status, fieldContainerId) {
            const container = document.getElementById(fieldContainerId);
            const inputField = container.querySelector('input');
            if (status === 'closed') {
                container.classList.remove('hidden');
                inputField.setAttribute('required', 'required');
            } else {
                container.classList.add('hidden');
                inputField.removeAttribute('required');
                inputField.value = '';
            }
        }

        // Transaction popup styling based on deposit / withdrawal selection
        function updateTransactionTheme(value) {
            const prefix = document.getElementById('amount-prefix');
            const submitBtn = document.getElementById('tx-submit-btn');
            
            if (value === 'withdrawal') {
                prefix.classList.remove('text-indigo-400');
                prefix.classList.add('text-rose-400');
                
                submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-500', 'shadow-indigo-600/10');
                submitBtn.classList.add('bg-rose-600', 'hover:bg-rose-500', 'shadow-rose-600/10');
            } else {
                prefix.classList.remove('text-rose-400');
                prefix.classList.add('text-indigo-400');
                
                submitBtn.classList.remove('bg-rose-600', 'hover:bg-rose-500', 'shadow-rose-600/10');
                submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-500', 'shadow-indigo-600/10');
            }
        }

        // Handle populate data to edit modal
        function openEditTradeModal(trade) {
            const form = document.getElementById('edit-trade-form');
            // Update form action URL dynamically
            form.action = `/trades/${trade.id}`;
            
            // Populate fields
            document.getElementById('edit_pair').value = trade.pair;
            document.getElementById('edit_type').value = trade.type;
            document.getElementById('edit_entry_price').value = trade.entry_price;
            document.getElementById('edit_stop_loss').value = trade.stop_loss;
            document.getElementById('edit_take_profit').value = trade.take_profit;
            document.getElementById('edit_lot_size').value = trade.lot_size;
            document.getElementById('edit_status').value = trade.status;
            
            // Trigger dynamic field behavior
            toggleProfitLossField(trade.status, 'edit-profit-loss-container');
            
            if (trade.status === 'closed') {
                document.getElementById('edit_profit_loss').value = trade.profit_loss;
            } else {
                document.getElementById('edit_profit_loss').value = '';
            }
            
            // Open the modal
            toggleModal('edit-trade-modal');
        }

        // Setup performance line chart (Chart.js)
        document.addEventListener('DOMContentLoaded', function () {
            // Get data from server injections
            const labels = @json($chartLabels);
            const dataPoints = @json($chartData);

            const ctx = document.getElementById('equityChart').getContext('2d');
            
            // Define vibrant custom gradients for chart lines
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.00)');

            const equityChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Equity (Rp)',
                        data: dataPoints,
                        borderColor: '#6366f1',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.25,
                        fill: true,
                        backgroundColor: gradient,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#0f172a',
                            titleColor: '#94a3b8',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255,255,255,0.06)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            bodyFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.03)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    family: 'Plus Jakarta Sans'
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.03)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    family: 'Plus Jakarta Sans'
                                },
                                callback: function(value, index, values) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
