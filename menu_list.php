<?php
// menu_list.php
include 'admin/Koneksi.php';

// 1. Tangkap Kategori dari URL (contoh: menu_list.php?kategori=Black Series)
// Kalau tidak ada kategori, default ke 'Black Series'
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'Black Series';

// 2. Ambil Data Menu dari Database berdasarkan Filter Nama/Deskripsi
// (Karena kita belum buat kolom kategori di tabel products, kita filter manual pakai LIKE)
if ($kategori == 'Black Series') {
    // Cari yang namanya mengandung 'Americano', 'Black', 'Espresso', 'V60'
    $sql = "SELECT * FROM products WHERE name LIKE '%Black%' OR name LIKE '%Americano%' OR name LIKE '%Espresso%' OR description LIKE '%hitam%'";
    $bgImage = "https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=2071&auto=format&fit=crop"; // Background Gelap
} else {
    // Cari yang namanya mengandung 'Latte', 'Susu', 'Cappucino'
    $sql = "SELECT * FROM products WHERE name LIKE '%Latte%' OR name LIKE '%Cappucino%' OR name LIKE '%Milk%' OR description LIKE '%susu%'";
    $bgImage = "https://images.unsplash.com/photo-1517701604599-bb29b5c7355c?q=80&w=2070&auto=format&fit=crop"; // Background Terang
}

$query = $conn->query($sql);
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu <?= $kategori ?> - Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> 
        body { font-family: 'Inter', sans-serif; }
        h1, h2 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen">

    <!-- NAVBAR SIMPEL -->
    <nav class="fixed top-0 w-full p-6 flex justify-between items-center z-50 bg-black/50 backdrop-blur-md border-b border-white/10">
        <a href="csindex.php" class="flex items-center gap-2 text-gray-300 hover:text-[#C69C6D] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <div class="text-xl font-bold tracking-widest text-[#C69C6D]">COFFEE ROOM</div>
        <div class="w-20"></div> <!-- Spacer biar tengah -->
    </nav>

    <!-- HEADER KATEGORI -->
    <header class="relative h-64 flex items-center justify-center overflow-hidden">
        <img src="<?= $bgImage ?>" class="absolute inset-0 w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-[#0f0f0f]"></div>
        <div class="relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-2"><?= $kategori ?></h1>
            <p class="text-gray-400">Pilih varian favoritmu</p>
        </div>
    </header>

    <!-- DAFTAR MENU (GRID) -->
    <main class="max-w-6xl mx-auto px-6 py-10">
        
        <?php if(count($products) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <?php foreach($products as $p): ?>
                <div class="bg-[#1a1a1a] rounded-2xl overflow-hidden border border-gray-800 hover:border-[#C69C6D] transition duration-300 group shadow-lg">
                    
                    <!-- Gambar Menu -->
                    <div class="h-56 overflow-hidden relative">
                        <?php if($p['image']): ?>
                            <img src="uploads/<?= $p['image'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-800 flex items-center justify-center text-gray-500">No Image</div>
                        <?php endif; ?>
                        
                        <!-- Badge Harga -->
                        <div class="absolute top-4 right-4 bg-black/80 backdrop-blur text-[#C69C6D] px-4 py-1 rounded-full text-sm font-bold border border-[#C69C6D]/30">
                            Rp <?= number_format($p['price'], 0, ',', '.') ?>
                        </div>
                    </div>

                    <!-- Info Menu -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-white group-hover:text-[#C69C6D] transition"><?= $p['name'] ?></h3>
                        <p class="text-gray-400 text-sm mb-6 h-10 overflow-hidden text-ellipsis line-clamp-2">
                            <?= $p['description'] ?>
                        </p>

                        <!-- FORM PILIHAN SUHU (Mengirim ke proses_keranjang.php) -->
<div class="grid grid-cols-2 gap-3 mt-4">
    
    <!-- Tombol HOT -->
    <form method="POST" action="proses_keranjang.php">
        <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
        <input type="hidden" name="nama_produk" value="<?= $p['name'] ?>">
        <input type="hidden" name="harga" value="<?= $p['price'] ?>">
        <input type="hidden" name="suhu" value="Hot">
        
        <button type="submit" name="add_to_cart" 
                class="w-full flex items-center justify-center gap-2 border border-red-900/50 bg-red-900/10 hover:bg-red-600 hover:text-white text-red-500 py-2 rounded-lg transition font-semibold text-sm">
            🔥 Hot
        </button>
    </form>
    
    <!-- Tombol ICE -->
    <form method="POST" action="proses_keranjang.php">
        <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
        <input type="hidden" name="nama_produk" value="<?= $p['name'] ?>">
        <input type="hidden" name="harga" value="<?= $p['price'] ?>">
        <input type="hidden" name="suhu" value="Ice">
        
        <button type="submit" name="add_to_cart" 
                class="w-full flex items-center justify-center gap-2 border border-blue-900/50 bg-blue-900/10 hover:bg-blue-500 hover:text-white text-blue-400 py-2 rounded-lg transition font-semibold text-sm">
            ❄️ Ice
        </button>
    </form>
</div>

                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        <?php else: ?>
            <!-- Kalau Menu Kosong -->
            <div class="text-center py-20">
                <div class="text-6xl mb-4">☕</div>
                <h3 class="text-2xl font-bold text-gray-600">Menu Belum Tersedia</h3>
                <p class="text-gray-500 mt-2">Admin belum menambahkan menu untuk kategori ini.</p>
                <a href="csindex.php" class="inline-block mt-6 text-[#C69C6D] hover:underline">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>

    </main>

    <!-- Tombol Keranjang Melayang (Floating Button) -->
<?php 
session_start(); // Pastikan session start ada (kalau belum ada di atas)
$jumlahItem = 0;
if(isset($_SESSION['keranjang'])) {
    foreach($_SESSION['keranjang'] as $item) {
        $jumlahItem += $item['qty'];
    }
}
?>

<?php if($jumlahItem > 0): ?>
<a href="keranjang.php" class="fixed bottom-6 right-6 bg-[#C69C6D] hover:bg-white hover:text-black text-white px-6 py-4 rounded-full shadow-2xl flex items-center gap-3 transition duration-300 z-50 animate-bounce">
    <span class="text-xl">🛒</span>
    <span class="font-bold">Lihat Keranjang</span>
    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full border border-white">
        <?= $jumlahItem ?>
    </span>
</a>
<?php endif; ?>
</body>
</html>