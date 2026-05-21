<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

// 1. Ambil data dari form
$tanggal    = $_POST['tanggal'];
$keperluan  = $_POST['keperluan'];
$jumlah     = (int)$_POST['jumlah'];
$keterangan = $_POST['keterangan'];

// 2. Validasi Dasar (Sesuai poin 1.7)
if (empty($tanggal) || empty($keperluan) || $jumlah <= 0) {
    echo "<script>alert('Data tidak lengkap atau nominal harus lebih dari 0!'); window.history.back();</script>";
    exit();
}

// 3. Validasi Saldo (Poin 1.7: Kas keluar tidak boleh melebihi saldo)
$q_saldo = mysqli_query($conn, "SELECT 
    SUM(CASE WHEN jenis='masuk' THEN jumlah ELSE 0 END) as total_masuk,
    SUM(CASE WHEN jenis='keluar' THEN jumlah ELSE 0 END) as total_keluar 
    FROM transaksi");
$d = mysqli_fetch_assoc($q_saldo);
$saldo_sekarang = ($d['total_masuk'] - $d['total_keluar']);

if ($jumlah > $saldo_sekarang) {
    echo "<script>
        alert('Gagal! Saldo Kas tidak cukup. Sisa saldo saat ini: Rp " . number_format($saldo_sekarang, 0, ',', '.') . "');
        window.history.back();
    </script>";
    exit();
}

// 4. Jika lolos validasi, Simpan ke Database
$sql = "INSERT INTO transaksi (jenis, tanggal, keperluan, jumlah, keterangan) VALUES ('keluar', '$tanggal', '$keperluan', '$jumlah', '$keterangan')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Berhasil mencatat pengeluaran!'); window.location.href='../transaksi.php';</script>";
} else {
    echo "<script>alert('Gagal menyimpan data!'); window.history.back();</script>";
}
?>