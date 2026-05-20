<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // 1. Perbaikan Keamanan: Menggunakan Prepared Statement untuk mencegah SQL Injection
    $stmt = mysqli_prepare($conn, "SELECT username, password, role FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $u);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($d = mysqli_fetch_assoc($result)) {
        // 2. Perbaikan Keamanan: Cek password (asumsi menggunakan password_verify nantinya)
        // Jika saat ini database-mu masih teks biasa, ganti line ini dengan: if($p == $d['password'])
        if ($p == $d['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $d['username'];
            $_SESSION['role'] = $d['role'];
            header("Location: ../home.php");
            exit;
        } else {
            $error = "password"; // Alert spesifik password salah
        }
    } else {
        $error = "username"; // Alert spesifik username tidak ada
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kas Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-card { width: 350px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="card login-card p-4 border-0 rounded-3">
        <h4 class="text-center mb-4">Login Kas Kelas</h4>

        <?php if (isset($error)): ?>
            <?php if ($error == "username"): ?>
                <div class="alert alert-warning py-2">Username tidak ditemukan!</div>
            <?php elseif ($error == "password"): ?>
                <div class="alert alert-danger py-2">Password Anda salah!</div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label small text-muted">Username</label>
                <input name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label small text-muted">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button name="login" class="btn btn-primary w-100 py-2 mt-2">Masuk Sekarang</button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">Lupa password? Hubungi Admin</small>
        </div>
    </div>

</body>
</html>