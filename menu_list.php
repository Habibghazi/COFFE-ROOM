<?php
// menu_list.php (VERSI FINAL + QTY INPUT)
session_start(); 

include 'admin/Koneksi.php';

// 1. Tangkap Kategori
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'Black Series';

// 2. Ambil Menu dari Database
$stmt = $conn->prepare("SELECT * FROM products WHERE category = :kategori");
$stmt->execute(['kategori' => $kategori]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Tentukan Background
if ($kategori == 'Black Series') {
    $bgImage = "https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=2071&auto=format&fit=crop"; 
} else {
    $bgImage = "https://images.unsplash.com/photo-1517701604599-bb29b5c7355c?q=80&w=2070&auto=format&fit=crop"; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu <?= htmlspecialchars($kategori) ?> - Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen">

    <!-- PRELOADER -->
    <div id="preloader" class="fixed inset-0 z-[9999] bg-[#0f0f0f] flex items-center justify-center transition-opacity duration-700">
        <div class="text-center">
            <div class="text-6xl mb-4 animate-bounce">☕</div>
            <div class="text-[#C69C6D] text-xl font-serif tracking-widest animate-pulse">BREWING...</div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('preloader');
            setTimeout(() => {
                loader.classList.add('opacity-0');
                setTimeout(() => { loader.style.display = 'none'; }, 700);
            }, 500);
        });
    </script>

    <!-- NAVBAR SIMPEL -->
    <nav class="fixed top-0 w-full p-6 flex justify-between items-center z-50 bg-black/50 backdrop-blur-md border-b border-white/10">
        <a href="pilih_series.php" class="flex items-center gap-2 text-gray-300 hover:text-[#C69C6D] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <div class="text-xl font-bold tracking-widest text-[#C69C6D]">COFFEE ROOM</div>
        <div class="w-20"></div>
    </nav>

    <!-- HEADER KATEGORI + TOMBOL SWITCH -->
    <header class="relative h-72 flex items-center justify-center overflow-hidden">
        <img src="<?= $bgImage ?>" class="absolute inset-0 w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-[#0f0f0f]"></div>
        
        <div class="relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-2"><?= htmlspecialchars($kategori) ?></h1>
            <p class="text-gray-400 mb-6">Pilih varian favoritmu</p>

            <!-- TOMBOL PINDAH KATEGORI -->
            <div class="flex justify-center gap-4">
                <a href="menu_list.php?kategori=Black Series" 
                   class="px-6 py-2 rounded-full border border-[#C69C6D] transition duration-300 font-bold text-sm uppercase tracking-wider
                   <?= ($kategori == 'Black Series') ? 'bg-[#C69C6D] text-white' : 'bg-black/50 text-[#C69C6D] hover:bg-[#C69C6D] hover:text-white' ?>">
                   ☕ Black Series
                </a>
                
                <a href="menu_list.php?kategori=White Series" 
                   class="px-6 py-2 rounded-full border border-white transition duration-300 font-bold text-sm uppercase tracking-wider
                   <?= ($kategori == 'White Series') ? 'bg-white text-black' : 'bg-black/50 text-white hover:bg-white hover:text-black' ?>">
                   🥛 White Series
                </a>
            </div>
        </div>
    </header>

    <!-- DAFTAR MENU (GRID) -->
    <main class="max-w-6xl mx-auto px-6 py-10 pb-24"> 
        
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
                        
                        <div class="absolute top-4 right-4 bg-black/80 backdrop-blur text-[#C69C6D] px-4 py-1 rounded-full text-sm font-bold border border-[#C69C6D]/30">
                            Rp <?= number_format($p['price'], 0, ',', '.') ?>
                        </div>
                    </div>

                    <!-- Info Menu -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-white group-hover:text-[#C69C6D] transition"><?= $p['name'] ?></h3>
                        <p class="text-gray-400 text-sm mb-4 h-10 overflow-hidden text-ellipsis line-clamp-2">
                            <?= $p['description'] ?>
                        </p>

                        <!-- INPUT JUMLAH (QTY) -->
                        <div class="flex items-center gap-2 mb-4 bg-black/40 p-2 rounded-lg border border-gray-700">
                            <span class="text-xs text-gray-400 pl-2">Jumlah:</span>
                            <input type="number" id="qty_<?= $p['id'] ?>" value="1" min="1" max="50" 
                                   class="w-full bg-transparent text-white text-center font-bold focus:outline-none border-l border-gray-600 pl-2">
                        </div>

                        <!-- FORM ADD TO CART -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Tombol HOT -->
                            <form method="POST" action="proses_keranjang.php" onsubmit="this.qty.value = document.getElementById('qty_<?= $p['id'] ?>').value">
                                <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
                                <input type="hidden" name="nama_produk" value="<?= $p['name'] ?>">
                                <input type="hidden" name="harga" value="<?= $p['price'] ?>">
                                <input type="hidden" name="suhu" value="Hot">
                                <input type="hidden" name="qty" value="1"> <!-- Akan diisi JS -->
                                <button type="submit" name="add_to_cart" class="w-full flex items-center justify-center gap-2 border border-red-900/50 bg-red-900/10 hover:bg-red-600 hover:text-white text-red-500 py-2 rounded-lg transition font-semibold text-sm">
                                    🔥 Hot
                                </button>
                            </form>
                            
                            <!-- Tombol ICE -->
                            <form method="POST" action="proses_keranjang.php" onsubmit="this.qty.value = document.getElementById('qty_<?= $p['id'] ?>').value">
                                <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
                                <input type="hidden" name="nama_produk" value="<?= $p['name'] ?>">
                                <input type="hidden" name="harga" value="<?= $p['price'] ?>">
                                <input type="hidden" name="suhu" value="Ice">
                                <input type="hidden" name="qty" value="1"> <!-- Akan diisi JS -->
                                <button type="submit" name="add_to_cart" class="w-full flex items-center justify-center gap-2 border border-blue-900/50 bg-blue-900/10 hover:bg-blue-500 hover:text-white text-blue-400 py-2 rounded-lg transition font-semibold text-sm">
                                    ❄️ Ice
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="text-6xl mb-4">☕</div>
                <h3 class="text-2xl font-bold text-gray-600">Menu Belum Tersedia</h3>
                <p class="text-gray-500 mt-2">Admin belum menambahkan menu untuk kategori ini.</p>
                <div class="mt-6">
                    <a href="menu_list.php?kategori=<?= ($kategori == 'Black Series') ? 'White Series' : 'Black Series' ?>" class="inline-block border border-gray-600 px-6 py-2 rounded-full hover:bg-white hover:text-black transition">
                        Cek Kategori Sebelah &rarr;
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <!-- Tombol Keranjang Melayang -->
    <?php 
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