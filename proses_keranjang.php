<?php
// proses_keranjang.php
session_start();

if (isset($_POST['add_to_cart'])) {
    
    $id_produk = $_POST['id_produk'];
    $nama      = $_POST['nama_produk'];
    $harga     = $_POST['harga'];
    $suhu      = $_POST['suhu'];
    
    // TANGKAP QTY (Kalau tidak ada, default 1)
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    if ($qty < 1) $qty = 1;

    // Siapkan item
    $item = [
        'id'    => $id_produk,
        'nama'  => $nama,
        'harga' => $harga,
        'suhu'  => $suhu,
        'qty'   => $qty // Pakai jumlah dari inputan
    ];

    // Cek Keranjang
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Cek apakah barang sudah ada?
    $found = false;
    foreach ($_SESSION['keranjang'] as $key => $val) {
        if ($val['id'] == $id_produk && $val['suhu'] == $suhu) {
            // Kalau sudah ada, tambahkan jumlahnya sesuai input
            $_SESSION['keranjang'][$key]['qty'] += $qty;
            $found = true;
            break;
        }
    }

    // Kalau baru, masukkan
    if (!$found) {
        $_SESSION['keranjang'][] = $item;
    }

    // Redirect Balik
    echo "<script>
            alert('✅ Berhasil menambah $qty $nama ($suhu) ke keranjang!');
            window.history.back();
          </script>";
}
?>