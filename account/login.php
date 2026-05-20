<?php

session_start();
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";


if(isset($_POST['login'])){
    $u = $_POST['username'];
    $p = $_POST['password'];
    $q = mysqli_query($conn,"SELECT * FROM user WHERE username='$u' AND password='$p'");
    if(mysqli_num_rows($q)==1){
        $d = mysqli_fetch_assoc($q);
        $_SESSION['login']=true;
        $_SESSION['username']=$d['username'];
        $_SESSION['role']=$d['role'];
        header("Location:../home.php");
    }else{
        $error=true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <form method="post" class="p-4 border rounded">
        <h4>Login Kas Kelas</h4>
        <?php if(isset($error)): ?>
        <div class="alert alert-denger">Login Gagal</div>
        <?php endif;?>
        <input name="username" class="form-control mb-2" placeholder="Username" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button name="login" class="btn btn-primary w-100">Login</button>
    </form>
</body>
</html>