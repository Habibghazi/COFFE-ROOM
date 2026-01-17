<?php
// admin/menu/edit.php
include '../Koneksi.php';

// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Ambil data produk lama
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: index.php");
    exit;
}

// 3. Jika Tombol UPDATE ditekan
if (isset($_POST['update'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];
    $kategori = $_POST['category']; // <--- Tangkap Kategori Baru
    
    // Logika Ganti Gambar
    $fotoName = $_FILES['foto']['name'];
    $params = [
        ':name' => $name, 
        ':price' => $price, 
        ':desc' => $desc, 
        ':kat' => $kategori, // <--- Masukkan parameter kategori
        ':id' => $id
    ];

    if ($fotoName != "") {
        // Kalau upload gambar baru
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $newFotoName = time() . '_' . $fotoName;
        move_uploaded_file($fotoTmp, '../../uploads/' . $newFotoName);
        
        $sql = "UPDATE products SET name=:name, price=:price, description=:desc, category=:kat, image=:img WHERE id=:id";
        $params[':img'] = $newFotoName;
    } else {
        // Kalau pakai gambar lama
        $sql = "UPDATE products SET name=:name, price=:price, description=:desc, category=:kat WHERE id=:id";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt->execute($params)) {
        echo "<script>alert('Data Berhasil Diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal Update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">✏️ Edit Menu</h2>
            <a href="index.php" class="text-gray-400 hover:text-gray-600">✕</a>
        </div>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu</label>
                <input type="text" name="name" value="<?= $data['name'] ?>" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none">
            </div>

            <!-- DROPDOWN KATEGORI (Otomatis Terpilih) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                <select name="category" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none bg-white">
                    <option value="Black Series" <?= ($data['category'] == 'Black Series') ? 'selected' : '' ?>>
                        ☕ Black Series (Hitam)
                    </option>
                    <option value="White Series" <?= ($data['category'] == 'White Series') ? 'selected' : '' ?>>
                        🥛 White Series (Susu)
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="<?= $data['price'] ?>" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none"><?= $data['description'] ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Saat Ini</label>
                <?php if($data['image']): ?>
                    <img src="../../uploads/<?= $data['image'] ?>" class="w-20 h-20 object-cover rounded mb-2 border">
                <?php endif; ?>
                <input type="file" name="foto" class="w-full text-sm text-gray-500">
                <p class="text-xs text-gray-400 mt-1">*Biarkan kosong jika tidak ingin mengganti gambar</p>
            </div>

            <button type="submit" name="update" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-md mt-2">
                Simpan Perubahan
            </button>

        </form>
    </div>

</body>
</html>