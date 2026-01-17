<?php
// tutup.php
include 'admin/Koneksi.php';

// Cek status toko di database
$shopStatus = $conn->query("SELECT setting_value FROM settings WHERE setting_key = 'shop_status'")->fetchColumn();

// Jika admin sudah buka toko lagi, otomatis balik ke welcome.php
if ($shopStatus == 'open') {
    header("Location: welcome.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Tutup - Coffee Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000; }
        h1 { font-family: 'Playfair Display', serif; }
        
        .fade-in { animation: fadeIn 2s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="text-white h-screen overflow-hidden">

    <!-- BACKGROUND IMAGE DENGAN OVERLAY -->
    <div class="absolute inset-0 z-0">
        <!-- Kamu bisa ganti URL gambar di bawah ini dengan gambar kopimu sendiri -->
        <img src="https://images.unsplash.com/photo-1507133750040-4a8f57021571?q=80&w=1974&auto=format&fit=crop" 
             class="w-full h-full object-cover opacity-40">
        <!-- Efek Gelap (Overlay) agar tulisan tetap jelas -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-black"></div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="relative z-10 h-full flex flex-col items-center justify-center p-6 text-center fade-in">
        <!-- Icon Minimalis -->
       
        
        <!-- Judul -->
        <h1 class="text-4xl md:text-6xl font-bold text-[#C69C6D] mb-4 tracking-tight">
            Toko Sedang Tutup
        </h1>
        
        <p class="text-gray-400 text-sm md:text-base mb-12 tracking-[0.3em] uppercase font-light">
            Silakan cek kembali beberapa saat lagi
        </p>

        <!-- Tombol Refresh -->
        <a href="tutup.php" class="group relative inline-flex items-center justify-center px-10 py-3 font-medium transition-all duration-500">
            <span class="absolute inset-0 border border-gray-700 rounded-full group-hover:border-[#C69C6D] transition-all duration-500"></span>
            <span class="relative text-gray-400 group-hover:text-[#C69C6D] text-xs tracking-[0.2em] uppercase">Refresh Halaman</span>
        </a>
    </div>

    <!-- Dekorasi Bawah -->
    <div class="relative z-10 pb-10 w-full text-center">
        <p class="text-[9px] text-gray-600 tracking-[0.8em] uppercase">Coffee Room Premium Experience</p>
    </div>

</body>
</html>