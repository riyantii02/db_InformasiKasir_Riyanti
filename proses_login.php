<?php
session_start();
require_once __DIR__ . "/src/koneksi.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM tb_user WHERE username = ?"
);

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {

    session_regenerate_id(true);

    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_user'] = $user['nama_user'];

    header("Location: index.php");
    exit;

} else {
    header("Location: login.php?error=1");
    exit;
}
?>