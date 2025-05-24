<?php
include 'koneksi.php';
session_start();

// Ambil ID dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['pesan'] = "ID siswa tidak ditemukan.";
    $_SESSION['tipe_pesan'] = "danger";
    header("location:index.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Query untuk mengambil data siswa berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    $_SESSION['pesan'] = "Data siswa tidak ditemukan.";
    $_SESSION['tipe_pesan'] = "danger";
    header("location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="file"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"], .btn-kembali {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        input[type="submit"]:hover, .btn-kembali:hover {
            background-color: #0056b3;
        }
        .foto-lama { max-width: 100px; max-height: 100px; display: block; margin-bottom: 10px; border-radius: 4px;}
        .info-foto { font-size: 0.9em; color: #666; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Data Siswa</h2>

    <form action="edit_aksi.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($data['foto']); ?>">

        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" value="<?php echo htmlspecialchars($data['kelas']); ?>" required>
        </div>
        <div class="form-group">
            <label for="no_absen">No. Absen:</label>
            <input type="number" id="no_absen" name="no_absen" value="<?php echo htmlspecialchars($data['no_absen']); ?>" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto Baru (Opsional):</label>
            <?php if (!empty($data['foto']) && file_exists("uploads/" . $data['foto'])) : ?>
                <img src="uploads/<?php echo htmlspecialchars($data['foto']); ?>" alt="Foto Lama" class="foto-lama">
            <?php else: ?>
                <p class="info-foto">Tidak ada foto lama.</p>
            <?php endif; ?>
            <input type="file" id="foto" name="foto" accept="image/*">
            <p class="info-foto">Kosongkan jika tidak ingin mengganti foto.</p>
        </div>
        <input type="submit" value="Update Data">
        <a href="index.php" class="btn-kembali" style="background-color: #6c757d; margin-left:10px;">Kembali</a>
    </form>
</div>

</body>
</html>