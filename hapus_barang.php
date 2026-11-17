<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM tb_barang WHERE id_barang = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    die("Barang tidak bisa dihapus karena sudah digunakan dalam transaksi.");
}

header("Location: barang.php");
exit;
?>