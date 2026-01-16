<?php
// admin/menu/tambah.php
include '../Koneksi.php';
session_start();
// Cek Login & Admin
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php"); // Mundur 2 langkah ke login utama
    exit;
}

if (isset($_POST['simpan'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];
    $kategori = $_POST['category']; 
    
    // --- BAGIAN UPLOAD GAMBAR ---
    $fotoName = $_FILES['foto']['name']; // Nama file asli
    $fotoTmp  = $_FILES['foto']['tmp_name']; // Lokasi sementara
    $newFotoName = null;
    
    if(!empty($fotoName)){
        // Bikin nama unik biar gak bentrok (pake waktu)
        $newFotoName = time() . '_' . $fotoName;
        
        // Pindahkan dari folder sementara ke folder uploads
        // Path '../../uploads/' artinya mundur 2 folder (keluar dari menu -> keluar dari admin -> masuk uploads)
        $uploadPath = '../../uploads/' . $newFotoName;
        
        if (move_uploaded_file($fotoTmp, $uploadPath)) {
            // Berhasil upload
        } else {
            echo "<script>alert('Gagal upload gambar! Cek permission folder.');</script>";
        }
    }
    // ---------------------------

    try {
        $sql = "INSERT INTO products (name, price, description, image, category) VALUES (:name, :price, :desc, :img, :kat)";
        $stmt = $conn->prepare($sql);
        $data = [
            ':name' => $name,
            ':price' => $price,
            ':desc' => $desc,
            ':img' => $newFotoName,
            ':kat' => $kategori
        ];
        $stmt->execute($data);
        echo "<script>alert('Menu Berhasil Ditambah!'); window.location='index.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Gagal Database: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-lg border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Menu Baru</h2>

        <!-- WAJIB ADA: enctype="multipart/form-data" -->
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Kategori</label>
                <select name="category" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none bg-white">
                    <option value="Black Series">☕ Black Series</option>
                    <option value="White Series">🥛 White Series</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto</label>
                <input type="file" name="foto" class="w-full text-sm text-gray-500">
            </div>

            <button type="submit" name="simpan" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-lg mt-2">
                Simpan Menu
            </button>
        </form>
    </div>
</body>
</html>