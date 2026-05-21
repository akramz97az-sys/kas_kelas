<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Ambil & Bersihkan Input
    $id_murid   = mysqli_real_escape_string($conn, $_POST['id_murid']);
    $tanggal    = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $bulan      = mysqli_real_escape_string($conn, $_POST['bulan']); // Tambahkan jika ada kolom bulan di db
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $minggu_arr = $_POST['minggu'] ?? [];
    $nominal_arr = $_POST['nominal'] ?? [];

    // 2. VALIDASI (Sesuai Poin 1.7)
    
    // - Nama murid tidak boleh kosong
    if (empty($id_murid)) {
        echo "<script>alert('Pilih murid terlebih dahulu!'); window.history.back();</script>"; exit;
    }
    
    // - Tanggal wajib diisi
    if (empty($tanggal)) {
        echo "<script>alert('Tanggal wajib diisi!'); window.history.back();</script>"; exit;
    }

    // - Hitung total & Validasi Nominal harus > 0
    $total_bayar = 0;
    foreach ($minggu_arr as $minggu) {
        $nominal = (int)$nominal_arr[$minggu];
        if ($nominal <= 0) {
            echo "<script>alert('Nominal Minggu ke-$minggu tidak valid!'); window.history.back();</script>"; exit;
        }
        $total_bayar += $nominal;
    }

    if ($total_bayar <= 0) {
        echo "<script>alert('Pilih setidaknya satu minggu dengan nominal yang benar!'); window.history.back();</script>"; exit;
    }

    // 3. Simpan Data (Loop per minggu yang dicentang)
    $success = true;
    foreach ($minggu_arr as $minggu) {
        $nominal = (int)$nominal_arr[$minggu];
        
        $query = "INSERT INTO transaksi (id_murid, tanggal, bulan, minggu, jumlah, jenis, keterangan) 
                  VALUES ('$id_murid', '$tanggal', '$bulan', '$minggu', '$nominal', 'masuk', '$keterangan')";
        
        if (!mysqli_query($conn, $query)) {
            $success = false;
        }
    }

    // 4. Feedback ke User
    if ($success) {
        echo "<script>
                alert('Berhasil! Setoran kas masuk telah disimpan.');
                window.location.href = '../transaksi.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menyimpan beberapa data transaksi!'); window.history.back();</script>";
    }

} else {
    header("Location: ../transaksi.php");
    exit;
}
?>