<?php
$title ="Arus Kas";
$subtitle ="Kas Kelas";
include "config/app.php";

// 1. Tangkap Bulan dan Tahun dari URL (Biar sinkron dengan pilihan di Header)
$bulan_angka = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Mapping angka ke nama bulan string untuk query kas masuk
$nama_bulan_list = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$bulan_pilihan_string = $nama_bulan_list[$bulan_angka];

// 2. Ambil Saldo Berdasarkan Bulan & Tahun Pilihan (Sinkronisasi Ringkasan)
$query_masuk = "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='masuk' AND bulan='$bulan_pilihan_string' AND YEAR(tanggal)='$tahun_pilihan'";
$total_masuk = mysqli_fetch_assoc(mysqli_query($conn, $query_masuk))['total'] ?? 0;

$query_keluar = "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='keluar' AND MONTH(tanggal)='$bulan_angka' AND YEAR(tanggal)='$tahun_pilihan'";
$total_keluar = mysqli_fetch_assoc(mysqli_query($conn, $query_keluar))['total'] ?? 0;

$saldo_akhir = $total_masuk - $total_keluar;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arus Kas | Kas Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] dark:bg-slate-950 transition-colors duration-300">
    <?php include "layout/sidebar.php"; ?>
    
    <div class="max-w-7xl mx-auto">
        <?php include "layout/header.php"; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 mb-8">
            <div class="bg-white p-7 dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 flex justify-between items-center group hover:scale-[1.03] transition-all duration-300 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kas Masuk (<?= $bulan_pilihan_string ?>)</p>
                    <h3 class="text-2xl dark:text-white font-black text-slate-900">Rp <?= number_format($total_masuk, 0, ',', '.') ?></h3>
                </div>
                <div class="w-14 h-14 bg-emerald-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-arrow-trend-up text-xl"></i>
                </div>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50/50 rounded-full blur-3xl group-hover:bg-emerald-200/50 transition-colors"></div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-7 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 flex justify-between items-center group hover:scale-[1.03] transition-all duration-300 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kas Keluar (<?= $bulan_pilihan_string ?>)</p>
                    <h3 class="text-2xl dark:text-white font-black text-slate-900 tracking-tight">Rp <?= number_format($total_keluar, 0, ',', '.') ?></h3>
                </div>
                <div class="w-14 h-14 bg-red-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-arrow-trend-down text-xl"></i>
                </div>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-red-50/50 rounded-full blur-3xl group-hover:bg-red-200/50 transition-colors"></div>
            </div>

            <div class="bg-slate-900 dark:bg-slate-900 dark:border dark:border-slate-800 p-7 rounded-[2.5rem] shadow-2xl shadow-slate-900/20 flex justify-between items-center group hover:scale-[1.03] transition-all duration-300 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Selisih Bulan Ini</p>
                    <h3 class="text-2xl font-black text-white tracking-tight">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h3>
                </div>
                <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/40 group-hover:rotate-12 transition-all duration-500">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-2xl shadow-slate-200/60 overflow-hidden">
            <div class="p-8 border-b border-gray-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-slate-900 dark:bg-slate-800 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-exchange-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl dark:text-white font-black text-slate-900 tracking-tight italic">Financial Records</h3>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Periode: <?= $bulan_pilihan_string ?> <?= $tahun_pilihan ?></p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 border-b border-gray-50 dark:border-slate-800 uppercase text-[10px] font-black tracking-[0.2em] bg-slate-50/50 dark:bg-slate-900/50">
                            <th class="px-8 py-6 text-center w-16">No</th>
                            <th class="px-8 py-6">Tanggal & Anggota / Keperluan</th>
                            <th class="px-8 py-6 text-center w-28">Minggu</th>
                            <th class="px-8 py-6">Keterangan Tambahan</th>
                            <th class="px-8 py-6 text-right w-44">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50 font-bold text-sm text-slate-700 dark:text-slate-300">
                        <?php
                        $no = 1;
                        // Query Gabungan: Filter berdasarkan bulan & tahun yang aktif di header saat ini
                        $query_string = "SELECT t.*, m.nama 
                            FROM transaksi t 
                            LEFT JOIN murid m ON t.id_murid = m.id_murid 
                            WHERE (t.jenis='masuk' AND t.bulan='$bulan_pilihan_string' AND YEAR(t.tanggal)='$tahun_pilihan')
                               OR (t.jenis='keluar' AND MONTH(t.tanggal)='$bulan_angka' AND YEAR(t.tanggal)='$tahun_pilihan')
                            ORDER BY t.tanggal DESC, t.id_transaksi DESC";
                        
                        $query = mysqli_query($conn, $query_string);
                        
                        if(mysqli_num_rows($query) > 0):
                            while($row = mysqli_fetch_assoc($query)):
                                $is_masuk = ($row['jenis'] == 'masuk');
                        ?>
                        <tr class="group <?= $is_masuk ? 'hover:bg-emerald-50/20 dark:hover:bg-emerald-500/5' : 'hover:bg-red-50/20 dark:hover:bg-red-500/5' ?> transition-all duration-300">
                            <td class="px-8 py-6 text-center text-slate-400 font-mono">
                                <?= str_pad($no++, 2, "0", STR_PAD_LEFT) ?>
                            </td>
                            
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-1 h-8 <?= $is_masuk ? 'bg-emerald-500' : 'bg-red-500' ?> rounded-full"></div>
                                    <div>
                                        <p class="font-black dark:text-white text-slate-900 text-sm uppercase">
                                            <?= $is_masuk ? htmlspecialchars($row['nama'] ?? 'Umum') : htmlspecialchars($row['keperluan'] ?? 'Pengeluaran Kelas') ?>
                                        </p>
                                        <p class="text-slate-400 text-[10px] font-mono mt-0.5">
                                            <i class="far fa-calendar-alt mr-1"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-8 py-6 text-center">
                                <?php if($is_masuk && !empty($row['minggu'])): ?>
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 rounded-xl text-xs font-black tracking-wider uppercase">
                                        M<?= $row['minggu'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-300 dark:text-slate-700 font-mono">-</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="px-8 py-6 text-xs text-slate-400 dark:text-slate-400 font-medium italic">
                                <?= !empty($row['keterangan']) ? htmlspecialchars($row['keterangan']) : '<span class="text-gray-300 dark:text-slate-700">Tidak ada info</span>' ?>
                            </td>
                            
                            <td class="px-8 py-6 text-right font-mono text-base">
                                <span class="<?= $is_masuk ? 'text-emerald-500' : 'text-red-500' ?> font-black">
                                    <?= ($is_masuk ? '+' : '-') ?> Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                                </span>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="p-16 text-center text-slate-400 font-black uppercase text-xs tracking-widest bg-slate-50/20">
                                    📭 Tidak ada rekaman transaksi arus kas pada periode ini
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/js/sidebar.js"></script>
</body>
</html>