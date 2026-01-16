<?php
// csindex.php
session_start();

// 1. CEK LOGIN: Kalau belum login, lempar ke halaman login
if (!isset($_SESSION['is_login'])) {
    header("Location: login.php");
    exit;
}
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
        .delay-500 { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-black text-white h-screen overflow-hidden"> 

    <!-- PRELOADER (Animasi Loading) -->
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

    <!-- NAVBAR (SUDAH DIUPDATE) -->
    <nav class="absolute top-0 w-full p-6 flex justify-between items-center z-50">
        <div class="text-2xl font-bold tracking-widest text-[#C69C6D]">COFFEE ROOM</div>
        
        <!-- Info User & Logout -->
        <div class="flex items-center gap-4 bg-black/30 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
            <span class="text-sm text-gray-300 font-medium">
                Halo, <span class="text-white font-bold"><?= $_SESSION['user_name'] ?? 'User' ?></span>
            </span>
            <div class="w-px h-4 bg-gray-600"></div> <!-- Garis pemisah -->
            <a href="logout.php" class="text-xs text-red-400 hover:text-white transition font-bold uppercase tracking-wider">
                Logout
            </a>
        </div>
    </nav>

    <!-- HERO SECTION FULL SCREEN -->
    <section class="relative h-full flex items-center justify-center">
        
        <!-- Background -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/80"></div>
        </div>

        <!-- Konten Tengah -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            
            <h1 class="text-5xl md:text-7xl font-bold mb-6 text-white fade-in-up">
                Welcome to <span class="text-[#C69C6D] italic">Coffee Room</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-300 mb-10 font-light tracking-wide fade-in-up delay-200">
                Rasakan kehangatan dalam setiap tegukan. <br>Premium beans, served with passion.
            </p>

            <div class="fade-in-up delay-500">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl max-w-md mx-auto transform hover:scale-105 transition duration-500">
                    
                    <h3 class="text-2xl font-semibold mb-4 text-[#C69C6D]">Mulai Pesananmu</h3>
                    <p class="text-sm text-gray-300 mb-6">Nikmati pengalaman ngopi terbaik hari ini.</p>

                    <!-- TOMBOL PINDAH HALAMAN -->
                    <a href="pilih_series.php" class="block w-full bg-[#C69C6D] hover:bg-[#b0885e] text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 uppercase tracking-wider">
                        ☕ Buat Pesanan Sekarang
                    </a>

                </div>
            </div>

        </div>
    </section>

</body>
</html>