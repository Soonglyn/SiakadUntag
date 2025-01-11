<?php
include 'koneksi.php';

// Delete data based on ID
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $delete_sql = "DELETE FROM matakuliah WHERE id = '$id'";

    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Data matakuliah berhasil dihapus.'); window.location.href='matakuliah.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
