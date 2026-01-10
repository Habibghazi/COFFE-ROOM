<?php
// keranjang.php
session_start();

// Hitung Total Bayar
$totalBayar = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $totalBayar += ($item['harga'] * $item['qty']);
    }
}

// Fitur Hapus Item (Jika tombol hapus diklik)
if (isset($_GET['hapus'])) {
    $idHapus = $_GET['hapus'];
    unset($_SESSION['keranjang'][$idHapus]);
    // Reset urutan array biar rapi
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    header("Location: keranjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Saya - Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen p-6 md:p-12">

    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-8 border-b border-gray-800 pb-6">
            <h1 class="text-3xl font-bold font-serif text-[#C69C6D]">Shopping Cart</h1>
            <a href="csindex.php" class="text-gray-400 hover:text-white text-sm">Kembali Belanja</a>
        </div>

        <?php if (!empty($_SESSION['keranjang'])): ?>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- LIST ITEM (Kiri) -->
                <div class="md:col-span-2 space-y-4">
                    <?php foreach ($_SESSION['keranjang'] as $key => $item): ?>
                    <div class="bg-[#1a1a1a] p-4 rounded-xl flex items-center justify-between border border-gray-800 hover:border-gray-600 transition">
                        
                        <!-- Info Produk -->
                        <div class="flex items-center gap-4">
                            <!-- Icon Suhu -->
                            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-gray-900 text-2xl">
                                <?= ($item['suhu'] == 'Hot') ? '🔥' : '❄️' ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg"><?= $item['nama'] ?></h3>
                                <p class="text-gray-400 text-sm">
                                    Variant: <span class="<?= ($item['suhu']=='Hot') ? 'text-red-400':'text-blue-400' ?>"><?= $item['suhu'] ?></span>
                                </p>
                            </div>
                        </div>

                        <!-- Harga & Qty -->
                        <div class="text-right">
                            <p class="text-[#C69C6D] font-bold">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                            <p class="text-gray-500 text-xs">x <?= $item['qty'] ?></p>
                        </div>

                        <!-- Tombol Hapus (X) -->
                        <a href="keranjang.php?hapus=<?= $key ?>" class="ml-4 text-gray-600 hover:text-red-500 transition px-2">
                            ✕
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- RINGKASAN BAYAR (Kanan) -->
                <div class="bg-[#1a1a1a] p-6 rounded-xl border border-gray-800 h-fit sticky top-10">
                    <h3 class="text-xl font-bold mb-4 text-white">Ringkasan</h3>
                    
                    <div class="flex justify-between mb-2 text-gray-400">
                        <span>Subtotal</span>
                        <span>Rp <?= number_format($totalBayar, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between mb-4 text-gray-400">
                        <span>Pajak (0%)</span>
                        <span>Rp 0</span>
                    </div>
                    
                    <div class="border-t border-gray-700 my-4 pt-4 flex justify-between text-xl font-bold text-[#C69C6D]">
                        <span>Total</span>
                        <span>Rp <?= number_format($totalBayar, 0, ',', '.') ?></span>
                    </div>

                    <!-- Tombol Checkout -->
                    <a href="checkout.php" class="block w-full bg-[#C69C6D] hover:bg-[#b0885e] text-white text-center font-bold py-3 rounded-lg transition mt-6">
                        LANJUT BAYAR &rarr;
                    </a>
                </div>

            </div>

        <?php else: ?>
            
            <!-- Kalau Keranjang Kosong -->
            <div class="text-center py-20 bg-[#1a1a1a] rounded-xl border border-dashed border-gray-700">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-bold text-gray-500 mb-2">Keranjang Kosong</h2>
                <p class="text-gray-600 mb-6">Kamu belum memilih kopi apapun.</p>
                <a href="csindex.php" class="bg-white text-black px-6 py-2 rounded-full font-bold hover:bg-gray-200 transition">
                    Pilih Kopi Dulu
                </a>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>