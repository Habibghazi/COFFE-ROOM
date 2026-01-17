<?php
// csindex.php
session_start();

// 1. CEK LOGIN (Wajib Paling Atas)
if (!isset($_SESSION['is_login'])) {
    header("Location: login.php");
    exit;
}

// 2. CEK ROLE (Admin dilarang masuk sini)
if ($_SESSION['role'] == 'admin') {
    header("Location: admin/dashboard.php");
    exit;
}

// 3. Hubungkan Database
include 'admin/Koneksi.php';

// --- TAMBAHAN FITUR CLOSE TOKO ---
// Ambil status toko dari database
$shopStatus = $conn->query("SELECT setting_value FROM settings WHERE setting_key = 'shop_status'")->fetchColumn();

// Jika statusnya closed, lempar ke halaman tutup.php
if ($shopStatus == 'closed') {
    header("Location: tutup.php");
    exit;
}
// --------------------------------

// 4. AMBIL VOUCHER TERBARU UNTUK USER INI
$idUser = $_SESSION['user_id'];
$voucherTerbaru = null;

try {
    // Kita ambil voucher terbaru yang belum dipakai dan belum expired
    $stmt = $conn->prepare("SELECT nama_voucher, diskon FROM user_vouchers 
                            WHERE user_id = :id AND is_used = 0 AND expired_at > NOW() 
                            ORDER BY id DESC LIMIT 1");
    $stmt->execute([':id' => $idUser]);
    $voucherTerbaru = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Room - Premium Taste</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2 { font-family: 'Playfair Display', serif; }
        
        .fade-in-up { animation: fadeInUp 1s ease-out forwards; opacity: 0; transform: translateY(30px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.6s; }
    </style>
</head>
<body class="bg-black text-white h-screen overflow-hidden"> 

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
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 700);
            }, 500);
        });
    </script>

    <!-- NAVBAR -->
    <nav class="absolute top-0 w-full p-6 flex justify-between items-center z-50">
        <div class="text-2xl font-bold tracking-widest text-[#C69C6D]">COFFEE ROOM</div>
        
        <div class="flex items-center gap-4 bg-black/30 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
            <span class="text-sm text-gray-300 font-medium">
                Halo, <span class="text-white font-bold"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
            </span>
            <div class="w-px h-4 bg-gray-600"></div>
            <a href="logout.php" class="text-xs text-red-400 hover:text-white transition font-bold uppercase tracking-wider">
                Logout
            </a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative h-full flex items-center justify-center">
        
        <!-- Background -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/80"></div>
        </div>

        <!-- Konten Tengah -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            
            <h1 class="text-5xl md:text-7xl font-bold mb-4 text-white fade-in-up">
                Welcome to <span class="text-[#C69C6D] italic">Coffee Room</span>
            </h1>
            

            <!-- BANNER PROMO -->
            <?php if($voucherTerbaru): ?>
            <div class="fade-in-up delay-300 mb-8">
                <div class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-900/60 to-green-900/60 border border-emerald-500 p-3 rounded-2xl shadow-[0_0_20px_rgba(16,185,129,0.3)] backdrop-blur-md transform hover:scale-105 transition duration-300 cursor-pointer animate-pulse">
                    <div class="bg-emerald-500 text-black w-10 h-10 flex items-center justify-center rounded-full font-bold text-xl">
                        %
                    </div>
                    <div class="text-left pr-4">
                        <p class="text-emerald-400 text-[10px] font-bold uppercase tracking-[0.2em]">Promo Baru Untukmu</p>
                        <p class="text-white text-sm font-medium">
                            Kamu dapat <span class="font-bold border-b border-emerald-500"><?= htmlspecialchars($voucherTerbaru['nama_voucher']) ?> <?= $voucherTerbaru['diskon'] ?>%</span>!
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- KOTAK PESANAN -->
            <div class="fade-in-up delay-500">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl max-w-md mx-auto transform hover:scale-105 transition duration-500">
                    
                    <h3 class="text-2xl font-semibold mb-4 text-[#C69C6D]">Mulai Pesananmu</h3>
                    <p class="text-sm text-gray-300 mb-6">Nikmati pengalaman ngopi terbaik hari ini.</p>

                    <a href="pilih_series.php" class="block w-full bg-[#C69C6D] hover:bg-[#b0885e] text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 uppercase tracking-wider">
                        ☕ Buat Pesanan Sekarang
                    </a>

                </div>
            </div>

        </div>
    </section>

</body>
</html>