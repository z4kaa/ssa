<?php
$host = "localhost"; // Sesuaikan dengan host database Anda
$user = "root";      // Sesuaikan dengan username database Anda
$pass = "";          // Sesuaikan dengan password database Anda
$db   = "db_siswa";   // Sesuaikan dengan nama database Anda

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>