<?php
// admin/transaksi/trindex.php
include '../Koneksi.php';

try {
    $query = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
    $orders = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Hitung Pending untuk Notifikasi
    $totalPending = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
} catch (Exception $e) {
    $orders = []; $totalPending = 0;
}

if (isset($_GET['terima'])) {
    $id = $_GET['terima'];
    $conn->query("UPDATE orders SET status='success' WHERE id=$id");
    echo "<script>alert('Pesanan selesai diproses!'); window.location='trindex.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi - Admin Coffee Room</title>
    <!-- Font Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> 
        body { font-family: 'Plus+Jakarta Sans', sans-serif; background-color: #F8FAFC; } 
        ::-webkit-scrollbar { width: 0px; background: transparent; }
    </style>
</head>
<body class="text-slate-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR (Modern) -->
    <aside class="w-72 bg-white m-4 rounded-3xl shadow-xl flex flex-col hidden md:flex border border-slate-100">
        <div class="h-24 flex items-center justify-center border-b border-dashed border-slate-200">
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">COFFEE<span class="text-amber-600">ROOM.</span></h1>
        </div>

        <nav class="flex-1 px-6 py-8 space-y-4">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2">Menu Utama</p>
            
            <a href="../dashboard.php" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-semibold">Overview</span>
            </a>

            <a href="../menu/index.php" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-semibold">Produk</span>
            </a>

            <!-- Transaksi Aktif -->
            <a href="trindex.php" class="flex items-center justify-between px-4 py-3.5 bg-amber-500 text-white rounded-2xl shadow-lg shadow-amber-200 transition-all transform hover:scale-[1.02]">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="font-bold">Transaksi</span>
                </div>
                <?php if($totalPending > 0): ?>
                    <span class="bg-white text-amber-600 text-[10px] font-bold px-2 py-1 rounded-full shadow animate-pulse">
                        <?= $totalPending ?>
                    </span>
                <?php endif; ?>
            </a>
        </nav>

        <div class="p-6">
            <a href="../logout.php" class="flex items-center justify-center w-full py-3 text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 rounded-2xl transition">
                Logout
            </a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8">
        
        <div class="flex justify-between items-end mb-8 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Pesanan Masuk</h2>
                <p class="text-slate-400 text-sm mt-1">Pantau orderan dari pelanggan secara real-time.</p>
            </div>
            <div class="bg-slate-50 px-4 py-2 rounded-xl text-sm font-bold text-slate-600 border border-slate-100">
                Total: <span class="text-amber-600"><?= count($orders) ?></span> Order
            </div>
        </div>

        <!-- Tabel Modern -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">No. Order</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Total</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(count($orders) > 0): ?>
                        <?php foreach($orders as $o): ?>
                        <tr class="hover:bg-amber-50/50 transition duration-200">
                            <td class="py-5 px-6 font-mono font-bold text-slate-400 text-sm">
                                #ORD-<?= str_pad($o['id'], 3, '0', STR_PAD_LEFT) ?>
                            </td>
                            
                            <td class="py-5 px-6">
                                <div class="font-bold text-slate-700"><?= htmlspecialchars($o['customer_name']) ?></div>
                            </td>
                            
                            <td class="py-5 px-6 font-mono font-bold text-amber-600">
                                Rp <?= number_format($o['total_price'], 0, ',', '.') ?>
                            </td>
                            
                            <td class="py-5 px-6 text-sm text-slate-500">
                                <?= date('d M Y, H:i', strtotime($o['order_date'])) ?>
                            </td>
                            
                            <td class="py-5 px-6">
                                <?php if($o['status'] == 'pending'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 shadow-sm">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full mr-2 animate-pulse"></span>
                                        Menunggu
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Selesai
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="py-5 px-6 text-right">
                                <?php if($o['status'] == 'pending'): ?>
                                    <a href="trindex.php?terima=<?= $o['id'] ?>" onclick="return confirm('Proses pesanan ini?')" 
                                       class="inline-block bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2 px-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                                        Proses
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-300 text-xs italic font-medium">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 bg-slate-50/50">
                                Belum ada pesanan masuk hari ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>