<?php
// admin/transaksi/cetak_struk.php
include '../Koneksi.php';

$id = $_GET['id'];

// Ambil data order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = :id");
$stmt->execute([':id' => $id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) die("Pesanan tidak ditemukan.");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran #<?= $id ?></title>
    <style>
        /* Reset CSS biar rapi saat diprint */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            background: #e0e0e0; 
            font-family: 'Courier New', Courier, monospace; /* Font Mesin Kasir */
            padding-top: 30px;
        }

        .struk-wrapper {
            width: 320px; /* Ukuran standar kertas thermal */
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Hiasan gerigi kertas di bawah (opsional, biar keren) */
        .struk-wrapper::after {
            content: "";
            position: absolute;
            left: 0; bottom: -10px;
            width: 100%;
            height: 10px;
            background: radial-gradient(circle, transparent 70%, #fff 70%) 0 0;
            background-size: 20px 20px;
            transform: rotate(180deg);
        }

        header { text-align: center; margin-bottom: 20px; }
        h1 { font-size: 24px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; }
        p.alamat { font-size: 10px; color: #555; line-height: 1.4; }

        .divider { border-bottom: 2px dashed #000; margin: 15px 0; }
        
        .info-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; }
        .info-label { color: #555; }
        .info-value { font-weight: bold; text-align: right; max-width: 60%; }

        .total-section { 
            background: #eee; 
            padding: 10px; 
            border-radius: 5px; 
            margin-top: 10px; 
        }
        .total-row { display: flex; justify-content: space-between; align-items: center; }
        .total-label { font-size: 14px; font-weight: bold; }
        .total-value { font-size: 20px; font-weight: 900; }

        .status-box {
            text-align: center;
            border: 2px solid #000;
            padding: 5px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        footer { text-align: center; font-size: 10px; color: #777; margin-top: 30px; }

        /* Konfigurasi Khusus Print */
        @media print {
            body { background: none; padding: 0; }
            .struk-wrapper { box-shadow: none; width: 100%; margin: 0; }
            /* Sembunyikan header/footer bawaan browser jika bisa */
            @page { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="struk-wrapper">
        
        <!-- Header Toko -->
        <header>
            <h1>COFFEE ROOM</h1>
            <p class="alamat">
                Jl. Kopi Nikmat No. 88, Jakarta Selatan<br>
                0812-3456-7890 | @coffeeroom.id
            </p>
        </header>

        <!-- Garis Putus-putus -->
        <div class="divider"></div>

        <!-- Detail Order -->
        <div class="info-row">
            <span class="info-label">No. Order</span>
            <span class="info-value">#ORD-<?= str_pad($order['id'], 3, '0', STR_PAD_LEFT) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal</span>
            <span class="info-value"><?= date('d/m/Y', strtotime($order['order_date'])) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Waktu</span>
            <span class="info-value"><?= date('H:i', strtotime($order['order_date'])) ?> WIB</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pelanggan</span>
            <span class="info-value"><?= htmlspecialchars($order['customer_name']) ?></span>
        </div>

        <div class="divider"></div>

        <!-- Rincian Pembayaran -->
        <div style="font-size: 12px; margin-bottom: 5px;">Rincian:</div>
        <div class="info-row">
            <span>Paket Minuman (All Items)</span>
            <span>1 Paket</span>
        </div>

        <div class="total-section">
            <div class="total-row">
                <span class="total-label">TOTAL BAYAR</span>
            </div>
            <div class="total-row" style="margin-top: 5px;">
                <span class="total-value">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Status Lunas -->
        <div class="status-box">
            <?= $order['status'] == 'success' ? 'LUNAS / PAID' : 'BELUM LUNAS' ?>
        </div>

        <!-- Footer -->
        <footer>
            <p>Terima kasih sudah berbelanja!</p>
            <p>Simpan struk ini sebagai bukti pembayaran.</p>
            <p style="margin-top: 10px;">*** COFFEE ROOM ***</p>
        </footer>

    </div>

</body>
</html>