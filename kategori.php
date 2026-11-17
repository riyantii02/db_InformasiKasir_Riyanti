<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_kategori ORDER BY id_kategori DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori</title>
    <link rel="stylesheet" href="src/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="header">
    <h1>Data Kategori</h1>
    <p>Kelola kategori barang</p>
</div>

<div class="container">

    <div class="card">
        <h2>Tambah Kategori</h2>

        <form action="tambah_kategori.php" method="POST">

            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text"
                       name="nama_kategori"
                       placeholder="Masukkan nama kategori"
                       required>
            </div>

            <button type="submit">Simpan Kategori</button>

        </form>
    </div>

    <div class="card">
        <h2>Daftar Kategori</h2>

        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $row['id_kategori']; ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                    <td>
                        <a class="btn btn-danger"
                           href="hapus_kategori.php?id=<?= $row['id_kategori']; ?>"
                           onclick="return confirm('Hapus kategori?')">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>

            </table>
        </div>

        <a class="back" href="index.php">← Kembali ke Dashboard</a>
    </div>

</div>

</body>
</html>