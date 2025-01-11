<?php
// koneksi.php
$host = "localhost"; // Ganti dengan host Anda
$user = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "dbkuliah2"; // Nama database

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
