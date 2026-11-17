<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/src/koneksi.php";

$kategori = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_kategori ORDER BY nama_kategori ASC"
);

$data = mysqli_query(
    $koneksi,
    "SELECT tb_barang.*, tb_kategori.nama_kategori
     FROM tb_barang
     JOIN tb_kategori
     ON tb_barang.id_kategori = tb_kategori.id_kategori
     ORDER BY tb_barang.id_barang DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link rel="stylesheet" href="src/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="header">
    <h1>Data Barang</h1>
    <p>Kelola data barang</p>
</div>

<div class="container">

    <div class="card">
        <h2>Tambah Barang</h2>

        <form action="tambah_barang.php" method="POST">

            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>

                <select name="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>

                    <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                    <option value="<?= $k['id_kategori']; ?>">
                        <?= htmlspecialchars($k['nama_kategori']); ?>
                    </option>
                    <?php endwhile; ?>

                </select>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" min="0" required>
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" min="0" required>
            </div>

            <button type="submit">Simpan Barang</button>

        </form>
    </div>

    <div class="card">
        <h2>Daftar Barang</h2>

        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $row['id_barang']; ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= $row['stok']; ?></td>
                    <td>
                        <a class="btn btn-danger"
                           href="hapus_barang.php?id=<?= $row['id_barang']; ?>"
                           onclick="return confirm('Hapus barang?')">
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