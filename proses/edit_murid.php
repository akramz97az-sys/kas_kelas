<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id    = $_POST['id_murid'];
    $nisn  = mysqli_real_escape_string($conn, $_POST['nisn']);
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE murid SET nisn='$nisn', nama='$nama', kelas='$kelas', status='$status' WHERE id_murid='$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: ../data_murid.php?pesan=edit_sukses");
    } else {
        header("Location: ../data_murid.php?pesan=edit_gagal");
    }
}
?>