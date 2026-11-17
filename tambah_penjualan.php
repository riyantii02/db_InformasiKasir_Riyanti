<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

// Mengambil data dari form
$id_barang = (int)($_POST['id_barang'] ?? 0);
$jumlah = (int)($_POST['jumlah'] ?? 0);
$nominal = (float)($_POST['nominal'] ?? 0);

$id_pelanggan = (
    isset($_POST['id_pelanggan']) &&
    $_POST['id_pelanggan'] !== ''
) ? (int)$_POST['id_pelanggan'] : null;

// Menambahkan tanggal dan waktu otomatis
$tanggal_penjualan = date('Y-m-d H:i:s');

// Validasi input
if ($id_barang <= 0 || $jumlah <= 0 || $nominal < 0) {
    die("Input transaksi tidak valid.");
}

mysqli_begin_transaction($koneksi);

try {

    // Mengambil harga dan stok barang
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT harga, stok FROM tb_barang
         WHERE id_barang = ? FOR UPDATE"
    );

    mysqli_stmt_bind_param($stmt, "i", $id_barang);
    mysqli_stmt_execute($stmt);

    $barang = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$barang) {
        throw new Exception("Barang tidak ditemukan.");
    }

    if ($barang['stok'] < $jumlah) {
        throw new Exception("Stok tidak mencukupi.");
    }

    // Menghitung total harga
    $harga = (float)$barang['harga'];
    $total = $harga * $jumlah;

    if ($nominal < $total) {
        throw new Exception("Nominal pembayaran kurang.");
    }

    // Menghitung kembalian
    $kembalian = $nominal - $total;

    $id_user = (int)$_SESSION['id_user'];

    // Menyimpan transaksi penjualan
    if ($id_pelanggan === null) {

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO tb_penjualan
            (tanggal_penjualan, id_pelanggan, id_user,
             total_harga, nominal, kembalian)
            VALUES (?, NULL, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt, "siddd",
            $tanggal_penjualan,
            $id_user,
            $total,
            $nominal,
            $kembalian
        );

    } else {

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO tb_penjualan
            (tanggal_penjualan, id_pelanggan, id_user,
             total_harga, nominal, kembalian)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt, "siiddd",
            $tanggal_penjualan,
            $id_pelanggan,
            $id_user,
            $total,
            $nominal,
            $kembalian
        );
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal menyimpan penjualan.");
    }

    // Mengambil ID penjualan
    $id_penjualan = mysqli_insert_id($koneksi);

    // Menyimpan detail penjualan
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_detail_penjualan
        (id_penjualan, id_barang, jumlah, harga, subtotal)
        VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt, "iiidd",
        $id_penjualan,
        $id_barang,
        $jumlah,
        $harga,
        $total
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal menyimpan detail penjualan.");
    }

    // Mengurangi stok barang
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tb_barang
         SET stok = stok - ?
         WHERE id_barang = ?"
    );

    mysqli_stmt_bind_param(
        $stmt, "ii",
        $jumlah,
        $id_barang
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal memperbarui stok.");
    }

    // Menyelesaikan transaksi
    mysqli_commit($koneksi);

    header("Location: data_penjualan.php?berhasil=1");
    exit;

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    die("Transaksi gagal: " .
        htmlspecialchars($e->getMessage()));
}
?>