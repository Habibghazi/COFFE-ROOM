<?php
// admin/menu/index.php
include '../Koneksi.php'; 
session_start();

// Cek Login & Admin
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Ambil data produk
$query = $conn->query("SELECT * FROM products ORDER BY id DESC");
$menus = $query->fetchAll(PDO::FETCH_ASSOC);

// Hitung Pending untuk Notifikasi Sidebar
$totalPending = 0;
try {
    $totalPending = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
} catch(Exception $e) {}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk - Admin Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> 
        body { font-family: 'Plus+Jakarta Sans', sans-serif; background-color: #F8FAFC; } 
        ::-webkit-scrollbar { width: 0px; background: transparent; }
    </style>
</head>
<body class="text-slate-800 h-screen flex overflow-hidden">

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

            <!-- Produk Aktif -->
            <a href="index.php" class="flex items-center px-4 py-3.5 bg-amber-500 text-white rounded-2xl shadow-lg shadow-amber-200 transition-all transform hover:scale-[1.02]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-bold">Produk</span>
            </a>

            <a href="../transaksi/trindex.php" class="flex items-center justify-between px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
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

            <!-- MEMBER & PROMO (BARU) -->
            <a href="../member/member_index.php" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-semibold">Member & Promo</span>
            </a>

        </nav>

        <div class="p-6">
            <a href="../admin_logout.php" class="flex items-center justify-center w-full py-3 text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 rounded-2xl transition">
                Logout
            </a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="flex justify-between items-end mb-8 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Daftar Menu</h2>
                <p class="text-slate-400 text-sm mt-1">Kelola varian kopi dan harga di sini.</p>
            </div>
            
            <a href="tambah.php" class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold py-3 px-6 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Menu
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">No</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Visual</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Detail Menu</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Harga</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(count($menus) > 0): ?>
                        <?php $no = 1; foreach($menus as $m): ?>
                        <tr class="hover:bg-amber-50/50 transition duration-200 group">
                            <td class="py-5 px-6 text-slate-400 font-medium"><?= $no++ ?></td>
                            <td class="py-5 px-6">
                                <div class="h-14 w-14 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 group-hover:border-amber-300 transition shadow-sm">
                                    <?php if(!empty($m['image'])): ?>
                                        <img src="../../uploads/<?= $m['image'] ?>" class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <div class="flex items-center justify-center h-full text-xs text-slate-400">IMG</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="font-bold text-slate-700 text-lg"><?= $m['name'] ?></div>
                                <div class="text-xs text-slate-400 mt-1 line-clamp-1"><?= $m['description'] ?></div>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500"><?= $m['category'] ?? 'General' ?></span>
                            </td>
                            <td class="py-5 px-6 font-mono font-bold text-amber-600">Rp <?= number_format($m['price'], 0, ',', '.') ?></td>
                            <td class="py-5 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="edit.php?id=<?= $m['id'] ?>" class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <a href="hapus.php?id=<?= $m['id'] ?>" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition" onclick="return confirm('Hapus menu ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="py-12 text-center text-slate-400 bg-slate-50/50">Belum ada data menu.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>