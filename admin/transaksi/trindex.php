<?php
// FILE: admin/transaksi/trindex.php
include '../Koneksi.php';

// 1. Ambil Data Transaksi dari Database (Urut dari yang terbaru)
// Kita pakai TRY-CATCH biar kalau tabel belum ada, gak langsung error blank
try {
    $query = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
    $orders = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $orders = []; // Kalau error, anggap kosong dulu
}

// 2. Fitur Update Status (Saat tombol 'Proses' diklik)
if (isset($_GET['terima'])) {
    $id = $_GET['terima'];
    
    // Ubah status jadi 'success'
    $conn->query("UPDATE orders SET status='success' WHERE id=$id");
    
    // Refresh halaman
    echo "<script>alert('Pesanan selesai diproses!'); window.location='trindex.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR ADMIN -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm">
        <div class="h-20 flex items-center px-8 border-b border-gray-100">
            <h1 class="text-xl font-bold tracking-tight text-gray-800">COFFEE<span class="text-amber-600">ROOM</span></h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- Link ke Dashboard -->
            <a href="../dashboard.php" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-amber-600 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Home</span>
            </a>
            <!-- Link ke Menu Produk -->
            <a href="../menu/index.php" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-amber-600 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-medium">Products</span>
            </a>
            <!-- Link Transaksi (SEDANG AKTIF) -->
            <a href="trindex.php" class="flex items-center px-4 py-3 bg-amber-50 text-amber-700 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="font-bold">Transaksi</span>
            </a>
        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 overflow-y-auto p-8">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Pesanan Masuk</h2>
                <p class="text-sm text-gray-500">Pantau orderan dari pelanggan secara real-time.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded shadow text-sm font-bold border">
                Total: <?= count($orders) ?> Order
            </div>
        </div>

        <!-- Tabel Daftar Pesanan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase">
                    <tr>
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Nama Pelanggan</th>
                        <th class="py-4 px-6">Total Bayar</th>
                        <th class="py-4 px-6">Waktu Order</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php if(count($orders) > 0): ?>
                        <?php foreach($orders as $o): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-mono text-gray-400">#<?= $o['id'] ?></td>
                            <td class="py-4 px-6 font-bold text-gray-800"><?= htmlspecialchars($o['customer_name']) ?></td>
                            <td class="py-4 px-6 font-mono text-[#C69C6D] font-bold">Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                            <td class="py-4 px-6 text-gray-500"><?= $o['order_date'] ?></td>
                            
                            <!-- Label Status -->
                            <td class="py-4 px-6">
                                <?php if($o['status'] == 'pending'): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                        ⏳ Menunggu
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        ✅ Selesai
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Tombol Aksi -->
                            <td class="py-4 px-6 text-right">
                                <?php if($o['status'] == 'pending'): ?>
                                    <a href="trindex.php?terima=<?= $o['id'] ?>" onclick="return confirm('Sudah dibuatkan kopinya?')" 
                                       class="bg-black hover:bg-gray-800 text-white text-xs font-bold py-2 px-4 rounded shadow transition">
                                        Proses
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs italic">Done</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400 italic bg-gray-50">
                                Belum ada pesanan masuk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>