<?php
include 'koneksi.php';
include 'auth.php';

// Function for SELECT
function tampilData($conn) {
    // Tentukan jumlah data per halaman
    $limit = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Tangkap keyword pencarian
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Hitung total data
    $sql_count = "SELECT COUNT(*) as total FROM mahasiswa";
    $result_count = mysqli_query($conn, $sql_count);
    $total_data = mysqli_fetch_assoc($result_count)['total'];
    $total_pages = ceil($total_data / $limit);

     // Query untuk data dengan limit, offset, dan filter pencarian
     $sql = "SELECT * FROM mahasiswa WHERE nama LIKE '%$search%' OR nbi LIKE '%$search%' LIMIT $limit OFFSET $offset";
     $result = mysqli_query($conn, $sql);

    // Query untuk data dengan limit dan offset
    $sql = "SELECT * FROM mahasiswa LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-bordered table-hover text-center'>";
        echo "<thead style='background-color: #2c3e50; color: white; text-align: center;'>
                <tr>
                    <th>No</th>
                    <th>Nbi</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat</th>
                    <th>IPK</th>
                    <th>SPP</th>
                    <th>Prodi</th>
                    <th>Foto Ijazah</th>
                    <th>Aksi</th>
                </tr>
              </thead>";
        echo "<tbody>";

        $no = $offset + 1; // Penomoran berdasarkan halaman
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $no++ . "</td>
                    <td>" . htmlspecialchars($row['nbi']) . "</td>
                    <td>" . htmlspecialchars($row['nama']) . "</td>
                    <td>" . htmlspecialchars($row['alamat']) . "</td>
                    <td>" . htmlspecialchars($row['ipk']) . "</td>
                    <td>" . htmlspecialchars($row['spp']) . "</td>
                    <td>" . htmlspecialchars($row['prodi']) . "</td>
                    <td>";
            
            if (file_exists('uploads/' . $row['foto_ijazah'])) {
                echo "<img src='uploads/{$row['foto_ijazah']}' class='ijazah-img' alt='Foto Ijazah'>";
            } else {
                echo "Gambar tidak ditemukan.";
            }

            echo "</td>
                    <td>
                        <a href='edit.php?edit=" . urlencode($row['nbi']) . "' class='edit-btn'>Edit</a> |
                        <a href='hapus.php?hapus=" . urlencode($row['nbi']) . "' class='delete-btn'>Hapus</a>
                    </td>
                  </tr>";
        }
        echo "</tbody>";
        echo "</table>";

        // Tampilkan pagination
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=$i' class='" . ($i == $page ? "active" : "") . "'>$i</a>";
        }
        echo "</div>";
    } else {
        echo "<tr>";
        echo "<td colspan='8' class='text-center'>Tidak ada data</td>";
        echo "</tr>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mahasiswa</title>
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
            width: 20%;
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
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd; /* Garis luar tabel */
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd; /* Garis antar kolom dan baris */
            vertical-align: middle; /* Vertikal tengah */
            word-wrap: break-word; /* Memungkinkan kata panjang untuk terputus dan membungkus */
            max-width: 150px; /* Sesuaikan nilai ini sesuai kebutuhan */
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        tr:nth-child(even) td {
            background-color: #f3f3f3; /* Warna berbeda untuk baris genap */
        }
        tr:hover td {
            background-color: #e8f4f8; /* Efek hover pada baris */
        }
        img.ijazah-img {
            width: 80px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .edit-btn, .delete-btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .edit-btn {
            color: #2c3e50;
            background-color: #ecf0f1;
            border: 1px solid #2c3e50;
        }
        .delete-btn {
            color: #e74c3c;
            background-color: #ecf0f1;
            border: 1px solid #e74c3c;
        }
        .edit-btn:hover {
            background-color: #34495e;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c0392b;
            color: white;
        }
            .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            color: #2c3e50;
            border: 1px solid #2c3e50;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        .pagination a.active {
            background-color: #2c3e50;
            color: white;
            border-color: #2c3e50;
        }
        .pagination a:hover {
            background-color: #34495e;
            color: white;
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
            <img src="icons/subjects.png" alt="Mata Kuliah"> Matakuliah
        </a>
        <a href="laporan.php" id="dropdownToggle" onclick="toggleMenu(event)">
            <img src="icons/report.png" alt="Laporan" style="margin-right: 12px; "> Laporan
        </a>

        <div id="dropdownMenu" class="dropdown-menu shadow">
            <a class="dropdown-item" href="laporanMHS.php">Mahasiswa</a>
            <a class="dropdown-item" href="laporanDosen.php">Dosen</a>
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
    <h1 class="text-center mb-4">Data Mahasiswa</h1>
   
    <div class="d-flex justify-content-between align-items-center">
        <!-- Tombol Tambah -->
        <a href="tambah.php" class="btn btn-success btn-sm">
            <i class=""></i> Tambah
        </a>

        <!-- Form Pencarian -->
        <form method="GET" action="" class="d-flex">
            <div class="input-group" style="width: 200px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="" 
                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="btn btn-sm btn-primary">Cari</button>
            </div>
        </form>
    </div>

    <table class="table table-bordered table-hover mt-2">
        <thead class="thead-dark text-center">
            <!-- Header tabel -->
        </thead>
        <tbody>
            <?php tampilData($conn); ?>
        </tbody>
    </table>
</div>



</body>
</html>

<?php
mysqli_close($conn);
?>
