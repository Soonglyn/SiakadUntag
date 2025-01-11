<?php
include 'auth.php';
$conn = mysqli_connect('localhost', 'root', '', 'dbkuliah2');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT nama, spp FROM mahasiswa";
$result = mysqli_query($conn, $sql);

$names = [];
$spps = [];

while ($row = mysqli_fetch_assoc($result)) {
    $names[] = $row['nama'];
    $spps[] = $row['spp'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik SPP Mahasiswa</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            display: flex;
            height: 100vh;
            background-color: #f4f4f9;
            color: #333;
        }
        .menu {
            width: 274px;
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

        <a href="mahasiswa.php" onclick="loadPage('mahasiswa.php')">
            <img src="icons/student.png" alt="Mahasiswa"> Mahasiswa
        </a>
        <a href="dosen.php" onclick="loadPage('dosen.php')">
            <img src="icons/teacher.png" alt="Dosen"> Dosen
        </a>
        <a href="matakuliah.php" onclick="loadPage('matakuliah.php')">
            <img src="icons/subjects.png" alt="Mata Kuliah"> Mata Kuliah
        </a>
        <a href="laporan.php" id="dropdownToggle" onclick="toggleMenu(event)">
            <img src="icons/report.png" alt="Laporan" style="margin-right: 12px;"> Laporan
        </a>

        <div id="dropdownMenu" class="dropdown-menu shadow">
            <a class="dropdown-item" href="laporanMHS.php">Mahasiswa</a>
            <a class="dropdown-item" href="laporanDosen">Dosen</a>
            <a class="dropdown-item" href="laporanMk.php">Matakuliah</a>
        </div>

<!-- Tambahkan Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .dropdown-item {
    display: flex; /* Gunakan flexbox */
    align-items: center; /* Sejajarkan ikon dan teks secara vertikal */
    gap: 12px; /* Jarak antara ikon dan teks */
    font-size: 16px;
    color: white; /* Konsisten dengan warna menu utama */
    padding: 10px 15px;
    background-color: #2c3e50; /* Warna dasar */
    border-bottom: 1px solid #34495e; /* Garis pembatas */
    text-decoration: none; /* Hilangkan garis bawah */
    }

    .dropdown-item img {
        width: 20px; /* Ukuran ikon */
        height: 20px;
        margin-right: 12px; /* Jarak ikon ke teks */
    }

    .dropdown-item:hover {
        background-color: #34495e; /* Warna hover */
        color: white;
    }
</style>

<script>
    function toggleMenu(event) {
        event.preventDefault();
        const dropdown = document.getElementById("dropdownMenu");
        const dropdownToggle = document.getElementById("dropdownToggle");

        // Posisi dropdown menu tepat di bawah tombol
        const rect = dropdownToggle.getBoundingClientRect();
        dropdown.style.top = `${rect.bottom}px`;
        dropdown.style.left = `${rect.left}px`;
        dropdown.style.width = `${rect.width}px`;

        // Toggle kelas "show" pada menu
        dropdown.classList.toggle("show");

        // Tutup menu jika klik di luar
        document.addEventListener("click", function closeDropdown(e) {
            if (!dropdown.contains(e.target) && e.target.id !== "dropdownToggle") {
                dropdown.classList.remove("show");
                document.removeEventListener("click", closeDropdown); // Hapus listener
            }
        });
    }
</script>

        <a href="grafik.php" onclick="loadPage('grafik.php')">
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
    
    <div class="container-fluid mt-4">
        <h2 class="text-center mb-4">Grafik SPP Mahasiswa</h2>
        <canvas id="myChart" width="400" height="200"></canvas>

    </div>
    
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($names); ?>,
                datasets: [{
                    label: 'SPP Mahasiswa',
                    data: <?php echo json_encode($spps); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
