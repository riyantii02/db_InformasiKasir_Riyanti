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
    "DELETE FROM tb_kategori WHERE id_kategori = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    die("Kategori tidak bisa dihapus karena masih digunakan barang.");
}

header("Location: kategori.php");
exit;
?>