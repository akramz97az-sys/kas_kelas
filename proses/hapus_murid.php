<?php
// Hubungkan ke database
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

// Pastikan ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query Hapus
    $query = "DELETE FROM murid WHERE id_murid = '$id'";

    if (mysqli_query($conn, $query)) {
        // Berhasil, balik ke halaman data murid
        header("Location: ../data_murid.php?pesan=hapus_sukses");
    } else {
        // Gagal (biasanya karena ID masih dipakai di tabel transaksi)
        header("Location: ../data_murid.php?pesan=hapus_gagal");
    }
} else {
    // Jika tidak ada ID, tendang balik
    header("Location: ../data_murid.php");
}
?>