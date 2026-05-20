<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
    include_once $root . "/config/app.php";

    // Ambil bulan dan tahun dari URL atau gunakan waktu sekarang
    $bulan_pilihan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // 1. Hitung TOTAL MURID yang AKTIF saja
    $query_murid = mysqli_query($conn, "SELECT COUNT(*) as total FROM murid WHERE status='aktif'");
    $total_murid_aktif = mysqli_fetch_assoc($query_murid)['total'] ?? 0;

    // 2. Hitung MURID SUDAH BAYAR (Bulan & Tahun Pilihan)
    $q_sudah = mysqli_query($conn, "SELECT COUNT(DISTINCT id_murid) as total FROM transaksi 
                                    WHERE jenis='Masuk' AND MONTH(tanggal) = '$bulan_pilihan' AND YEAR(tanggal) = '$tahun_pilihan'");
    $sudah_bayar = mysqli_fetch_assoc($q_sudah)['total'] ?? 0;

    // 3. Murid Belum Bayar (Berdasarkan murid aktif)
    $belum_bayar = $total_murid_aktif - $sudah_bayar;

    // 4. Saldo Kas Akhir (Total Masuk - Total Keluar Selamanya)
    $q_saldo = mysqli_query($conn, "SELECT 
        SUM(CASE WHEN jenis='Masuk' THEN jumlah ELSE 0 END) as masuk,
        SUM(CASE WHEN jenis='Keluar' THEN jumlah ELSE 0 END) as keluar 
        FROM transaksi");
    $data_kas = mysqli_fetch_assoc($q_saldo);
    $saldo_akhir = ($data_kas['masuk'] ?? 0) - ($data_kas['keluar'] ?? 0);

    // Daftar murid belum bayar (Limit 10 untuk dashboard)
    $q_daftar_tunggu = mysqli_query($conn, "SELECT * FROM murid 
                                          WHERE status='aktif' 
                                          AND id_murid NOT IN (
                                              SELECT id_murid FROM transaksi 
                                              WHERE MONTH(tanggal) = '$bulan_pilihan' 
                                              AND YEAR(tanggal) = '$tahun_pilihan' 
                                              AND jenis='Masuk'
                                          ) LIMIT 10");
    $title = "Dashboard"; 
    $subtitle = "Kas Kelas"; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kas Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body{
            overflow-x:hidden;
        }
    </style>
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
    <?php include "layout/sidebar.php"; ?>
    
    <div class="max-w-7xl mx-auto">
        <?php include "layout/header.php"; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 shadow-xl shadow-slate-200/50 flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Total Murid</p>
                    <h2 class="text-4xl dark:text-white font-black text-slate-900 mt-2"><?= $total_murid_aktif ?></h2>
                    <div class="flex items-center gap-1 mt-2 text-emerald-500 text-xs font-bold">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
                <div class="bg-emerald-50 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 shadow-xl shadow-slate-200/50 flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Sudah Bayar</p>
                    <h2 class="text-4xl dark:text-white font-black text-slate-900 mt-2"><?= $sudah_bayar ?>
                    <span class="text-lg text-slate-400 font-bold">/<?= $total_murid_aktif ?></span></h2>
                    <p class="text-emerald-500 text-xs font-bold mt-2 italic">Bulan Ini</p>
                </div>
                <div class="bg-emerald-100 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>

            <div class="bg-red-50/50 dark:bg-slate-900 p-6 rounded-[2rem] border border-red-100 shadow-xl shadow-red-200/30 flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-red-500 font-bold text-xs uppercase tracking-widest">Belum Bayar</p>
                    <h2 class="text-4xl dark:text-white font-black text-slate-900 mt-2"><?=$belum_bayar?>
                    <span class="text-lg text-slate-400 font-bold">/<?= $total_murid_aktif ?></span></h2>
                    <p class="text-red-400 text-xs font-bold mt-2">Perlu Ditagih</p>
                </div>
                <div class="bg-red-500 w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-500/40">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl shadow-slate-900/20 flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Saldo Kas</p>
                    <h2 class="text-2xl font-black text-white mt-2 leading-tight">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h2>
                    <p class="text-emerald-400 text-[10px] font-bold mt-2">Aman / +12%</p>
                    <div class="mt-2 flex items-center gap-2">
                        <?php
                            // Hitung pemasukan khusus bulan yang dipilih
                            $q_bulan_ini = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='Masuk' AND MONTH(tanggal) = '$bulan_pilihan' AND YEAR(tanggal) = '$tahun_pilihan'");
                            $pemasukan_bulan = mysqli_fetch_assoc($q_bulan_ini)['total'] ?? 0;
                        ?>
                        <span class="text-emerald-400 text-[10px] font-bold">+ Rp <?= number_format($pemasukan_bulan, 0, ',', '.') ?> (Bulan Ini)</span>
                    </div>
                </div>
                <div class="bg-white/10 w-12 h-12 rounded-2xl flex items-center justify-center text-white backdrop-blur-md">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-slate-200/60 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-slate-50/30">
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Murid</h3>
                    </div>
                    <p class="text-slate-400 font-medium text-sm mt-1">Murid yang belum melunasi kas bulan ini(
                        <?php 
                            $nama_bulan = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                            echo $nama_bulan[$bulan_pilihan] . " " . $tahun_pilihan; 
                        ?>
                        )</p>
                </div>
                <div class="relative hidden md:block">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Cari nama..." class="bg-slate-100 border-none rounded-xl pl-10 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[11px] font-black tracking-[0.15em]">
                            <th class="px-8 py-5">No</th>
                            <th class="px-8 py-5">Info Murid</th>
                            <th class="px-8 py-5">Kelas</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php 
                            $no = 1;
                            if(mysqli_num_rows($q_daftar_tunggu) > 0):
                                while($row = mysqli_fetch_assoc($q_daftar_tunggu)) : 
                                    $inisial = strtoupper(substr($row['nama'], 0, 2));
                                    
                                    // --- LOGIKA STATUS ---
                                    $kas_wajib = 20000; 
                                    $total_bayar = $row['total_pembayaran_bulan_ini'] ?? 0; 

                                    if ($total_bayar == 0) {
                                        $status_label = "Belum Bayar";
                                        $warna_class = "bg-red-100 text-red-600 border-red-200";
                                    } elseif ($total_bayar < $kas_wajib) {
                                        $status_label = "Sebagian Bayar";
                                        $warna_class = "bg-yellow-100 text-yellow-600 border-yellow-200";
                                    } else {
                                        $status_label = "Lunas";
                                        $warna_class = "bg-green-100 text-green-600 border-green-200";
                                    }
                                    // --- END LOGIKA ---
                        ?>
                        <tr class="group hover:bg-slate-50 transition-all">
                            <td class="px-8 py-6 font-bold text-slate-400"><?= $no++ ?></td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center font-black text-slate-500"><?= $inisial ?></div>
                                    <div>
                                        <p class="font-black dark:text-white text-slate-900 uppercase text-sm"><?= $row['nama'] ?></p>
                                        <p class="text-slate-400 text-[10px] font-mono italic">NISN: <?= $row['nisn'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 font-bold text-slate-600 dark:text-slate-300 text-sm"><?= $row['kelas'] ?></td>
                            
                            <td class="px-8 py-6 text-center">
                                <span class="<?= $warna_class ?> border px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    <?= $status_label ?>
                                </span>
                            </td>

                            <td class="px-8 py-6 text-center">
                                <a href="transaksi.php?id=<?= $row['id_murid'] ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl inline-flex items-center gap-2 shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-check-circle group-hover:rotate-[360deg] transition-transform duration-500"></i>
                                    <span class="font-black text-xs uppercase">Tandai Lunas</span>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 font-bold">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-check-circle text-3xl text-emerald-500"></i>
                                    <span>Semua murid sudah lunas bulan ini! 🎉</span>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script src="assets/js/sidebar.js"></script>
</html>