<?php
session_start();
include 'koneksi.php';

// Ambil data dari form
$id = mysqli_real_escape_string($koneksi, $_POST['id']);
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
$no_absen = mysqli_real_escape_string($koneksi, $_POST['no_absen']);
$foto_lama = mysqli_real_escape_string($koneksi, $_POST['foto_lama']);
$nama_file_foto_baru = "";

// Cek apakah ada file foto baru yang diupload
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && !empty($_FILES['foto']['name'])) {
    $target_dir = "uploads/";
    $nama_file_foto_baru = time() . "_" . basename($_FILES["foto"]["name"]);
    $target_file_baru = $target_dir . $nama_file_foto_baru;
    $imageFileType = strtolower(pathinfo($target_file_baru, PATHINFO_EXTENSION));

    // Cek apakah file adalah gambar asli
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['pesan'] = "File baru yang diupload bukan gambar.";
        $_SESSION['tipe_pesan'] = "danger";
        header("location:edit.php?id=$id");
        exit();
    }

    // Batasi jenis file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $_SESSION['pesan'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan untuk foto baru.";
        $_SESSION['tipe_pesan'] = "danger";
        header("location:edit.php?id=$id");
        exit();
    }

    // Coba upload file baru
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file_baru)) {
        // Hapus foto lama jika ada dan upload foto baru berhasil
        if (!empty($foto_lama) && file_exists($target_dir . $foto_lama)) {
            unlink($target_dir . $foto_lama);
        }
    } else {
        $_SESSION['pesan'] = "Maaf, terjadi kesalahan saat mengupload file foto baru Anda.";
        $_SESSION['tipe_pesan'] = "danger";
        header("location:edit.php?id=$id");
        exit();
    }
} else {
    // Jika tidak ada foto baru diupload, gunakan foto lama
    $nama_file_foto_baru = $foto_lama;
}

// Query untuk update data
$query = "UPDATE tb_siswa SET 
            nama='$nama', 
            kelas='$kelas', 
            no_absen='$no_absen', 
            foto='$nama_file_foto_baru' 
          WHERE id='$id'";

$hasil = mysqli_query($koneksi, $query);

if ($hasil) {
    $_SESSION['pesan'] = "Data siswa berhasil diupdate.";
    $_SESSION['tipe_pesan'] = "success";
} else {
    $_SESSION['pesan'] = "Gagal mengupdate data siswa: " . mysqli_error($koneksi);
    $_SESSION['tipe_pesan'] = "danger";
    // Jika update gagal dan foto baru diupload, hapus foto baru tersebut (opsional)
    if ($nama_file_foto_baru != $foto_lama && file_exists($target_dir . $nama_file_foto_baru)) {
        unlink($target_dir . $nama_file_foto_baru);
    }
}

header("location:index.php");
exit();
?>