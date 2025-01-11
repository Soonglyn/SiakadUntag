<?php
include 'koneksi.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = 'Password dan konfirmasi password tidak cocok!';
    } else {
        $hashed_password = md5($password); // Hashing sederhana (gunakan bcrypt/argon2 untuk produksi)

        // Cek apakah username sudah ada
        $sql_check = "SELECT * FROM users WHERE username = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param('s', $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $message = 'Username sudah digunakan!';
        } else {
            // Tambahkan pengguna baru
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php?success=1");
                exit;
            } else {
                $message = 'Terjadi kesalahan saat mendaftar!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Register</h3>
                        <?php if ($message): ?>
                            <div class="alert alert-danger"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <form method="POST" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!username || !password || !confirmPassword) {
                alert('Semua bidang harus diisi!');
                return false;
            }
            if (password !== confirmPassword) {
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
