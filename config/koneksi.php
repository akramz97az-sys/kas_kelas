<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kas_kelas_web";
$port = 3307;
$base_url = "http://localhost/project_kas_kelas_web/";

// 3. Buat Koneksi (Wajib di atas sebelum Query!)
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>