<?php
// admin/dashboard.php
session_start();

if (file_exists('Koneksi.php')) {
    include 'Koneksi.php';
} else {
    die("Error: File Koneksi.php tidak ditemukan!");
}
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Data Default
$totalMenu = 0; $totalTransaksi = 0; $omsetHariIni = 0; 
$totalPending = 0; 
$labelGrafik = []; $dataGrafik = [];

if (isset($conn)) {
    try {
        $totalMenu = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $totalTransaksi = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        
        $sqlToday = "SELECT SUM(total_price) FROM orders WHERE status = 'success' AND DATE(order_date) = CURDATE()";
        $omsetHariIni = $conn->query($sqlToday)->fetchColumn() ?: 0;
        
        $totalPending = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();

        $sqlGrafik = "SELECT DATE(order_date) as tgl, SUM(total_price) as total FROM orders WHERE status = 'success' GROUP BY DATE(order_date) ORDER BY tgl ASC LIMIT 7";
        $stmtGrafik = $conn->query($sqlGrafik);
        while ($row = $stmtGrafik->fetch(PDO::FETCH_ASSOC)) {
            $labelGrafik[] = date('d/M', strtotime($row['tgl'])); 
            $dataGrafik[] = $row['total'];
        }
    } catch (Exception $e) {}
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Owner</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> 
        body { font-family: 'Plus+Jakarta Sans', sans-serif; background-color: #F8FAFC; } 
        ::-webkit-scrollbar { width: 0px; background: transparent; }
    </style>
</head>
<body class="text-slate-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-72 bg-white m-4 rounded-3xl shadow-xl flex flex-col hidden md:flex border border-slate-100">
        <div class="h-24 flex items-center justify-center border-b border-dashed border-slate-200">
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">COFFEE<span class="text-amber-600">ROOM.</span></h1>
        </div>

        <nav class="flex-1 px-6 py-8 space-y-4">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2">Menu Utama</p>
            
            <a href="dashboard.php" class="flex items-center px-4 py-3.5 bg-amber-500 text-white rounded-2xl shadow-lg shadow-amber-200 transition-all transform hover:scale-[1.02]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-bold">Overview</span>
            </a>

            <a href="menu/index.php" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-semibold">Produk</span>
            </a>

            <a href="transaksi/trindex.php" class="flex items-center justify-between px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="font-semibold">Transaksi</span>
                </div>
                <?php if($totalPending > 0): ?>
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-md animate-bounce">
                        <?= $totalPending ?>
                    </span>
                <?php endif; ?>
            </a>

            <!-- LINK BARU: MEMBER & PROMO -->
            <a href="member/member_index.php" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-semibold">Member & Promo</span>
            </a>

        </nav>

        <div class="p-6">
            <a href="admin_logout.php" class="flex items-center justify-center w-full py-3 text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 rounded-2xl transition">
                Logout
            </a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8">
        
        <header class="flex flex-col md:flex-row justify-between items-center mb-10 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Halo, Habib! 👋</h2>
                <p class="text-slate-400 mt-1">Ini ringkasan bisnismu hari ini.</p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <div id="hariTanggal" class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1"></div>
                <div id="jamDigital" class="text-4xl font-mono font-bold text-amber-500"></div>
            </div>
        </header>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-50 hover:border-amber-100 transition duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-amber-50 text-amber-500 rounded-2xl group-hover:bg-amber-500 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-lg">Hari Ini</span>
                </div>
                <h3 class="text-4xl font-bold text-slate-800 mb-1">Rp <?= number_format($omsetHariIni, 0, ',', '.') ?></h3>
                <p class="text-slate-400 text-sm font-medium">Total Pemasukan</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-50 hover:border-blue-100 transition duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-500 rounded-2xl group-hover:bg-blue-500 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-bold text-slate-800 mb-1"><?= $totalTransaksi ?></h3>
                <p class="text-slate-400 text-sm font-medium">Total Pesanan Masuk</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-50 hover:border-purple-100 transition duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 text-purple-500 rounded-2xl group-hover:bg-purple-500 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-bold text-slate-800 mb-1"><?= $totalMenu ?></h3>
                <p class="text-slate-400 text-sm font-medium">Varian Menu</p>
            </div>
        </div>

        <!-- GRAFIK -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800">Analisis Penjualan</h3>
                <select class="text-sm border-none bg-slate-50 rounded-lg px-3 py-1 text-slate-500 font-bold focus:ring-0">
                    <option>7 Hari Terakhir</option>
                </select>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

    </main>

    <script>
        function updateWaktu() {
            const now = new Date();
            document.getElementById('hariTanggal').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('jamDigital').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
        }
        setInterval(updateWaktu, 1000); updateWaktu();

        const ctx = document.getElementById('salesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(245, 158, 11, 0.2)');
        gradient.addColorStop(1, 'rgba(245, 158, 11, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labelGrafik) ?>,
                datasets: [{
                    label: 'Income',
                    data: <?= json_encode($dataGrafik) ?>,
                    borderColor: '#f59e0b',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f59e0b',
                    pointRadius: 6,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f1f5f9' }, ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" } } },
                    x: { grid: { display: false }, ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" } } }
                }
            }
        });
    </script>

</body>
</html>