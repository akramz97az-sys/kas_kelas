<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
    include_once $root . "/config/app.php";

    // Ambil bulan dan tahun dari URL atau gunakan waktu sekarang (Format angka: '01' - '12')
    $bulan_angka = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Mapping angka bulan ke nama bulan string (Sesuai dengan isi database kas masuk)
    $nama_bulan_list = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $bulan_pilihan_string = $nama_bulan_list[$bulan_angka];

    // 1. Hitung TOTAL MURID yang AKTIF saja
    $query_murid = mysqli_query($conn, "SELECT COUNT(*) as total FROM murid WHERE status='aktif'");
    $total_murid_aktif = mysqli_fetch_assoc($query_murid)['total'] ?? 0;

    // 2. Hitung MURID SUDAH BAYAR (Unik per murid pada bulan & tahun ini)
    $q_sudah = mysqli_query($conn, "SELECT COUNT(DISTINCT id_murid) as total FROM transaksi 
                                    WHERE jenis='masuk' 
                                    AND bulan = '$bulan_pilihan_string' 
                                    AND YEAR(tanggal) = '$tahun_pilihan'");
    $sudah_bayar = mysqli_fetch_assoc($q_sudah)['total'] ?? 0;

    // 3. Murid Belum Bayar sama sekali di bulan pilihan
    $belum_bayar = $total_murid_aktif - $sudah_bayar;

    // 4. Saldo Kas Akhir Global (Total Masuk - Total Keluar Selamanya)
    $q_saldo = mysqli_query($conn, "SELECT 
        SUM(CASE WHEN jenis='masuk' THEN jumlah ELSE 0 END) as masuk,
        SUM(CASE WHEN jenis='keluar' THEN jumlah ELSE 0 END) as keluar 
        FROM transaksi");
    $data_kas = mysqli_fetch_assoc($q_saldo);
    $saldo_akhir = ($data_kas['masuk'] ?? 0) - ($data_kas['keluar'] ?? 0);

    // 5. FIX Pemasukan & Pengeluaran periode terpilih (Membaca MONTH() dari tanggal untuk jenis 'keluar')
    $q_masuk_bulan_ini = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='masuk' AND bulan = '$bulan_pilihan_string' AND YEAR(tanggal) = '$tahun_pilihan'");
    $pemasukan_bulan = mysqli_fetch_assoc($q_masuk_bulan_ini)['total'] ?? 0;

    // Disini perubahannya: untuk pengeluaran kita pakai MONTH(tanggal) berdasar parameter $bulan_angka
    $q_keluar_bulan_ini = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='keluar' AND MONTH(tanggal) = '$bulan_angka' AND YEAR(tanggal) = '$tahun_pilihan'");
    $pengeluaran_bulan = mysqli_fetch_assoc($q_keluar_bulan_ini)['total'] ?? 0;

    // 6. Ambil Daftar Murid Aktif + Hitung total bayar mereka dengan handling NULL menggunakan IFNULL
    $query_daftar_tunggu_string = "SELECT m.*, 
        IFNULL((SELECT SUM(t.jumlah) FROM transaksi t 
          WHERE t.id_murid = m.id_murid 
          AND t.jenis = 'masuk' 
          AND t.bulan = '$bulan_pilihan_string' 
          AND YEAR(t.tanggal) = '$tahun_pilihan'), 0) as total_pembayaran_bulan_ini
        FROM murid m
        WHERE m.status='aktif'
        ORDER BY total_pembayaran_bulan_ini ASC, m.nama ASC";
        
    $q_daftar_tunggu = mysqli_query($conn, $query_daftar_tunggu_string);

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
<body class="bg-gray-50 p-6 font-[sans-serif] dark:bg-slate-950 transition-colors duration-300">
    <?php include "layout/sidebar.php"; ?>
    
    <div class="max-w-7xl mx-auto">
        <?php include "layout/header.php"; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Total Murid</p>
                    <h2 class="text-4xl dark:text-white font-black text-slate-900 mt-2"><?= $total_murid_aktif ?></h2>
                    <div class="flex items-center gap-1 mt-2 text-emerald-500 text-xs font-bold">
                        <i class="fas fa-arrow-up"></i> <span class="font-normal text-slate-400">Aktif</span>
                    </div>
                </div>
                <div class="bg-emerald-50 dark:bg-slate-800 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Sudah Bayar</p>
                    <h2 class="text-4xl dark:text-white font-black text-slate-900 mt-2"><?= $sudah_bayar ?>
                    <span class="text-lg text-slate-400 font-bold">/<?= $total_murid_aktif ?></span></h2>
                    <p class="text-emerald-500 text-xs font-bold mt-2 italic">Bulan Ini</p>
                </div>
                <div class="bg-emerald-100 dark:bg-slate-800 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>

            <div class="bg-red-50/50 dark:bg-slate-900 p-6 rounded-[2rem] border border-red-100 dark:border-slate-800 shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
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

            <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div class="w-full">
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Saldo Kas</p>
                    <h2 class="text-2xl font-black text-white mt-2 leading-tight">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h2>
                    
                    <div class="mt-3 pt-2 border-t border-white/10 space-y-1">
                        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Periode Ini:</div>
                        
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-400"><i class="fas fa-arrow-down text-emerald-400 mr-1 text-[9px]"></i> Kas Masuk:</span>
                            <span class="text-emerald-400 font-black">+ Rp <?= number_format($pemasukan_bulan, 0, ',', '.') ?></span>
                        </div>

                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-400"><i class="fas fa-arrow-up text-red-400 mr-1 text-[9px]"></i> Kas Keluar:</span>
                            <span class="text-red-400 font-black">- Rp <?= number_format($pengeluaran_bulan, 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 w-12 h-12 rounded-2xl flex items-center justify-center text-white backdrop-blur-md shrink-0 ml-2">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-gray-50 dark:border-slate-800 flex justify-between items-center bg-slate-50/30 dark:bg-slate-800/20">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Daftar Murid</h3>
                    <p class="text-slate-400 font-medium text-sm mt-1">Status pembayaran kas bulan ini (<?= $bulan_pilihan_string . " " . $tahun_pilihan ?>)</p>
                </div>
                <div class="relative hidden md:block">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="cariNama" placeholder="Cari nama..." class="bg-slate-100 dark:bg-slate-800 border-none rounded-xl pl-10 pr-4 py-2 text-sm outline-none dark:text-white focus:ring-2 focus:ring-emerald-500/20 transition-all">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[11px] font-black tracking-[0.15em] border-b border-gray-50 dark:border-slate-800">
                            <th class="px-8 py-5">No</th>
                            <th class="px-8 py-5">Info Murid</th>
                            <th class="px-8 py-5">Kelas</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody" class="divide-y divide-gray-50 dark:divide-slate-800/50">
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($q_daftar_tunggu) > 0):
                        while($row = mysqli_fetch_assoc($q_daftar_tunggu)): 
                            $total_bayar = $row['total_pembayaran_bulan_ini'] ?? 0;
                            $kas_wajib = 40000;
                            
                            $inisial = strtoupper(substr($row['nama'], 0, 1));
                            
                            // Logika Pembuatan Badge Status
                            if($total_bayar == 0) {
                                $badge_status = '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900/50">Belum Bayar</span>';
                            } elseif($total_bayar < $kas_wajib) {
                                $badge_status = '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50">Sebagian (Rp '.number_format($total_bayar,0,',','.').')</span>';
                            } else {
                                $badge_status = '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50">Lunas</span>';
                            }
                    ?>
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all row-murid">
                            <td class="px-8 py-6 font-bold text-slate-400"><?= $no++ ?></td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center font-black text-slate-500 dark:text-slate-400"><?= $inisial ?></div>
                                    <div>
                                        <p class="font-black dark:text-white text-slate-900 uppercase text-sm nama-murid"><?= htmlspecialchars($row['nama']) ?></p>
                                        <p class="text-slate-400 text-[10px] font-mono italic">NISN: <?= htmlspecialchars($row['nisn']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 font-bold text-slate-600 dark:text-slate-300 text-sm"><?= htmlspecialchars($row['kelas']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <?= $badge_status ?>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <?php if($total_bayar < $kas_wajib): ?>
                                    <a href="transaksi.php?id=<?= $row['id_murid'] ?>&bulan=<?= $bulan_angka ?>&tahun=<?= $tahun_pilihan ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl inline-flex items-center gap-2 shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                                        <i class="fas fa-check-circle group-hover:rotate-[360deg] transition-transform duration-500"></i>
                                        <span class="font-black text-xs uppercase">Bayar Kas</span>
                                    </a>
                                <?php else: ?>
                                    <button disabled class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 font-black text-xs uppercase tracking-wider rounded-xl cursor-not-allowed border dark:border-slate-700 inline-flex items-center gap-2">
                                        <i class="fas fa-check-double"></i> Sudah Lunas
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 font-bold">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-check-circle text-3xl text-emerald-500"></i>
                                    <span>Tidak ada data murid aktif atau semua sudah diatur! 🎉</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('cariNama').addEventListener('input', function() {
            let filter = this.value.toUpperCase();
            let rows = document.querySelectorAll('.row-murid');
            
            rows.forEach(row => {
                let nama = row.querySelector('.nama-murid').textContent || row.querySelector('.nama-murid').innerText;
                if (nama.toUpperCase().indexOf(filter) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
    <script src="assets/js/sidebar.js"></script>
</body>
</html>