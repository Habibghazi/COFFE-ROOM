<?php
// checkout.php
session_start();
include 'admin/Koneksi.php';

// Cek keranjang
if (empty($_SESSION['keranjang'])) {
    header("Location: csindex.php");
    exit;
}

// Hitung Total
$totalBayar = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $totalBayar += ($item['harga'] * $item['qty']);
}

// Proses Bayar
if (isset($_POST['bayar'])) {
    $namaPemesan = $_POST['nama_pemesan'];
    $metodeBayar = $_POST['metode_pembayaran']; // Tangkap metode bayar
    
    // Simpan ke database
    $sql = "INSERT INTO orders (customer_name, total_price, status) VALUES (:nama, :total, 'pending')";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([':nama' => "$namaPemesan ($metodeBayar)", ':total' => $totalBayar])) {
        $last_id = $conn->lastInsertId();
        unset($_SESSION['keranjang']);
        echo "<script>alert('✅ Pesanan Berhasil! Silakan lakukan pembayaran.'); window.location = 'lacak.php?id=$last_id';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout & Pembayaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-lg bg-[#1a1a1a] rounded-2xl shadow-2xl border border-gray-800 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-[#252525] p-6 text-center border-b border-gray-700">
            <h1 class="text-2xl font-serif text-[#C69C6D]">Selesaikan Pembayaran</h1>
            <p class="text-gray-400 text-sm mt-1">Langkah terakhir untuk menikmati kopimu</p>
        </div>

        <div class="p-8">
            
            <!-- Ringkasan Total -->
            <div class="flex justify-between items-center mb-6 bg-black/30 p-4 rounded-lg border border-gray-700">
                <span class="text-gray-300">Total Tagihan</span>
                <span class="text-2xl font-bold text-[#C69C6D]">Rp <?= number_format($totalBayar, 0, ',', '.') ?></span>
            </div>

            <form method="POST">
                
                <!-- Input Nama -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Nama Pemesan / Meja</label>
                    <input type="text" name="nama_pemesan" required placeholder="Contoh: Habib" 
                        class="w-full bg-[#0f0f0f] border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#C69C6D] focus:ring-1 focus:ring-[#C69C6D]">
                </div>

                <!-- Info Rekening (Card Pilihan) -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Metode Pembayaran</label>
                    
                    <div class="space-y-3">
                        <!-- Opsi DANA -->
                        <label class="flex items-start gap-4 p-4 border border-gray-700 rounded-xl cursor-pointer hover:bg-gray-800 transition">
                            <input type="radio" name="metode_pembayaran" value="DANA" required class="mt-1 accent-[#C69C6D]">
                            <div>
                                <span class="font-bold text-blue-400 block">DANA</span>
                                <span class="text-sm text-gray-300">Habib Ghazi AL-Ghifari</span>
                                <span class="text-xs text-gray-500 block font-mono bg-black/20 px-2 py-1 mt-1 rounded">0823-4571-2542</span>
                            </div>
                        </label>

                        <!-- Opsi BNI -->
                        <label class="flex items-start gap-4 p-4 border border-gray-700 rounded-xl cursor-pointer hover:bg-gray-800 transition">
                            <input type="radio" name="metode_pembayaran" value="BNI" required class="mt-1 accent-[#C69C6D]">
                            <div>
                                <span class="font-bold text-orange-400 block">Bank BNI</span>
                                <span class="text-sm text-gray-300">Habib Ghazi AL-Ghifari</span>
                                <span class="text-xs text-gray-500 block font-mono bg-black/20 px-2 py-1 mt-1 rounded">1005-987-678</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit (Warna Emas Besar) -->
                <button type="submit" name="bayar" class="w-full bg-gradient-to-r from-[#C69C6D] to-[#8c6b4a] hover:from-[#b0885e] hover:to-[#7a5c3f] text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <span>✅</span> KONFIRMASI SUDAH BAYAR
                </button>
                
                <a href="keranjang.php" class="block text-center text-gray-500 text-xs mt-4 hover:text-white">
                    Kembali ke Keranjang
                </a>

            </form>
        </div>
    </div>

</body>
</html>