<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="src/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div class="header">
    <h1>Sistem Informasi Kasir</h1>
    <p>
        Selamat datang,
        <?= htmlspecialchars($_SESSION['nama_user']); ?>
    </p>
</div>

<div class="dashboard">

    <h2>Menu Utama</h2>

    <div class="menu">

        <div class="menu-card">
            <h3>Kategori</h3>
            <a href="kategori.php">Kelola Kategori</a>
        </div>

        <div class="menu-card">
            <h3>Barang</h3>
            <a href="barang.php">Kelola Barang</a>
        </div>

        <div class="menu-card">
            <h3>Penjualan</h3>
            <a href="penjualan.php">Transaksi Penjualan</a>
        </div>

        <div class="menu-card logout">
            <h3>Logout</h3>
            <a href="logout.php">Keluar</a>
        </div>

    </div>

    <p><a href="data_penjualan.php">Data Penjualan</a></p>

</div>

</body>
</html>