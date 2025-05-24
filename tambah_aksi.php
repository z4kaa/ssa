<?php
session_start();
include 'koneksi.php';

// Ambil data dari form
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
$no_absen = mysqli_real_escape_string($koneksi, $_POST['no_absen']);

// Proses upload foto
$nama_file_foto = "";
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "uploads/";
    // Buat nama file unik untuk menghindari konflik
    $nama_file_foto = time() . "_" . basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $nama_file_foto;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file adalah gambar asli
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['pesan'] = "File yang diupload bukan gambar.";
        $_SESSION['tipe_pesan'] = "danger";
        header("location:index.php");
        exit();
    }

    // Batasi jenis file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $_SESSION['pesan'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $_SESSION['tipe_pesan'] = "danger";
        header("location:index.php");
        exit();
    }

    // Coba upload file
    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $_SESSION['pesan'] = "Maaf, terjadi kesalahan saat mengupload file foto Anda.";
        $_SESSION['tipe_pesan'] = "danger";
        header("location:index.php");
        exit();
    }
} else {
    $_SESSION['pesan'] = "Error saat mengupload file: " . $_FILES['foto']['error'];
    $_SESSION['tipe_pesan'] = "danger";
    header("location:index.php");
    exit();
}

// Query untuk insert data
$query = "INSERT INTO tb_siswa (nama, kelas, no_absen, foto) VALUES ('$nama', '$kelas', '$no_absen', '$nama_file_foto')";
$hasil = mysqli_query($koneksi, $query);

if ($hasil) {
    $_SESSION['pesan'] = "Data siswa berhasil ditambahkan.";
    $_SESSION['tipe_pesan'] = "success";
} else {
    $_SESSION['pesan'] = "Gagal menambahkan data siswa: " . mysqli_error($koneksi);
    $_SESSION['tipe_pesan'] = "danger";
    // Jika gagal, hapus foto yang sudah terupload (opsional)
    if (!empty($nama_file_foto) && file_exists($target_dir . $nama_file_foto)) {
        unlink($target_dir . $nama_file_foto);
    }
}

header("location:index.php");
exit();
?>