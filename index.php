<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Siswa</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f0f0f0; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="file"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"], .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }
        input[type="submit"]:hover, .btn:hover {
            background-color: #0056b3;
        }
        .btn-edit { background-color: #ffc107; }
        .btn-edit:hover { background-color: #e0a800; }
        .btn-hapus { background-color: #dc3545; }
        .btn-hapus:hover { background-color: #c82333; }
        img.foto-siswa { max-width: 80px; max-height: 80px; border-radius: 4px; }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Input Data Siswa</h2>

    <?php
    session_start(); // Mulai session untuk menampilkan notifikasi
    if (isset($_SESSION['pesan'])) {
        echo '<div class="alert alert-' . $_SESSION['tipe_pesan'] . '">' . $_SESSION['pesan'] . '</div>';
        unset($_SESSION['pesan']);
        unset($_SESSION['tipe_pesan']);
    }
    ?>

    <form action="tambah_aksi.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" required>
        </div>
        <div class="form-group">
            <label for="no_absen">No. Absen:</label>
            <input type="number" id="no_absen" name="no_absen" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto" accept="image/*" required>
        </div>
        <input type="submit" value="Tambah Data">
    </form>

    <hr style="margin-top: 30px; margin-bottom: 30px;">

    <h2>Data Siswa</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>No. Absen</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'koneksi.php';
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM tb_siswa ORDER BY nama ASC");
            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                <td><?php echo htmlspecialchars($data['kelas']); ?></td>
                <td><?php echo htmlspecialchars($data['no_absen']); ?></td>
                <td>
                    <?php if (!empty($data['foto']) && file_exists("uploads/" . $data['foto'])) : ?>
                        <img src="uploads/<?php echo htmlspecialchars($data['foto']); ?>" alt="Foto <?php echo htmlspecialchars($data['nama']); ?>" class="foto-siswa">
                    <?php else : ?>
                        Tidak ada foto
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit.php?id=<?php echo $data['id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="hapus.php?id=<?php echo $data['id']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6" style="text-align:center;">Tidak ada data.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>