<?php
// lacak.php
include 'admin/Koneksi.php';

// Cek ID di URL
$id = $_GET['id'] ?? null;
$order = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Biar responsif di HP -->
    <title>Status Pesanan #<?= $id ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Auto Refresh setiap 10 detik biar update sendiri -->
    <meta http-equiv="refresh" content="10"> 
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-[#1a1a1a] rounded-2xl shadow-2xl border border-gray-800 p-8 text-center relative overflow-hidden">
        
        <!-- Header -->
        <h2 class="text-gray-400 text-sm uppercase tracking-widest mb-2">Order Tracking</h2>
        <h1 class="text-3xl font-bold text-[#C69C6D] mb-8">#ORD-<?= $id ?></h1>

        <?php if ($order): ?>
            
            <!-- STATUS DISPLAY -->
            <div class="mb-8">
                <?php if ($order['status'] == 'pending'): ?>
                    <!-- Tampilan Kalau Masih PENDING -->
                    <div class="w-20 h-20 bg-yellow-500/20 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <span class="text-4xl">⏳</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Sedang Disiapkan</h3>
                    <p class="text-gray-400">Barista kami sedang meracik kopimu. Mohon tunggu sebentar.</p>
                
                <?php elseif ($order['status'] == 'success'): ?>
                    <!-- Tampilan Kalau Sudah SUKSES -->
                    <div class="w-20 h-20 bg-green-500/20 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">✅</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Siap Dinikmati!</h3>
                    <p class="text-gray-400">Pesananmu sudah selesai. Silakan ambil di counter / tunggu diantar.</p>
                
                <?php else: ?>
                    <!-- Cancelled -->
                    <div class="w-20 h-20 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">❌</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Dibatalkan</h3>
                <?php endif; ?>
            </div>

            <!-- Detail Pesanan -->
            <div class="bg-black/30 rounded-xl p-6 text-left border border-gray-700">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-bold"><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">Total Bayar</span>
                    <span class="font-bold text-[#C69C6D]">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Waktu Pesan</span>
                    <span class="text-sm"><?= date('H:i d/M/Y', strtotime($order['order_date'])) ?></span>
                </div>
            </div>

            <div class="mt-8">
                <a href="csindex.php" class="text-gray-500 hover:text-white text-sm transition">
                    &larr; Kembali ke Menu Utama
                </a>
            </div>

        <?php else: ?>
            <!-- Kalau ID Salah -->
            <div class="py-10">
                <div class="text-4xl mb-4">🔍</div>
                <h3 class="text-xl font-bold">Pesanan Tidak Ditemukan</h3>
                <a href="csindex.php" class="block mt-6 text-[#C69C6D]">Kembali</a>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>