<?php
// admin/member/member_index.php
include '../../admin/Koneksi.php'; 
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// --- LOGIC KIRIM VOUCHER BARU (INSERT) ---
if (isset($_POST['kirim_diskon'])) {
    $target = $_POST['target_user'];
    $diskon = $_POST['jumlah_diskon'];
    $durasi = $_POST['durasi']; 
    // Mengambil Nama Promo dari Input, jika kosong pakai default
    $namaPromo = !empty($_POST['nama_promo']) ? $_POST['nama_promo'] : "Diskon Admin Spesial";

    // Hitung waktu expired
    $expiredTime = date('Y-m-d H:i:s', strtotime("+$durasi"));

    if ($target == 'all') {
        // Kirim ke SEMUA user
        $users = $conn->query("SELECT id FROM users WHERE role = 'customer'")->fetchAll();
        foreach ($users as $u) {
            $sql = "INSERT INTO user_vouchers (user_id, nama_voucher, diskon, expired_at) VALUES (?, ?, ?, ?)";
            $conn->prepare($sql)->execute([$u['id'], $namaPromo, $diskon, $expiredTime]);
        }
        $pesan = "Voucher '$namaPromo' dikirim ke SEMUA Member!";
    } else {
        // Kirim ke SATU user
        $sql = "INSERT INTO user_vouchers (user_id, nama_voucher, diskon, expired_at) VALUES (?, ?, ?, ?)";
        $conn->prepare($sql)->execute([$target, $namaPromo, $diskon, $expiredTime]);
        $pesan = "Voucher '$namaPromo' berhasil dikirim!";
    }
    
    echo "<script>alert('$pesan'); window.location='member_index.php';</script>";
}

// Ambil Data Member
$members = $conn->query("SELECT * FROM users WHERE role = 'customer'")->fetchAll(PDO::FETCH_ASSOC);

// Hitung Pending Sidebar
$totalPending = 0;
try { $totalPending = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn(); } catch(Exception $e) {}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Member - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; } </style>
</head>
<body class="text-slate-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
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
            <a href="../transaksi/trindex.php" class="flex items-center justify-between px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-amber-600 rounded-2xl transition-all group">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="font-semibold">Transaksi</span>
                </div>
                <?php if($totalPending > 0): ?>
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-md animate-bounce"><?= $totalPending ?></span>
                <?php endif; ?>
            </a>
            <a href="member_index.php" class="flex items-center px-4 py-3.5 bg-amber-500 text-white rounded-2xl shadow-lg shadow-amber-200 transition-all transform hover:scale-[1.02]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-bold">Member & Promo</span>
            </a>
        </nav>
        <div class="p-6">
            <a href="../admin_logout.php" class="flex items-center justify-center w-full py-3 text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 rounded-2xl transition">Logout</a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8">
        
        <div class="flex justify-between items-end mb-8 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kelola Member & Promo</h2>
                <p class="text-slate-400 text-sm mt-1">Kirim diskon spesial untuk pelanggan setia.</p>
            </div>
        </div>

        <!-- FORM KIRIM DISKON -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">🎁 Kirim Voucher Baru</h3>
            
            <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <!-- Kolom Input Nama Voucher -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Promo</label>
                    <input type="text" name="nama_promo" placeholder="Misal: Promo Gajian" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-amber-500" required>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Target Member</label>
                    <select name="target_user" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-amber-500">
                        <option value="all">📢 SEMUA MEMBER</option>
                        <?php foreach($members as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= $m['name'] ?> (<?= $m['email'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Diskon (%)</label>
                    <div class="relative">
                        <input type="number" name="jumlah_diskon" value="20" min="0" max="100" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-amber-500 pl-10">
                        <span class="absolute left-4 top-3 text-slate-400 font-bold">%</span>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Durasi Berlaku</label>
                    <select name="durasi" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-amber-500">
                        <option value="1 HOUR">1 Jam</option>
                        <option value="1 DAY" selected>1 Hari</option>
                        <option value="3 DAY">3 Hari</option>
                        <option value="1 WEEK">1 Minggu</option>
                    </select>
                </div>

                <div class="md:col-span-1">
                    <button type="submit" name="kirim_diskon" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-amber-200 transform hover:-translate-y-1">
                        Kirim Voucher 🚀
                    </button>
                </div>
            </form>
        </div>

        <!-- TABEL MEMBER & VOUCHER AKTIF -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Total Voucher Aktif</th>
                        <th class="py-5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach($members as $m): 
                        // Hitung voucher aktif per user
                        $stmtVoucher = $conn->prepare("SELECT COUNT(*) FROM user_vouchers WHERE user_id = ? AND is_used = 0 AND expired_at > NOW()");
                        $stmtVoucher->execute([$m['id']]);
                        $jmlVoucher = $stmtVoucher->fetchColumn();
                    ?>
                    <tr class="hover:bg-amber-50/50 transition duration-200">
                        <td class="py-5 px-6 font-bold text-slate-700"><?= $m['name'] ?></td>
                        <td class="py-5 px-6">
                            <?php if($jmlVoucher > 0): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    <?= $jmlVoucher ?> Voucher Tersedia
                                </span>
                            <?php else: ?>
                                <span class="text-slate-400 text-xs">Kosong</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-5 px-6 text-right text-xs text-slate-400">
                            ID: #<?= $m['id'] ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>