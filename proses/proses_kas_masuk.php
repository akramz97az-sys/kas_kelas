<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dan bersihkan
    $id_murid  = mysqli_real_escape_string($conn, $_POST['id_murid']);
    $tanggal   = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jumlah    = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $jenis = 'masuk';

    // Validasi jumlah
    if ($jumlah <= 0) {
        echo "<script>alert('Jumlah setoran tidak valid!'); window.history.back();</script>";
        exit;
    }

    // QUERY DIPERBAIKI: Hapus id_user karena tidak ada di tabel transaksi kamu
    $query = "INSERT INTO transaksi (id_murid, tanggal, jumlah, jenis, keterangan) 
              VALUES ('$id_murid', '$tanggal', '$jumlah', '$jenis', '$keterangan')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Setoran kas berhasil dicatat!');
                window.location.href = '../transaksi.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    header("Location: ../transaksi.php");
    exit;
}
?>