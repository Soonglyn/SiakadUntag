<?php
// Setelan koneksi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "dbkuliah";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data mahasiswa
$sql = "SELECT nbi, nama, alamat FROM mahasiswa";
$result = $conn->query($sql);

// Menyiapkan array untuk data mahasiswa
$students = array();

if ($result->num_rows > 0) {
    // Mengambil data setiap baris
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Menutup koneksi
$conn->close();

// Menampilkan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($students);
?>

