<?php
// admin/dashboard.php
session_start();

if (file_exists('Koneksi.php')) {
    include 'Koneksi.php';
} else {
    die("Error: File Koneksi.php tidak ditemukan!");
}

$totalMenu = 0;
$totalUser = 1;
$omset = 0;
$totalTransaksi = 0;

if (isset($conn)) {
    try {
        $totalMenu = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $cekTabel = $conn->query("SHOW TABLES LIKE 'orders'")->rowCount();
        if ($cekTabel > 0) {
            $omset = $conn->query("SELECT SUM(total_price) FROM orders WHERE status = 'success'")->fetchColumn() ?: 0;
            $totalTransaksi = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        }
    } catch (Exception $e) {}
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm">
        <div class="h-20 flex items-center px-8 border-b border-gray-100">
            <h1 class="text-xl font-bold tracking-tight text-gray-800">COFFEE<span class="text-amber-600">ROOM</span></h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- Home -->
            <a href="dashboard.php" class="flex items-center px-4 py-3 bg-amber-50 text-amber-700 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-bold">Home</span>
            </a>

            <!-- Products -->
            <a href="menu/index.php" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-amber-600 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-medium">Products</span>
            </a>

            <!-- Transaksi (LINK SUDAH DIPERBAIKI) -->
            <a href="transaksi/trindex.php" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-amber-600 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="font-medium">Transaksi</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-gray-100">
            <a href="logout.php" class="flex items-center text-sm text-gray-500 hover:text-red-600 transition">
                Logout
            </a>
        </div>
    </aside>

    <!-- KONTEN -->
    <main class="flex-1 overflow-y-auto p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Overview</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100 border-l-4 border-l-green-500">
                <p class="text-gray-500 text-sm">Total Omset</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp <?= number_format($omset, 0, ',', '.') ?></h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100 border-l-4 border-l-blue-500">
                <p class="text-gray-500 text-sm">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= $totalTransaksi ?> Pesanan</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-amber-100 border-l-4 border-l-amber-500">
                <p class="text-gray-500 text-sm">Total Menu</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= $totalMenu ?> Items</h3>
            </div>
        </div>
    </main>

</body>
</html>