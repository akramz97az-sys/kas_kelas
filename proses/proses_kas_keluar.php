<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $tanggal    = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keperluan  = mysqli_real_escape_string($conn, $_POST['keperluan']);
    $jumlah     = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $ket_extra  = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    // Gabungkan keperluan dan keterangan tambahan untuk disimpan di kolom 'keterangan'
    $keterangan_lengkap = $keperluan . " (" . $ket_extra . ")";
    
    $jenis = 'keluar';

    // Validasi jumlah
    if ($jumlah <= 0) {
        echo "<script>alert('Nominal pengeluaran tidak valid!'); window.history.back();</script>";
        exit;
    }

    // QUERY: id_murid diisi NULL karena ini pengeluaran kelas/umum
    $query = "INSERT INTO transaksi (id_murid, tanggal, jumlah, jenis, keterangan) 
              VALUES (NULL, '$tanggal', '$jumlah', '$jenis', '$keterangan_lengkap')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Pengeluaran dana berhasil dicatat!');
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