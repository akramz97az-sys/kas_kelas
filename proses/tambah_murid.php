<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn   = mysqli_real_escape_string($conn, $_POST['nisn']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas  = mysqli_real_escape_string($conn, $_POST['kelas']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validasi Kosong (Pencegahan jika user mematikan fitur 'required' di browser)
    if (empty($nisn) || empty($nama) || empty($kelas)) {
        echo "<script>alert('Semua kolom wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Validasi NISN harus angka
    if (!is_numeric($nisn)) {
        echo "<script>alert('NISN harus berupa angka!'); window.history.back();</script>";
        exit;
    }

    // Query simpan
    $query = "INSERT INTO murid (nisn, nama, kelas, status) VALUES ('$nisn', '$nama', '$kelas', '$status')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: ../data_murid.php?pesan=tambah_sukses");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>