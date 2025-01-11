<?php
include 'koneksi.php';

// Memastikan bahwa parameter "id" ada dalam URL
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];  // Menangkap ID yang dikirim melalui URL

    // Melakukan query untuk mengambil data dosen berdasarkan ID
    $sql = "SELECT * FROM matakuliah WHERE id = '$id'"; 

    // Menjalankan query dan memeriksa apakah ada data yang ditemukan
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);  // Mengambil data dosen
    } else {
        // Jika tidak ada data ditemukan, mengarahkan kembali ke halaman dosen.php
        echo "<script>alert('Data dosen tidak ditemukan.'); window.location.href='matakuliah.php';</script>";
    }
}


// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kodemk = $_POST['kodemk'];
    $nama_matkul = $_POST['nama_matkul'];
    $sks = $_POST['sks'];

    // Query update data
    $conn->query("UPDATE dosen SET 
        kodemk = '$kodemk',
        nama_matkul = '$nama_matkul', 
        sks = '$sks', 
        WHERE id = $id");

    echo "<script>alert('Data berhasil diperbarui!'); window.location='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mata Kuliah</title>
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
        <h1 class="text-center">Edit Mata kuliah</h1>
        <form action="editMk.php?edit=<?php echo urlencode($row['id']); ?>" method="post">
            <div class="mb-3">
                <label for="kodemk" class="form-label">Kode Matakuliah</label>
                <input type="text" id="kodemk" name="kodemk" class="form-control" value="<?php echo htmlspecialchars($row['kodemk']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="nama_matkul" class="form-label">Nama Matakuliah</label>
                <input type="text" id="nama_matkul" name="nama_matkul" class="form-control" value="<?php echo htmlspecialchars($row['nama_matkul']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="sks" class="form-label">Sks</label>
                <textarea id="sks" name="sks" class="form-control" required><?php echo htmlspecialchars($row['sks']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>

            <a href="matakuliah.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>