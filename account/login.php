<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if(isset($_POST['login'])){
    // Gunakan real_escape_string untuk mencegah SQL Injection
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = $_POST['password']; // Password akan dicek pakai password_verify

    $q = mysqli_query($conn, "SELECT * FROM user WHERE username='$u'");
    
    if(mysqli_num_rows($q) == 1){
        $d = mysqli_fetch_assoc($q);
        
        // Cek password (asumsi di DB sudah pakai password_hash)
        // Jika belum pakai hash, ganti jadi: if($p == $d['password'])
        if(password_verify($p, $d['password'])){
            $_SESSION['login'] = true;
            $_SESSION['username'] = $d['username'];
            $_SESSION['role'] = $d['role'];
            header("Location:../home.php");
            exit;
        }
    }
    // Jika user tidak ketemu atau password salah
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Kas Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <form method="post" class="p-4 border rounded bg-white shadow-sm" style="width: 350px;">
        <h4 class="mb-3">Login Kas Kelas</h4>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger">Username atau Password salah!</div>
        <?php endif; ?>
        <input name="username" class="form-control mb-2" placeholder="Username" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button name="login" class="btn btn-primary w-100">Login</button>
    </form>
</body>
</html>