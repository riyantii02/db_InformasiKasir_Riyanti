<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

$barang = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_barang WHERE stok > 0"
);

$pelanggan = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_pelanggan ORDER BY nama_pelanggan"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penjualan</title>
    <link rel="stylesheet" href="src/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="header">
    <h1>Transaksi Penjualan</h1>
</div>

<div class="container">
    <div class="card">

        <h2>Form Penjualan</h2>

        <form action="tambah_penjualan.php" method="POST">

            <div class="form-group">
                <label>Pelanggan (Opsional)</label>
                <select name="id_pelanggan">
                    <option value="">-- Tanpa Pelanggan --</option>

                    <?php while ($p = mysqli_fetch_assoc($pelanggan)): ?>
                    <option value="<?= $p['id_pelanggan']; ?>">
                        <?= htmlspecialchars($p['nama_pelanggan']); ?>
                    </option>
                    <?php endwhile; ?>

                </select>
            </div>

            <div class="form-group">
                <label>Barang</label>
                <select name="id_barang" required>
                    <option value="">-- Pilih Barang --</option>

                    <?php while ($b = mysqli_fetch_assoc($barang)): ?>
                    <option value="<?= $b['id_barang']; ?>">
                        <?= htmlspecialchars($b['nama_barang']); ?>
                        - Rp <?= number_format($b['harga'], 0, ',', '.'); ?>
                        (Stok: <?= $b['stok']; ?>)
                    </option>
                    <?php endwhile; ?>

                </select>
            </div>

            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" min="1" required>
            </div>

            <div class="form-group">
                <label>Nominal Bayar</label>
                <input type="number" name="nominal" min="0" required>
            </div>

            <button type="submit">Simpan Penjualan</button>

        </form>

        <a class="back" href="data_penjualan.php">Data Penjualan</a><br>
        <a class="back" href="index.php">← Kembali ke Dashboard</a>

    </div>
</div>

</body>
</html>