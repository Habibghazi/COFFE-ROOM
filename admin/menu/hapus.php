<?php
// admin/menu/hapus.php
include '../Koneksi.php';

// Cek apakah ada ID di URL (misal: hapus.php?id=5)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // (Opsional) Hapus juga gambar fisiknya dari folder uploads biar hemat memori
    // 1. Ambil nama gambar dulu
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();

    // 2. Jika ada gambar, hapus filenya
    if ($data && $data['image']) {
        $fileGambar = "../../uploads/" . $data['image'];
        if (file_exists($fileGambar)) {
            unlink($fileGambar); // Perintah hapus file
        }
    }

    // 3. Hapus data dari database
    $query = $conn->prepare("DELETE FROM products WHERE id = :id");
    if ($query->execute([':id' => $id])) {
        echo "<script>alert('Menu berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus!'); window.location='index.php';</script>";
    }
}
?>