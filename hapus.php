<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['pesan'] = "ID siswa tidak ditemukan untuk dihapus.";
    $_SESSION['tipe_pesan'] = "danger";
    header("location:index.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil nama file foto sebelum menghapus data dari database
$query_select_foto = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id='$id'");
$data_foto = mysqli_fetch_assoc($query_select_foto);
$nama_file_foto = "";
if ($data_foto) {
    $nama_file_foto = $data_foto['foto'];
}

// Query untuk hapus data
$query_hapus = mysqli_query($koneksi, "DELETE FROM tb_siswa WHERE id='$id'");

if ($query_hapus) {
    // Hapus file foto dari folder uploads jika ada
    if (!empty($nama_file_foto) && file_exists("uploads/" . $nama_file_foto)) {
        unlink("uploads/" . $nama_file_foto);
    }
    $_SESSION['pesan'] = "Data siswa berhasil dihapus.";
    $_SESSION['tipe_pesan'] = "success";
} else {
    $_SESSION['pesan'] = "Gagal menghapus data siswa: " . mysqli_error($koneksi);
    $_SESSION['tipe_pesan'] = "danger";
}

header("location:index.php");
exit();
?>