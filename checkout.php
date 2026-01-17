<?php
// checkout.php (VERSI ULTIMATE: POIN + RESET DISKON)
session_start();
include 'admin/Koneksi.php';

// Cek Login & Keranjang
if (!isset($_SESSION['is_login'])) { header("Location: login.php"); exit; }
if (empty($_SESSION['keranjang'])) { header("Location: csindex.php"); exit; }

// --- LOGIKA HITUNG TOTAL & DISKON ---
$subtotal = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $subtotal += ($item['harga'] * $item['qty']);
}

$diskon = isset($_SESSION['diskon_aktif']) ? $_SESSION['diskon_aktif'] : 0;
$totalBayar = $subtotal - $diskon;
// ------------------------------------

if (isset($_POST['bayar'])) {
    $namaUser = $_SESSION['user_name']; 
    $metodeBayar = $_POST['metode_pembayaran']; 
    
    $infoLengkap = "$namaUser - $metodeBayar";
    
    $sql = "INSERT INTO orders (customer_name, total_price, status) VALUES (:nama, :total, 'pending')";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([':nama' => $infoLengkap, ':total' => $totalBayar])) {
        $last_id = $conn->lastInsertId();
        $idUser = $_SESSION['user_id'];
        
        // --- 1. LOGIC UPDATE POIN & UNLOCK VOUCHER ---
        $jumlahItemDibeli = 0;
        foreach ($_SESSION['keranjang'] as $itm) {
            $jumlahItemDibeli += $itm['qty'];
        }
        
        $cekUser = $conn->query("SELECT total_points, voucher_unlock_date FROM users WHERE id = $idUser")->fetch(PDO::FETCH_ASSOC);
        $poinLama = $cekUser['total_points'];
        $poinBaru = $poinLama + $jumlahItemDibeli;
        $tglUnlock = $cekUser['voucher_unlock_date'];

        if ($poinBaru >= 15 && $tglUnlock == NULL) {
            $conn->query("UPDATE users SET total_points = $poinBaru, voucher_unlock_date = CURDATE() WHERE id = $idUser");
        } else {
            $conn->query("UPDATE users SET total_points = $poinBaru WHERE id = $idUser");
        }

        // --- 2. RESET DISKON SPESIAL ADMIN (Supaya cuma bisa dipakai sekali) ---
        $conn->query("UPDATE users SET special_discount = 0 WHERE id = $idUser");
        // ----------------------------------------------------------------------

        unset($_SESSION['keranjang']);
        unset($_SESSION['diskon_aktif']); 
        
        echo "<script>alert('✅ Pesanan Berhasil! Terima kasih.'); window.location = 'lacak.php?id=$last_id';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-lg bg-[#1a1a1a] rounded-2xl shadow-2xl border border-gray-800 overflow-hidden">
        
        <div class="bg-[#252525] p-6 text-center border-b border-gray-700">
            <h1 class="text-2xl font-serif text-[#C69C6D]">Pembayaran</h1>
        </div>

        <div class="p-8">
            <div class="mb-6 text-center">
                <p class="text-gray-400 text-sm mb-1">Memesan sebagai:</p>
                <h3 class="text-xl font-bold text-white"><?= $_SESSION['user_name'] ?></h3>
            </div>

            <!-- RINGKASAN TAGIHAN -->
            <div class="bg-black/30 p-4 rounded-lg border border-gray-700 mb-6 space-y-2">
                <div class="flex justify-between text-gray-400 text-sm">
                    <span>Subtotal</span>
                    <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                </div>
                
                <?php if($diskon > 0): ?>
                <div class="flex justify-between text-green-400 text-sm">
                    <span>Diskon (Voucher)</span>
                    <span>- Rp <?= number_format($diskon, 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>

                <div class="flex justify-between items-center pt-2 border-t border-gray-700">
                    <span class="text-gray-300 font-bold">Total Bayar</span>
                    <span class="text-2xl font-bold text-[#C69C6D]">Rp <?= number_format($totalBayar, 0, ',', '.') ?></span>
                </div>
            </div>

            <form method="POST">
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Metode Pembayaran</label>
                    <div class="space-y-3">
                        <label class="flex items-start gap-4 p-4 border border-gray-700 rounded-xl cursor-pointer hover:bg-gray-800 transition">
                            <input type="radio" name="metode_pembayaran" value="DANA" required class="mt-1 accent-[#C69C6D]">
                            <div>
                                <span class="font-bold text-blue-400 block">DANA</span>
                                <span class="text-xs text-gray-500 block font-mono bg-black/20 px-2 py-1 mt-1 rounded">0823-4571-2542 (Habib)</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-4 p-4 border border-gray-700 rounded-xl cursor-pointer hover:bg-gray-800 transition">
                            <input type="radio" name="metode_pembayaran" value="BNI" required class="mt-1 accent-[#C69C6D]">
                            <div>
                                <span class="font-bold text-orange-400 block">Bank BNI</span>
                                <span class="text-xs text-gray-500 block font-mono bg-black/20 px-2 py-1 mt-1 rounded">1005-987-678 (Habib)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" name="bayar" class="w-full bg-gradient-to-r from-[#C69C6D] to-[#8c6b4a] hover:from-[#b0885e] hover:to-[#7a5c3f] text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 transform hover:scale-[1.02]">
                    ✅ KONFIRMASI BAYAR
                </button>
                
                <a href="keranjang.php" class="block text-center text-gray-500 text-xs mt-4 hover:text-white">
                    Batal
                </a>
            </form>
        </div>
    </div>

</body>
</html>