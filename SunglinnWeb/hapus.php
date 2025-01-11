<?php
include 'koneksi.php';

// Check if the 'hapus' parameter is passed in the URL
if (isset($_GET['hapus'])) {
    $nbi = $_GET['hapus'];

    // Delete the student record from the database
    $sql = "SELECT foto_ijazah FROM mahasiswa WHERE nbi = '$nbi'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Remove the uploaded photo if it exists
        if (file_exists('uploads/' . $row['foto_ijazah'])) {
            unlink('uploads/' . $row['foto_ijazah']);
        }

        // Delete the student record from the database
        $delete_sql = "DELETE FROM mahasiswa WHERE nbi = '$nbi'";
        
        if (mysqli_query($conn, $delete_sql)) {
            echo "<script>
                    alert('Data mahasiswa berhasil dihapus.');
                    window.location.href = 'mahasiswa.php'; // Redirect to the main page after deletion
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Error: " . mysqli_error($conn) . "');
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data mahasiswa tidak ditemukan.');
              </script>";
    }
} else {
    echo "<script>
            alert('Permintaan tidak valid.');
          </script>";
}

mysqli_close($conn);
?>
