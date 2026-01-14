<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Series - Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h2, h3 { font-family: 'Playfair Display', serif; }
        
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<!-- BACKGROUND IMAGE DI SINI -->
<body class="bg-[url('pexels-rachel-claire-5864208.jpg')] bg-cover bg-center bg-fixed text-white min-h-screen flex flex-col relative">

    <!-- OVERLAY GELAP (Biar tulisan kebaca) -->
    <div class="absolute inset-0 bg-black/80 z-0"></div>

    <!-- WRAPPER KONTEN (Supaya konten ada di atas overlay) -->
    <div class="relative z-10 flex flex-col min-h-screen w-full">

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
                    setTimeout(() => { loader.style.display = 'none'; }, 700);
                }, 500);
            });
        </script>

        <!-- Navbar Simpel -->
        <nav class="p-6">
            <a href="csindex.php" class="flex items-center gap-2 text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Home
            </a>
        </nav>

        <!-- KONTEN UTAMA -->
        <div class="flex-1 flex flex-col justify-center items-center px-6 py-10">
            
            <div class="text-center mb-12 animate-fade-in-up">
                <h2 class="text-4xl md:text-5xl text-[#C69C6D] mb-4">Our Signature Series</h2>
                <p class="text-gray-300">Pilih karakter kopi yang sesuai dengan mood kamu hari ini.</p>
            </div>

            <!-- GRID 2 KARTU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl w-full">

                <!-- KARTU BLACK SERIES -->
                <a href="menu_list.php?kategori=Black Series" class="group relative bg-[#111] border border-gray-800 rounded-3xl overflow-hidden hover:border-[#C69C6D] transition duration-500 shadow-2xl block h-96">
                    <div class="absolute inset-0">
                        <img src="WhatsApp Image 2026-01-10 at 20.23.49.jpeg" 
                             class="w-full h-full object-cover opacity-50 group-hover:scale-110 group-hover:opacity-70 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                    </div>
                    <div class="absolute bottom-0 w-full p-8 text-center">
                        <div class="bg-black w-16 h-16 mx-auto rounded-full flex items-center justify-center border-2 border-[#C69C6D] mb-4 shadow-lg group-hover:bg-[#C69C6D] group-hover:text-white transition">
                            <span class="text-2xl">☕</span>
                        </div>
                        <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-[#C69C6D] group-hover:text-white transition">BLACK SERIES</h3>
                        <p class="text-gray-400 text-sm group-hover:text-gray-200">Strong & Pure Energy</p>
                    </div>
                </a>

                <!-- KARTU WHITE SERIES -->
                <a href="menu_list.php?kategori=White Series" class="group relative bg-[#111] border border-gray-800 rounded-3xl overflow-hidden hover:border-white transition duration-500 shadow-2xl block h-96">
                    <div class="absolute inset-0">
                        <img src="WhatsApp Image 2026-01-10 at 20.23.18.jpeg" 
                             class="w-full h-full object-cover opacity-50 group-hover:scale-110 group-hover:opacity-70 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                    </div>
                    <div class="absolute bottom-0 w-full p-8 text-center">
                        <div class="bg-white w-16 h-16 mx-auto rounded-full flex items-center justify-center border-2 border-gray-300 mb-4 shadow-lg group-hover:scale-110 transition text-black">
                            <span class="text-2xl">🥛</span>
                        </div>
                        <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-gray-300 transition">WHITE SERIES</h3>
                        <p class="text-gray-400 text-sm group-hover:text-gray-200">Creamy & Smooth</p>
                    </div>
                </a>

            </div>

        </div>

    </div> <!-- Penutup Wrapper -->

</body>
</html>