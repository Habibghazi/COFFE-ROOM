<?php
// keranjang.php (VERSI CUSTOM NAMA VOUCHER)
session_start();
include 'admin/Koneksi.php';

if (!isset($_SESSION['is_login'])) { header("Location: login.php"); exit; }

$idUser = $_SESSION['user_id'];
$sekarang = date('Y-m-d H:i:s');

// 1. AMBIL POIN USER
$user = $conn->query("SELECT total_points, voucher_unlock_date FROM users WHERE id = $idUser")->fetch();
$totalRiwayatItem = $user['total_points'] ?? 0;
$tglUnlock = $user['voucher_unlock_date'];

// 2. HITUNG KERANJANG
$subtotal = 0; $totalQty = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $subtotal += ($item['harga'] * $item['qty']);
        $totalQty += $item['qty'];
    }
}

// 3. AMBIL LIST VOUCHER YANG TERSEDIA
$listVoucher = [];

// A. Voucher dari Admin (Tabel user_vouchers)
try {
    $stmt = $conn->prepare("SELECT * FROM user_vouchers WHERE user_id = :uid AND is_used = 0 AND expired_at > :now");
    $stmt->execute([':uid' => $idUser, ':now' => $sekarang]);
    $vouchersDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($vouchersDB as $v) {
        $listVoucher[] = [
            'id' => $v['id'],
            'type' => 'admin_db',
            'nama' => $v['nama_voucher'], // Mengambil nama custom dari Admin
            'nilai' => $v['diskon'],
            'warna' => 'emerald' // Warna Hijau untuk Voucher Admin
        ];
    }
} catch (Exception $e) { }

// B. Voucher Poin
if ($totalRiwayatItem >= 15 && $tglUnlock != NULL) {
    $tglBatas = date('Y-m-d', strtotime($tglUnlock . ' + 4 days'));
    if (date('Y-m-d') <= $tglBatas && $totalQty >= 5) {
        $listVoucher[] = [
            'id' => 'poin',
            'type' => 'poin',
            'nama' => "Reward Poin (Eksklusif)",
            'nilai' => 50,
            'warna' => 'amber' // Warna Emas untuk Voucher Poin
        ];
    }
}

// 4. PROSES PILIH VOUCHER
$diskonRupiah = 0;
$voucherDipilihID = $_SESSION['voucher_dipakai']['id'] ?? "";

if (isset($_POST['pilih_voucher'])) {
    $idVoc = $_POST['id_voucher'];
    $persen = $_POST['persen'];
    $tipe = $_POST['tipe_voucher'];
    
    // Hitung Diskon
    $diskonRupiah = $subtotal * ($persen / 100);
    
    // Simpan ke Session
    $_SESSION['diskon_aktif'] = $diskonRupiah;
    $_SESSION['voucher_dipakai'] = [
        'id' => $idVoc,
        'type' => $tipe,
        'persen' => $persen
    ];
    
    $voucherDipilihID = $idVoc;
} else if (isset($_SESSION['diskon_aktif'])) {
    // Re-kalkulasi diskon jika keranjang berubah tapi voucher masih aktif
    $diskonRupiah = $_SESSION['diskon_aktif'];
}

$totalBayar = max(0, $subtotal - $diskonRupiah);

// Hapus Item
if (isset($_GET['hapus'])) {
    $idHapus = $_GET['hapus'];
    unset($_SESSION['keranjang'][$idHapus]);
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    
    // Reset diskon jika keranjang kosong
    if(empty($_SESSION['keranjang'])) {
        unset($_SESSION['diskon_aktif']);
        unset($_SESSION['voucher_dipakai']);
    }
    
    header("Location: keranjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen p-6 md:p-12">

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8 border-b border-gray-800 pb-6">
            <h1 class="text-3xl font-bold font-serif text-[#C69C6D]">Shopping Cart</h1>
            <a href="csindex.php" class="text-gray-400 hover:text-white text-sm transition flex items-center gap-2">
                <span>&larr;</span> Kembali Belanja
            </a>
        </div>

        <?php if (!empty($_SESSION['keranjang'])): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- LIST ITEM (KIRI) -->
                <div class="md:col-span-2 space-y-4">
                    <!-- Info Member -->
                    <div class="bg-gray-800/50 p-4 rounded-2xl mb-6 border border-gray-700/50">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xl">🎁</span>
                            <h4 class="font-bold text-[#C69C6D]">Info Poin Kamu</h4>
                        </div>
                        <div class="text-sm text-gray-300 flex justify-between mb-2">
                            <span>Progres Voucher Gratis:</span>
                            <span class="font-bold text-white"><?= $totalRiwayatItem ?> <span class="text-gray-500">/ 15 Item</span></span>
                        </div>
                        <div class="w-full bg-gray-900 rounded-full h-3 border border-gray-700">
                            <div class="bg-gradient-to-r from-[#C69C6D] to-[#e3bc8e] h-full rounded-full transition-all duration-1000" style="width: <?= min(100, ($totalRiwayatItem/15)*100) ?>%"></div>
                        </div>
                    </div>

                    <?php foreach ($_SESSION['keranjang'] as $key => $item): ?>
                    <div class="bg-[#1a1a1a] p-5 rounded-2xl flex items-center justify-between border border-gray-800 hover:border-gray-700 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gray-900 border border-gray-800 text-2xl">
                                <?= ($item['suhu'] == 'Hot') ? '☕' : '❄️' ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-100"><?= $item['nama'] ?></h3>
                                <p class="text-gray-500 text-sm italic">Variant: <?= $item['suhu'] ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <p class="text-[#C69C6D] font-bold">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                <p class="text-gray-500 text-xs">x <?= $item['qty'] ?></p>
                            </div>
                            <a href="keranjang.php?hapus=<?= $key ?>" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition">✕</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- RINGKASAN (KANAN) -->
                <div class="bg-[#1a1a1a] p-6 rounded-2xl border border-gray-800 h-fit sticky top-10 shadow-2xl">
                    <h3 class="text-xl font-bold mb-6 text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#C69C6D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Ringkasan Pesanan
                    </h3>
                    
                    <!-- PILIH VOUCHER -->
                    <h4 class="text-[10px] font-bold text-gray-500 mb-3 uppercase tracking-[0.2em]">Voucher Tersedia</h4>
                    
                    <?php if(count($listVoucher) > 0): ?>
                        <div class="space-y-3 mb-6">
                            <?php foreach($listVoucher as $voc): 
                                $isPoin = ($voc['warna'] == 'amber');
                                $colorClass = $isPoin ? 'amber' : 'emerald';
                            ?>
                                <form method="POST">
                                    <input type="hidden" name="id_voucher" value="<?= $voc['id'] ?>">
                                    <input type="hidden" name="tipe_voucher" value="<?= $voc['type'] ?>">
                                    <input type="hidden" name="persen" value="<?= $voc['nilai'] ?>">
                                    
                                    <button type="submit" name="pilih_voucher" class="w-full text-left group">
                                        <div class="relative overflow-hidden border transition-all duration-300 rounded-xl p-4 
                                            <?= ($voucherDipilihID == $voc['id']) 
                                                ? "border-$colorClass-500 bg-$colorClass-500/10 shadow-[0_0_15px_rgba(16,185,129,0.1)]" 
                                                : "border-gray-800 bg-gray-900/50 hover:border-gray-600" ?>">
                                            
                                            <div class="flex justify-between items-center relative z-10">
                                                <div>
                                                    <p class="text-<?= $colorClass ?>-400 font-extrabold text-sm uppercase tracking-tight">
                                                        <?= $voc['nama'] ?> <!-- INI NAMA CUSTOM DARI ADMIN -->
                                                    </p>
                                                    <p class="text-[10px] <?= ($voucherDipilihID == $voc['id']) ? "text-$colorClass-300" : "text-gray-500" ?> mt-1">
                                                        Potongan sebesar <?= $voc['nilai'] ?>%
                                                    </p>
                                                </div>
                                                <?php if($voucherDipilihID == $voc['id']): ?>
                                                    <span class="bg-<?= $colorClass ?>-500 text-black rounded-full w-5 h-5 flex items-center justify-center text-[10px] font-bold italic">YEP</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-gray-900/50 border border-dashed border-gray-800 p-4 rounded-xl text-xs text-gray-500 text-center mb-6">
                            Belum ada voucher untukmu.
                        </div>
                    <?php endif; ?>

                    <div class="space-y-3 border-t border-gray-800 pt-6">
                        <div class="flex justify-between text-sm text-gray-400">
                            <span>Subtotal</span>
                            <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-emerald-400 font-medium">
                            <span>Diskon Voucher</span>
                            <span>- Rp <?= number_format($diskonRupiah, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-[#C69C6D] pt-2">
                            <span>Total</span>
                            <span>Rp <?= number_format($totalBayar, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <a href="checkout.php" class="block w-full mt-8 bg-[#C69C6D] hover:bg-[#b0885e] text-black text-center font-black py-4 rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-xl shadow-orange-900/10 uppercase tracking-widest text-sm">
                        Lanjut Bayar &rarr;
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-24 bg-[#1a1a1a] rounded-3xl border border-dashed border-gray-800">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-bold text-gray-400 mb-2">Keranjangmu Kosong</h2>
                <p class="text-gray-600 mb-8">Sepertinya kamu belum memilih kopi hari ini.</p>
                <a href="csindex.php" class="inline-block bg-[#C69C6D] text-black px-10 py-3 rounded-xl font-bold hover:bg-white transition-all">Mulai Belanja</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>