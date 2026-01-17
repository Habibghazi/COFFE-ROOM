<?php
include 'admin/Koneksi.php';

// CEK STATUS TOKO
$shopStatus = $conn->query("SELECT setting_value FROM settings WHERE setting_key = 'shop_status'")->fetchColumn();

if ($shopStatus == 'closed') {
    header("Location: tutup.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Coffee Room</title>
    
    <!-- Font Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        /* Animasi Background Zoom Pelan */
        @keyframes zoomBg {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        .animate-bg { animation: zoomBg 20s infinite alternate ease-in-out; }

        /* Animasi Muncul */
        .fade-up { animation: fadeUp 1s ease-out forwards; opacity: 0; transform: translateY(30px); }
        .delay-100 { animation-delay: 0.2s; }
        .delay-200 { animation-delay: 0.4s; }
        .delay-300 { animation-delay: 0.6s; }
        
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-black h-screen overflow-hidden relative">

    <!-- BACKGROUND GAMBAR (Zoom Effect) -->
    <div class="absolute inset-0 z-0">
        <img src="coffee-2440074_1920.jpg" 
             class="w-full h-full object-cover opacity-60 animate-bg">
        <!-- Overlay Gradasi Gelap -->
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-black/30"></div>
    </div>

    <!-- KONTEN TENGAH -->
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-6">
        
        <!-- Logo Kecil / Tagline -->
        <div class="fade-up">
            <span class="px-4 py-1 rounded-full border border-[#C69C6D]/50 text-[#C69C6D] text-xs font-bold tracking-[0.2em] uppercase bg-black/20 backdrop-blur-sm">
                Premium Coffee Shop
            </span>
        </div>

        <!-- Judul Besar -->
        <h1 class="text-5xl md:text-7xl font-serif text-white mt-6 mb-4 fade-up delay-100 drop-shadow-2xl">
            Welcome to <br> <span class="italic text-[#C69C6D]">Coffee Room</span>
        </h1>

        <p class="text-gray-300 text-lg md:text-xl font-light tracking-wide max-w-lg mb-10 fade-up delay-200">
            Temukan cita rasa kopi terbaik dan suasana yang menenangkan di setiap tegukan.
        </p>

        <!-- Tombol Aksi (Sign In & Register) -->
        <div class="flex flex-col md:flex-row gap-4 w-full max-w-md fade-up delay-300">
            
            <!-- Tombol LOGIN / SIGN IN -->
            <a href="login.php" class="group flex-1 bg-[#C69C6D] hover:bg-[#b0885e] text-white py-4 rounded-xl font-bold tracking-wider transition-all transform hover:-translate-y-1 shadow-[0_0_20px_rgba(198,156,109,0.3)] text-center">
                SIGN IN
            </a>

            <!-- Tombol REGISTER -->
            <a href="register.php" class="group flex-1 bg-white/10 hover:bg-white text-white hover:text-black border border-white/20 hover:border-white py-4 rounded-xl font-bold tracking-wider backdrop-blur-md transition-all transform hover:-translate-y-1 text-center">
                REGISTER
            </a>

        </div>

        <!-- Footer Kecil -->
        <div class="absolute bottom-8 text-xs text-gray-500 tracking-widest fade-up delay-300">
            © 2025 COFFEE ROOM.
        </div>

    </div>

</body>
</html>