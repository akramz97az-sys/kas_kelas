<?php
$title ="Laporan Kas";
$subtitle ="Kas Kelas";
include "config/app.php"; 

// 1. Ambil Bulan dan Tahun dari URL (Sinkron dengan Header)
$bulan_angka = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$nama_bulan_list = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$bulan_pilihan_string = $nama_bulan_list[$bulan_angka];

// 2. Query Total Ringkasan untuk Card Atas
$query_masuk = "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='masuk' AND bulan='$bulan_pilihan_string' AND YEAR(tanggal)='$tahun_pilihan'";
$total_masuk = mysqli_fetch_assoc(mysqli_query($conn, $query_masuk))['total'] ?? 0;

$query_keluar = "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='keluar' AND MONTH(tanggal)='$bulan_angka' AND YEAR(tanggal)='$tahun_pilihan'";
$total_keluar = mysqli_fetch_assoc(mysqli_query($conn, $query_keluar))['total'] ?? 0;

$saldo_akhir = $total_masuk - $total_keluar;

// 3. Query Utama Laporan: Ambil semua murid aktif
$query_murid = mysqli_query($conn, "SELECT id_murid, nama FROM murid WHERE status='aktif' ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan | Kas Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] dark:bg-slate-950 transition-colors duration-300">
<?php include "layout/sidebar.php"?>

    <div class="max-w-7xl mx-auto">
    <?php include "layout/header.php"?>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 mb-8">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-xl flex justify-between items-center group">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pemasukan (<?= $bulan_pilihan_string ?>)</p>
                    <h3 class="text-2xl dark:text-white font-black text-emerald-500">Rp <?= number_format($total_masuk, 0, ',', '.') ?></h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 dark:bg-slate-800 text-emerald-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-arrow-trend-up"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-xl flex justify-between items-center group">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pengeluaran (<?= $bulan_pilihan_string ?>)</p>
                    <h3 class="text-2xl dark:text-white font-black text-red-500">Rp <?= number_format($total_keluar, 0, ',', '.') ?></h3>
                </div>
                <div class="w-12 h-12 bg-red-50 dark:bg-slate-800 text-red-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
            </div>

            <div class="bg-slate-900 dark:bg-slate-900 dark:border dark:border-slate-800 p-6 rounded-[2rem] shadow-xl flex justify-between items-center group">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Selisih Kas Bulan Ini</p>
                    <h3 class="text-2xl font-black text-white">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h3>
                </div>
                <div class="w-12 h-12 bg-blue-500 text-white rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="dark:bg-slate-900 bg-white rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-xl overflow-hidden ring-1 ring-black/5">
            <div class="p-6 border-b border-gray-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-black dark:text-white text-slate-800 uppercase tracking-tight italic">📊 Rekap Partisipasi Kas Siswa</h3>
                    <p class="text-xs text-slate-400 font-bold">Periode Laporan: <?= $bulan_pilihan_string ?> <?= $tahun_pilihan ?></p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 border-b border-gray-50 dark:border-slate-800 uppercase text-[10px] font-black tracking-[0.2em]">
                            <th class="px-6 py-5 text-center w-16">No</th>
                            <th class="px-6 py-5">Nama Murid</th>
                            <th class="px-4 py-5 text-center w-24">Minggu 1</th>
                            <th class="px-4 py-5 text-center w-24">Minggu 2</th>
                            <th class="px-4 py-5 text-center w-24">Minggu 3</th>
                            <th class="px-4 py-5 text-center w-24">Minggu 4</th>
                            <th class="px-6 py-5 text-right w-36">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/50 font-bold text-sm text-slate-700 dark:text-slate-300">
                        <?php
                        $no = 1;
                        while($m = mysqli_fetch_assoc($query_murid)):
                            $id_m = $m['id_murid'];
                            
                            // Ambil riwayat transaksi cicilan masuk untuk murid ini khusus bulan & tahun terpilih
                            $query_transaksi_murid = mysqli_query($conn, "SELECT minggu, jumlah FROM transaksi 
                                WHERE id_murid='$id_m' AND jenis='masuk' AND bulan='$bulan_pilihan_string' AND YEAR(tanggal)='$tahun_pilihan'");
                            
                            // Map nominal bayar ke dalam array minggu
                            $status_minggu = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                            $total_bayar_murid = 0;
                            while($t = mysqli_fetch_assoc($query_transaksi_murid)) {
                                $status_minggu[$t['minggu']] = $t['jumlah'];
                                $total_bayar_murid += $t['jumlah'];
                            }
                        ?>
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-5 text-center text-slate-400 font-mono"><?= str_pad($no++, 2, "0", STR_PAD_LEFT) ?></td>
                            <td class="px-6 py-5 font-black text-slate-900 dark:text-white uppercase text-xs"><?= htmlspecialchars($m['nama']) ?></td>
                            
                            <?php for($i=1; $i<=4; $i++): ?>
                                <td class="px-4 py-5 text-center">
                                    <?php if($status_minggu[$i] > 0): ?>
                                        <span class="inline-flex flex-col items-center text-emerald-500">
                                            <i class="fas fa-check-circle text-base"></i>
                                            <span class="text-[9px] font-mono font-bold text-slate-400">Rp<?= number_format($status_minggu[$i], 0, ',', '.') ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-red-400">
                                            <i class="fas fa-times-circle text-base opacity-40"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>

                            <td class="px-6 py-5 text-right font-mono text-xs">
                                <span class="<?= $total_bayar_murid >= 40000 ? 'text-emerald-500' : ($total_bayar_murid > 0 ? 'text-amber-500' : 'text-red-500') ?>">
                                    Rp <?= number_format($total_bayar_murid, 0, ',', '.') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script src="assets/js/sidebar.js"></script>
</body>
</html>