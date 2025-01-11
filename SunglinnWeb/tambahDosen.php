<?php
include 'koneksi.php'; // Pastikan file koneksi dimuat

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kodedsn = isset($_POST['kodedsn']) ? $_POST['kodedsn'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $prodi = isset($_POST['prodi']) ? $_POST['prodi'] : '';
    $jabatan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';

    
    // Masukkan data ke database menggunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO dosen (kodedsn, nama, alamat, prodi, jabatan) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $kodedsn, $nama, $alamat, $prodi, $jabatan);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='dosen.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan data.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-group {
            margin-bottom: 15px;
        }
        input, textarea, button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #27ae60;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #2ecc71;
        }
        a {
            text-decoration: none;
            color: #333;
            margin-top: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="menu">
    <div class="logo">
        <img src="icons/unicorn.png" alt="Logo Perusahaan">
        <h2>S I A K A D</h2>
    </div>

    <a href="#" onclick="loadPage('operasiMHS.php')">
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

<div class="content" id="content">
    <h1>Tambah Data Dosen</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="kodedsn">Kode Dosen:</label>
        <input type="text" id="kodedsn" name="kodedsn" required>

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="alamat">Alamat:</label>
        <textarea id="alamat" name="alamat" required></textarea>

        <label for="prodi">Prodi:</label>
        <input type="text" id="prodi" name="prodi" required>

        <label for="jabatan">Jabatan:</label>
        <input type="text" id="jabatan" name="jabatan" required>

        <a>
        <button type="submit" class="btn btn-success">Tambah</button>
        </a>
        <a href="dosen.php" class="btn btn-secondary mb-3">Batal</a>
    </form>
</div>
</body>
</html>
