<?php
// register.php
include 'admin/Koneksi.php';

if (isset($_POST['register'])) {
    $nama  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = $_POST['password'];
    $pass2 = $_POST['confirm_password']; // Tangkap konfirmasi password
    
    // 1. Cek apakah password cocok?
    if ($pass !== $pass2) {
        $error = "Password tidak cocok! Silakan ulangi.";
    } else {
        // 2. Cek email kembar
        $cek = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $cek->execute([$email]);
        
        if ($cek->rowCount() > 0) {
            $error = "Email sudah terdaftar! Silakan login.";
        } else {
            // 3. Simpan User Baru
            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute([$nama, $email, $pass])) {
                echo "<script>
                    alert('Pendaftaran Berhasil! Silakan Login.'); 
                    window.location='login.php';
                </script>";
            } else {
                $error = "Gagal mendaftar. Coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun - Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#0f0f0f] flex items-center justify-center min-h-screen bg-[url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=2071&auto=format&fit=crop')] bg-cover bg-center py-10">
    
    <div class="absolute inset-0 bg-black/80"></div>

    <div class="relative z-10 bg-black/50 backdrop-blur-lg border border-white/10 p-8 rounded-2xl shadow-2xl w-full max-w-sm text-white transform hover:scale-105 transition duration-500">
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold tracking-widest text-[#C69C6D]">BUAT AKUN</h2>
            <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">Join Coffee Room Family</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-500/20 text-red-200 p-3 rounded mb-4 text-xs text-center border border-red-500/50">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            
            <!-- Nama -->
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#C69C6D] transition">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Email</label>
                <input type="email" name="email" required placeholder="budi@gmail.com"
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#C69C6D] transition">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Password</label>
                <input type="password" name="password" required placeholder="••••••"
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#C69C6D] transition">
            </div>

            <!-- Konfirmasi Password (BARU) -->
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Ulangi Password</label>
                <input type="password" name="confirm_password" required placeholder="••••••"
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#C69C6D] transition">
            </div>

            <button type="submit" name="register" class="w-full bg-[#C69C6D] hover:bg-[#b0885e] text-white font-bold py-3 rounded-lg transition shadow-lg mt-2">
                DAFTAR SEKARANG
            </button>
        </form>

        <div class="mt-6 text-center pt-4 border-t border-gray-700">
            <p class="text-xs text-gray-400">Sudah punya akun?</p>
            <a href="login.php" class="inline-block mt-2 text-[#C69C6D] hover:text-white text-xs font-bold transition uppercase tracking-widest">
                Login di sini &rarr;
            </a>
        </div>
    </div>

</body>
</html>