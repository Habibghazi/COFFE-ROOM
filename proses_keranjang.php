<?php
// proses_keranjang.php
session_start();

// 1. Cek apakah ada data yang dikirim?
if (isset($_POST['add_to_cart'])) {
    
    $id_produk = $_POST['id_produk'];
    $nama      = $_POST['nama_produk'];
    $harga     = $_POST['harga'];
    $suhu      = $_POST['suhu']; // Hot atau Ice
    
    // 2. Siapkan array item
    $item = [
        'id'    => $id_produk,
        'nama'  => $nama,
        'harga' => $harga,
        'suhu'  => $suhu,
        'qty'   => 1
    ];

    // 3. Masukkan ke Session Keranjang
    // Cek apakah keranjang sudah ada?
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Cek apakah barang yang sama persis (ID + Suhu) sudah ada?
    $found = false;
    foreach ($_SESSION['keranjang'] as $key => $val) {
        if ($val['id'] == $id_produk && $val['suhu'] == $suhu) {
            // Kalau sudah ada, tambahkan jumlahnya (qty) aja
            $_SESSION['keranjang'][$key]['qty'] += 1;
            $found = true;
            break;
        }
    }

    // Kalau barang baru, masukkan ke antrian
    if (!$found) {
        $_SESSION['keranjang'][] = $item;
    }

    // 4. Balikin user ke halaman menu lagi
    // Kita pakai script JS biar bisa go back history (tetap di filter yang sama)
    echo "<script>
            alert('✅ Berhasil masuk keranjang: $nama ($suhu)');
            window.history.back();
          </script>";
}
?>