<?php
// File: admin/Koneksi.php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_coffee_room"; // Pastikan nama database ini sudah dibuat di phpMyAdmin

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Pesan kalau berhasil (Nanti bisa dihapus/di-comment)
    

} catch (PDOException $e) {
    echo "<h1>❌ Koneksi Gagal</h1>";
    echo $e->getMessage();
}
?>