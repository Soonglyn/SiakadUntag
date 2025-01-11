<?php
include 'koneksi.php'; // Pastikan file koneksi dimuat

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kodemk = isset($_POST['kodemk']) ? $_POST['kodemk'] : '';
    $nama_matkul = isset($_POST['nama_matkul']) ? $_POST['nama_matkul'] : '';
    $sks = isset($_POST['sks']) ? $_POST['sks'] : '';
    
    // Masukkan data ke database menggunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO matakuliah (kodemk, nama_matkul, sks) 
                            VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $kodemk, $nama_matkul, $sks);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='matakuliah.php';</script>";
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
    <h1>Tambah Data Mata Kuliah</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="kodemk">Kode Mata Kuliah:</label>
        <input type="text" id="kodemk" name="kodemk" required>

        <label for="nama_matkul">Nama Mata Kuliah:</label>
        <input type="text" id="nama_matkul" name="nama_matkul" required>

        <label for="sks">Sks:</label>
        <textarea id="sks" name="sks" required></textarea>

        <a>
        <button type="submit" class="btn btn-success">Tambah</button>
        </a>
        <a href="matakuliah.php" class="btn btn-secondary mb-3">Batal</a>
    </form>
</div>
</body>
</html>
