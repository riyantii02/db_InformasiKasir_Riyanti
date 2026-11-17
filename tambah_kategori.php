<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

$nama = trim($_POST['nama_kategori'] ?? '');

if ($nama == '') {
    die("Nama kategori wajib diisi.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO tb_kategori (nama_kategori) VALUES (?)"
);

mysqli_stmt_bind_param($stmt, "s", $nama);

if (mysqli_stmt_execute($stmt)) {
    header("Location: kategori.php");
    exit;
} else {
    echo "Gagal menyimpan kategori.";
}
?>