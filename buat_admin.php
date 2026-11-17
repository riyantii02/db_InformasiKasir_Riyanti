<?php
include "src/koneksi.php";

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$nama_user = "Administrator";

$query = mysqli_prepare($koneksi,
    "INSERT INTO tb_user (username, password, nama_user)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE
     password = VALUES(password),
     nama_user = VALUES(nama_user)"
);

mysqli_stmt_bind_param(
    $query,
    "sss",
    $username,
    $password,
    $nama_user
);

if (mysqli_stmt_execute($query)) {
    echo "Akun admin berhasil dibuat!";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>