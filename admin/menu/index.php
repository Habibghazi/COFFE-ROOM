<?php
// admin/menu/index.php
include '../Koneksi.php'; 

// Ambil data produk
$query = $conn->query("SELECT * FROM products ORDER BY id DESC");
$menus = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Admin Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex overflow-hidden">

    <!-- 1. SIDEBAR (KIRI) -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-8 border-b border-gray-100">
            <h1 class="text-xl font-bold tracking-tight text-gray-800">COFFEE<span class="text-amber-600">ROOM</span></h1>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            
            <!-- Menu Home (Dashboard) -->
            <a href="../dashboard.php" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-amber-600 rounded-lg transition group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Home</span>
            </a>

            <!-- Menu Product (Sedang Aktif) -->
            <a href="index.php" class="flex items-center px-4 py-3 bg-amber-50 text-amber-700 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-bold">Products</span>
            </a>

            <!-- Menu Transaksi (SUDAH DIPERBAIKI) -->
            <a href="../transaksi/trindex.php" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-amber-600 rounded-lg transition group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="font-medium">Transaksi</span>
            </a>

        </nav>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-gray-100">
            <a href="../logout.php" class="flex items-center text-sm text-gray-500 hover:text-red-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </a>
        </div>
    </aside>

    <!-- 2. KONTEN UTAMA (KANAN) -->
    <main class="flex-1 overflow-y-auto p-8">
        
        <!-- Header Konten -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Products</h2>
                <p class="text-sm text-gray-500">Kelola menu kopi Anda di sini.</p>
            </div>
            
            <a href="tambah.php" class="bg-black hover:bg-gray-800 text-white text-sm font-medium py-2.5 px-5 rounded-lg shadow-lg flex items-center gap-2 transition">
                <span>+</span> Tambah Menu
            </a>
        </div>

        <!-- Tabel Konten -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Image</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Name & Desc</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Price</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(count($menus) > 0): ?>
                        <?php $no = 1; foreach($menus as $m): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 text-gray-400 text-sm"><?= $no++ ?></td>
                            <td class="py-4 px-6">
                                <div class="h-10 w-10 rounded-lg bg-gray-100 overflow-hidden border border-gray-200">
                                    <?php if(!empty($m['image'])): ?>
                                        <img src="../../uploads/<?= $m['image'] ?>" class="h-full w-full object-cover">
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900"><?= $m['name'] ?></div>
                                <div class="text-xs text-gray-500 truncate max-w-[200px]"><?= $m['description'] ?></div>
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-900 text-sm">
                                Rp <?= number_format($m['price'], 0, ',', '.') ?>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="edit.php?id=<?= $m['id'] ?>" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold uppercase">Edit</a>
                                <a href="hapus.php?id=<?= $m['id'] ?>" class="text-red-500 hover:text-red-700 text-xs font-bold uppercase" onclick="return confirm('Hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="py-8 text-center text-sm text-gray-400">Belum ada data.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>