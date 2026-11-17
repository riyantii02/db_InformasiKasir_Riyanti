<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

$nama = trim($_POST['nama_barang'] ?? '');
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$harga = (float)($_POST['harga'] ?? 0);
$stok = (int)($_POST['stok'] ?? 0);

if ($nama == '' || $id_kategori <= 0 || $harga < 0 || $stok < 0) {
    die("Data barang tidak valid.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO tb_barang
    (id_kategori, nama_barang, harga, stok)
    VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "isdi",
    $id_kategori,
    $nama,
    $harga,
    $stok
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: barang.php");
    exit;
} else {
    echo "Gagal menyimpan barang: " . mysqli_error($koneksi);
}
?>