<?php
// FILE: csindex.php
// Hubungkan ke database (jalurnya masuk ke folder admin)
// Gunakan @ agar tidak error fatal jika file belum ada, tapi pastikan Koneksi.php ada di folder admin
if (file_exists('admin/Koneksi.php')) {
    include 'admin/Koneksi.php';
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth"> <!-- scroll-smooth bikin gerakannya halus -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Room - Premium Taste</title>
    
    <!-- Font Keren (Playfair Display & Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        
        /* Animasi Kustom */
        .fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-200 { animation-delay: 0.2s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">

    <!-- NAVBAR (Menu Atas) -->
    <nav class="absolute top-0 w-full p-6 flex justify-between items-center z-50">
        <div class="text-2xl font-bold tracking-widest text-[#C69C6D]">
            COFFEE ROOM
        </div>
        <div>
            <!-- Tombol Login Admin -->
            <a href="admin/dashboard.php" class="text-xs text-gray-400 hover:text-white transition uppercase tracking-widest border border-gray-600 px-4 py-2 rounded-full hover:border-white">
                Admin Area
            </a>
        </div>
    </nav>

    <!-- 1. HERO SECTION (Layar Utama) -->
    <section class="relative h-screen flex items-center justify-center">
        
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover opacity-60">
            <!-- Overlay Hitam -->
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

            <!-- THE ORDER BOX -->
            <div class="fade-in-up delay-500">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl max-w-md mx-auto transform hover:scale-105 transition duration-500">
                    
                    <h3 class="text-2xl font-semibold mb-4 text-[#C69C6D]">Mulai Pesananmu</h3>
                    
                    <p class="text-sm text-gray-300 mb-6">
                        Pilih kopi favoritmu dari Black Series atau White Series kami.
                    </p>

                    <!-- Tombol Pesan (Scroll ke bawah) -->
                    <a href="#menu-pilihan" class="block w-full bg-[#C69C6D] hover:bg-[#b0885e] text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 uppercase tracking-wider">
                        ☕ Buat Pesanan Sekarang
                    </a>

                </div>
            </div>

        </div>

        <!-- Panah Bawah -->
        <div class="absolute bottom-10 animate-bounce">
            <a href="#menu-pilihan" class="text-gray-400 hover:text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </a>
        </div>

    </section>

    <!-- 2. SECTION MENU PILIHAN (Black vs White) -->
    <section id="menu-pilihan" class="min-h-screen bg-[#0a0a0a] py-20 px-4 md:px-20 border-t border-gray-900">
        
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl text-[#C69C6D] mb-4">Our Signature Series</h2>
            <p class="text-gray-400">Pilih karakter kopi yang sesuai dengan mood kamu hari ini.</p>
        </div>

        <!-- GRID 2 KARTU BESAR -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">

            <!-- KARTU 1: BLACK SERIES -->
            <div class="group relative bg-[#111] border border-gray-800 rounded-3xl overflow-hidden hover:border-[#C69C6D] transition duration-500 shadow-2xl">
                <!-- Gambar -->
                <div class="h-80 overflow-hidden relative">
                    <img src="WhatsApp Image 2026-01-10 at 20.23.49.jpeg" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#111] to-transparent"></div>
                </div>
                
                <!-- Teks -->
                <div class="p-8 text-center relative -mt-20 z-10">
                    <div class="bg-black w-20 h-20 mx-auto rounded-full flex items-center justify-center border-4 border-[#111] shadow-xl mb-4 group-hover:border-[#C69C6D] transition">
                        <span class="text-4xl">☕</span>
                    </div>
                    
                    <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-[#C69C6D] transition">BLACK SERIES</h3>
                    <p class="text-gray-400 text-sm mb-8 px-4 leading-relaxed">
                        Kuat, Berani, dan Murni.<br>Untuk kamu yang butuh energi ekstra tanpa basa-basi.
                    </p>
                    
                    <!-- Tombol Arahkan ke Menu List -->
                    <a href="menu_list.php?kategori=Black Series" class="inline-block border border-[#C69C6D] text-[#C69C6D] px-8 py-3 rounded-full hover:bg-[#C69C6D] hover:text-white transition uppercase text-xs tracking-[0.2em] font-bold">
                        LIHAT MENU BLACK
                    </a>
                </div>
            </div>

            <!-- KARTU 2: WHITE SERIES -->
            <div class="group relative bg-[#111] border border-gray-800 rounded-3xl overflow-hidden hover:border-white transition duration-500 shadow-2xl">
                <!-- Gambar -->
                <div class="h-80 overflow-hidden relative">
                    <img src="WhatsApp Image 2026-01-10 at 20.23.18.jpeg" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-70 group-hover:opacity-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#111] to-transparent"></div>
                </div>
                
                <!-- Teks -->
                <div class="p-8 text-center relative -mt-20 z-10">
                    <div class="bg-white w-20 h-20 mx-auto rounded-full flex items-center justify-center border-4 border-[#111] shadow-xl mb-4 group-hover:border-gray-300 transition">
                        <span class="text-4xl">🥛</span>
                    </div>
                    
                    <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-gray-300 transition">WHITE SERIES</h3>
                    <p class="text-gray-400 text-sm mb-8 px-4 leading-relaxed">
                        Lembut, Creamy, dan Manis.<br>Teman santai yang sempurna untuk menenangkan pikiran.
                    </p>
                    
                    <!-- Tombol Arahkan ke Menu List -->
                    <a href="menu_list.php?kategori=White Series" class="inline-block border border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-black transition uppercase text-xs tracking-[0.2em] font-bold">
                        LIHAT MENU WHITE
                    </a>
                </div>
            </div>

        </div>

    </section>

</body>
</html>