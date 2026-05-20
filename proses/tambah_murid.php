<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn  = mysqli_real_escape_string($conn, $_POST['nisn']);
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $status = "aktif"; // Default status saat baru ditambah

    $cek_nisn = mysqli_query($conn, "SELECT nisn FROM murid WHERE nisn = '$nisn'");
    if (mysqli_num_rows($cek_nisn) > 0) {
        header("Location: ../data_murid.php?pesan=nisn_ada");
        exit(); 
    }

    $query = "INSERT INTO murid (nisn, nama, kelas, status) VALUES ('$nisn','$nama', '$kelas', '$status')";

    if (mysqli_query($conn, $query)) {
        header("Location: ../data_murid.php?status=sukses");
    } else {
        header("Location: ../data_murid.php?status=gagal");
    }
}
?>