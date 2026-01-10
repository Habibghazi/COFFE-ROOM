<?php
// admin/menu/edit.php
include '../Koneksi.php';

// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Ambil data produk berdasarkan ID itu
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data tidak ketemu, tendang balik
if (!$data) {
    header("Location: index.php");
    exit;
}

// 3. Jika Tombol UPDATE ditekan
if (isset($_POST['update'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];
    
    // Logika Ganti Gambar
    $fotoName = $_FILES['foto']['name'];
    
    if ($fotoName != "") {
        // Kalau user upload gambar baru
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $newFotoName = time() . '_' . $fotoName;
        move_uploaded_file($fotoTmp, '../../uploads/' . $newFotoName);
        
        // Update data DENGAN gambar baru
        $sql = "UPDATE products SET name=:name, price=:price, description=:desc, image=:img WHERE id=:id";
        $params = [':name'=>$name, ':price'=>$price, ':desc'=>$desc, ':img'=>$newFotoName, ':id'=>$id];
    } else {
        // Kalau user TIDAK upload gambar (pakai gambar lama)
        $sql = "UPDATE products SET name=:name, price=:price, description=:desc WHERE id=:id";
        $params = [':name'=>$name, ':price'=>$price, ':desc'=>$desc, ':id'=>$id];
    }

    $stmt = $conn->prepare($sql);
    if ($stmt->execute($params)) {
        echo "<script>alert('Data Berhasil Diupdate!'); window.location='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-6">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">✏️ Edit Menu</h2>

        <form method="POST" enctype="multipart/form-data">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Menu</label>
                <input type="text" name="name" value="<?= $data['name'] ?>" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Harga</label>
                <input type="number" name="price" value="<?= $data['price'] ?>" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border p-2 rounded"><?= $data['description'] ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Ganti Foto (Opsional)</label>
                <?php if($data['image']): ?>
                    <img src="../../uploads/<?= $data['image'] ?>" class="w-20 h-20 object-cover mb-2 rounded">
                <?php endif; ?>
                <input type="file" name="foto" class="w-full text-sm">
                <p class="text-xs text-gray-400 mt-1">*Biarkan kosong jika tidak ingin mengganti gambar</p>
            </div>

            <div class="flex gap-2">
                <button type="submit" name="update" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Update Data
                </button>
                <a href="index.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded w-1/3 text-center">
                    Batal
                </a>
            </div>

        </form>
    </div>

</body>
</html>