<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

$data = mysqli_query(
    $koneksi,
    "SELECT p.*, u.nama_user, pl.nama_pelanggan
     FROM tb_penjualan p
     JOIN tb_user u ON p.id_user = u.id_user
     LEFT JOIN tb_pelanggan pl
     ON p.id_pelanggan = pl.id_pelanggan
     ORDER BY p.id_penjualan DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Penjualan</title>
    <link rel="stylesheet" href="src/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="header">
    <h1>Data Penjualan</h1>
    <p>Riwayat transaksi</p>
</div>

<div class="container">
    <div class="card">

        <?php if (isset($_GET['berhasil'])): ?>
            <p>Transaksi berhasil disimpan!</p>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Nominal</th>
                    <th>Kembalian</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $row['id_penjualan']; ?></td>
                    <td><?= $row['tanggal_penjualan']; ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['nama_user']); ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td>Rp <?= number_format($row['nominal'], 0, ',', '.'); ?></td>
                    <td>Rp <?= number_format($row['kembalian'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>

            </table>
        </div>

        <br>
        <a class="btn" href="penjualan.php">Tambah Transaksi</a>
        <a class="back" href="index.php">← Kembali ke Dashboard</a>

    </div>
</div>

</body>
</html>