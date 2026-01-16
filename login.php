<?php
// login.php
session_start();
include 'admin/Koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    // Cek User
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Cek Password (disini masih plain text, nanti bisa diupgrade pakai password_verify)
        if ($pass == $user['password']) {
            
            // SET SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_login'] = true; // Flag login

            // CEK ROLE: ADMIN ATAU CUSTOMER?
            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php"); // Ke Admin Panel
            } else {
                header("Location: csindex.php"); // Ke Halaman Pelanggan
            }
            exit;

        } else {
            $error = "Password Salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Coffee Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#0f0f0f] flex items-center justify-center h-screen bg-[url('pexels-lawrencesuzara-1581554.jpg')] bg-cover bg-center">
    
    <!-- Overlay Gelap -->
    <div class="absolute inset-0 bg-black/70"></div>

    <div class="relative z-10 bg-black/50 backdrop-blur-lg border border-white/10 p-8 rounded-2xl shadow-2xl w-full max-w-sm text-white transform hover:scale-105 transition duration-500">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-widest text-[#C69C6D] mb-1">COFFEE ROOM</h1>
            <p class="text-xs text-gray-400 uppercase tracking-widest">Welcome Back</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-500/20 text-red-200 p-3 rounded mb-4 text-sm text-center border border-red-500/50"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Email</label>
                <input type="email" name="email" required placeholder="name@example.com"
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#C69C6D] transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Password</label>
                <input type="password" name="password" required placeholder="••••••"
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#C69C6D] transition">
            </div>

            <button type="submit" name="login" class="w-full bg-[#C69C6D] hover:bg-[#b0885e] text-white font-bold py-3 rounded-lg transition shadow-lg">
                MASUK
            </button>
        </form>

        <div class="mt-6 text-center pt-6 border-t border-gray-700">
            <p class="text-sm text-gray-400">Belum punya akun?</p>
            <a href="register.php" class="inline-block mt-2 text-white border border-white/30 px-4 py-2 rounded-full text-xs font-bold hover:bg-white hover:text-black transition">
                DAFTAR SEKARANG
            </a>
        </div>
    </div>

</body>
</html>