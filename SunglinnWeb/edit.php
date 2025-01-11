<?php
include 'koneksi.php';

// Check if the 'edit' parameter is set in the URL
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    // Fetch the data for the selected student from the database
    $sql = "SELECT * FROM mahasiswa WHERE nbi = '$nbi'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Data tidak ditemukan'); window.location.href='mahasiswa.php';</script>";
    }
}

// Update data after form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nbi = $_POST['nbi'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $ipk = $_POST['ipk'];
    $spp = $_POST['spp'];
    $prodi = $_POST['prodi'];
    $foto_ijazah = $_FILES['foto_ijazah']['name'];

    // Handle file upload for foto_ijazah
    if ($foto_ijazah) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto_ijazah);
        move_uploaded_file($_FILES['foto_ijazah']['tmp_name'], $target_file);
    } else {
        $foto_ijazah = $row['foto_ijazah'];  // Keep the existing photo if no new one is uploaded
    }

    // Update student data in the database
    $update_sql = "UPDATE mahasiswa 
                   SET nama='$nama', alamat='$alamat', ipk='$ipk', spp='$spp', prodi='$prodi', foto_ijazah='$foto_ijazah'
                   WHERE nbi='$nbi'";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Data mahasiswa berhasil diperbarui.'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
           
            display: flex;
            height: 100vh;
            background-color: #f4f4f9;
            color: #333;
        }
        .menu {
            width: 230px;
            background-color: #2c3e50;
            color: white;
            padding: 20px 10px;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .menu a {
            text-decoration: none;
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            text-align: left;
            transition: background-color 0.3s;
        }
        .menu a img {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }
        .menu a:hover {
            background-color: #34495e;
        }
        .menu .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .menu .logo img {
            width: 80px;
            height: auto;
            border-radius: 10px;
        }
        .menu .logo h2 {
            margin: 10px 0;
            font-size: 18px;
            color: #ecf0f1;
        }
        .content {
            width: 80%;
            padding: 30px;
            overflow-y: auto;
        }
        .content h1 {
            margin-top: 0;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #ecf0f1;
        }
        tr:hover {
            background-color: #dcdcdc;
        }
        .ijazah-img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .edit-btn, .delete-btn {
            color: #3498db;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 14px;
        }
        .edit-btn:hover, .delete-btn:hover {
            background-color: #3498db;
            color: white;
        }
        .button-down {
            margin-top: 30px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-down:hover {
            background-color: #2ecc71;
        }
    </style>
</head>
<body>

<div class="menu">
    <!-- Logo Perusahaan -->
    <div class="logo">
        <img src="icons/unicorn.png" alt="Logo Perusahaan">
        <h2>S I A K A D</h2>
    </div>

    <a href="#" onclick="loadPage('mahasiswa.php')">
            <img src="icons/student.png" alt="Mahasiswa"> Mahasiswa
        </a>
        <a href="#" onclick="loadPage('dosen.php')">
            <img src="icons/teacher.png" alt="Dosen"> Dosen
        </a>
        <a href="#" onclick="loadPage('matakuliah.php')">
            <img src="icons/subjects.png" alt="Mata Kuliah"> Matakuliah
        </a>
        <a href="#" onclick="loadPage('laporan.php')">
            <img src="icons/report.png" alt="Laporan"> Laporan
        </a>
        <a href="#" onclick="loadPage('grafik.php')">
            <img src="icons/chart.png" alt="Grafik"> Grafik
        </a>
        <a href="logout.php" onclick="return confirm('Apakah Anda ingin logout?')">
            <img src="icons/shutdown.png" alt="Logout"> Logout
        </a>
        <script>
            function confirmLogout() {
                alert("Anda akan keluar dari sistem.");
                return confirm('Apakah Anda ingin logout?');
            }
        </script>
</div>

<div class="content">
    <h1 class="text-center">Edit Mahasiswa</h1>
    <form action="edit.php?edit=<?php echo urlencode($row['nbi']); ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nbi" class="form-label">NBI</label>
            <input type="text" id="nbi" name="nbi" class="form-control" value="<?php echo htmlspecialchars($row['nbi']); ?>" readonly required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" name="alamat" class="form-control" required><?php echo htmlspecialchars($row['alamat']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="ipk" class="form-label">IPK</label>
            <input type="text" id="ipk" name="ipk" class="form-control" value="<?php echo htmlspecialchars($row['ipk']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="spp" class="form-label">SPP</label>
            <input type="text" id="spp" name="spp" class="form-control" value="<?php echo htmlspecialchars($row['spp']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="prodi" class="form-label">Prodi</label>
            <input type="text" id="prodi" name="prodi" class="form-control" value="<?php echo htmlspecialchars($row['prodi']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="foto_ijazah" class="form-label">Foto Ijazah</label>
            <input type="file" id="foto_ijazah" name="foto_ijazah" class="form-control mb-3">
            
            <small>Current Photo: <img src="uploads/<?php echo htmlspecialchars($row['foto_ijazah']); ?>" class="ijazah-img" alt="Foto Ijazah"></small>
        </div>
            
        <button type="submit" class="btn btn-primary">Simpan</button>
         <a href="mahasiswa.php" class="btn btn-secondary">Batal</a>
    </form>

   
</div>

<!-- Link to Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
