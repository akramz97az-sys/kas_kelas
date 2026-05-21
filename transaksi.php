<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
    include_once $root . "/config/app.php";

    // 1. Tangkap bulan dan tahun dari URL (Sinkron dengan Pilihan Header)
    $bulan_angka = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Mapping angka ke nama bulan string untuk query ke database
    $nama_bulan_list = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $bulan_pilihan_string = $nama_bulan_list[$bulan_angka];

    // 2. Query Dropdown: Ambil semua murid AKTIF + total bayar untuk info di dropdown option
    $query_murid_string = "SELECT m.*, 
        (SELECT SUM(t.jumlah) FROM transaksi t 
         WHERE t.id_murid = m.id_murid 
         AND t.jenis = 'masuk' 
         AND t.bulan = '$bulan_pilihan_string' 
         AND YEAR(t.tanggal) = '$tahun_pilihan') as total_bayar_bulan_ini
        FROM murid m
        WHERE m.status='aktif'
        ORDER BY m.nama ASC";
    $q_murid = mysqli_query($conn, $query_murid_string);

    // 3. Query Tabel Kas Masuk (Riwayat Kas Masuk bulan & tahun pilihan)
    $query_riwayat_masuk = "SELECT t.*, m.nama 
        FROM transaksi t 
        JOIN murid m ON t.id_murid = m.id_murid 
        WHERE t.jenis = 'masuk' 
        AND t.bulan = '$bulan_pilihan_string' 
        AND YEAR(t.tanggal) = '$tahun_pilihan' 
        ORDER BY t.tanggal DESC, t.id_transaksi DESC";
    $q_riwayat_masuk = mysqli_query($conn, $query_riwayat_masuk);

    // 4. Query Tabel Kas Keluar (Riwayat Kas Keluar bulan & tahun pilihan)
    $query_riwayat_keluar = "SELECT * FROM transaksi 
        WHERE jenis = 'keluar' 
        AND tgl_keluar_bulan_ini_atau_apapun_kolomnya (Suaikan dengan kolom bulan/tanggal di db kamu)
        *Untuk amannya kita filter pakai format tanggal / bulan bawaan:*
        AND MONTH(tanggal) = '$bulan_angka' 
        AND YEAR(tanggal) = '$tahun_pilihan' 
        ORDER BY tanggal DESC, id_transaksi DESC";
    
    // Mari kita sederhanakan query kas keluar berdasarkan bulan & tahun yang dipilih:
    $query_riwayat_keluar = "SELECT * FROM transaksi 
        WHERE jenis = 'keluar' 
        AND MONTH(tanggal) = '$bulan_angka' 
        AND YEAR(tanggal) = '$tahun_pilihan' 
        ORDER BY tanggal DESC, id_transaksi DESC";
    $q_riwayat_keluar = mysqli_query($conn, $query_riwayat_keluar);

    // Ambil data murid spesifik jika ada ID yang dilempar dari tombol "Tandai Lunas" Dashboard
    $id_murid_terpilih = isset($_GET['id']) ? intval($_GET['id']) : '';

    $title = "Transaksi";
    $subtitle = "Kas Kelas";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi | Kas Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] dark:bg-slate-950 transition-colors duration-300">
    <?php include "layout/sidebar.php"; ?>
    
    <div class="max-w-7xl mx-auto">
        <?php include "layout/header.php"; ?>

        <div class="mt-8 flex gap-4 p-2 bg-slate-200/50 dark:bg-slate-900 w-fit rounded-[1.5rem] mb-8">
            <button onclick="switchTab('masuk')" id="btn-masuk" class="px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all bg-emerald-500 text-white shadow-lg">
                <i class="fas fa-plus-circle mr-2"></i> Kas Masuk
            </button>
            <button onclick="switchTab('keluar')" id="btn-keluar" class="px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all text-slate-500 hover:bg-slate-100">
                <i class="fas fa-minus-circle mr-2"></i> Kas Keluar
            </button>
        </div>

        <div id="tab-masuk" class="tab-content">
            <div class="mt-8 bg-white dark:bg-slate-900 rounded-[1.2rem] border border-gray-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-white dark:bg-slate-900 p-8 flex items-center gap-4 border-b border-gray-50 dark:border-slate-800">
                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl dark:text-white font-black text-black uppercase tracking-tight">Input Kas Mingguan</h2>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Sistem Multi-Minggu Terstruktur</p>
                    </div>
                </div>

                <form action="proses/proses_kas_mingguan.php" method="POST" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Murid</label>
                            <select name="id_murid" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 font-bold text-slate-700 dark:text-slate-200 outline-none border border-transparent focus:border-emerald-500 focus:bg-white transition-all">
                                <option value="">-- Pilih Nama Murid --</option>
                                <?php 
                                mysqli_data_seek($q_murid, 0); 
                                while($m = mysqli_fetch_assoc($q_murid)): 
                                    $total_bayar = $m['total_bayar_bulan_ini'] ?? 0;
                                    $kas_wajib_bulanan = 40000;
                                    
                                    if($total_bayar == 0) {
                                        $status_teks = " [BELUM BAYAR]";
                                    } elseif($total_bayar < $kas_wajib_bulanan) {
                                        $status_teks = " [SEBAGIAN: Rp " . number_format($total_bayar, 0, ',', '.') . "]";
                                    } else {
                                        $status_teks = " [LUNAS]";
                                    }

                                    $selected = ($m['id_murid'] == $id_murid_terpilih) ? "selected" : "";
                                ?>
                                    <option value="<?= $m['id_murid'] ?>" <?= $selected ?>><?= $m['nama'] ?> <?= $status_teks ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Periode Bulan</label>
                            <select name="bulan" id="select-bulan" onchange="cekSinkronisasiBulan()" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 font-bold text-slate-700 dark:text-slate-200 outline-none border border-transparent focus:border-emerald-500 focus:bg-white transition-all">
                                <?php
                                $bulan_list = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                                $bulan_sekarang = date('n') - 1; 
                                
                                foreach ($bulan_list as $index => $nama_bulan) {
                                    $selected = ($index == $bulan_sekarang) ? "selected" : "";
                                    echo "<option value='$nama_bulan' data-index='$index' $selected>$nama_bulan</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center ml-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Input (Hari Ini)</label>
                                <span id="warning-bulan-lalu" class="text-[9px] font-black text-amber-500 uppercase tracking-wider hidden animate-pulse">
                                    <i class="fas fa-exclamation-triangle"></i> Bayar Kas Bulan Lalu
                                </span>
                            </div>
                            <div class="relative group">
                                <i class="fas fa-calendar-alt absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required
                                    class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 outline-none transition-all font-bold text-slate-700 dark:text-slate-200">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">Centang Minggu & Atur Nominal Bayar (Max Rp 10.000)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <?php for($i = 1; $i <= 4; $i++): ?>
                            <div class="flex flex-col justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/50 gap-3 transition-all duration-300">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="minggu[]" value="<?= $i ?>" id="m<?= $i ?>" onchange="hitungTotalKas()" class="w-5 h-5 rounded text-emerald-500 focus:ring-emerald-400 border-gray-300 cursor-pointer">
                                    <label for="m<?= $i ?>" class="font-black text-sm text-slate-800 dark:text-slate-200 cursor-pointer">Minggu <?= $i ?> (M<?= $i ?>)</label>
                                </div>
                                <div class="flex items-center gap-1 bg-white dark:bg-slate-900 border dark:border-slate-700 px-3 py-2 rounded-xl shadow-sm">
                                    <span class="text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="nominal[<?= $i ?>]" id="nominal_m<?= $i ?>" value="10000" min="1" max="10000" oninput="validasiDanHitung(this)" class="w-full text-xs font-black text-slate-700 dark:text-slate-200 bg-transparent outline-none text-right">
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan Tambahan</label>
                            <textarea name="keterangan" placeholder="Contoh: Dibayar lunas langsung"
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 outline-none transition-all font-bold text-slate-700 dark:text-slate-200 resize-none" rows="2"></textarea>
                        </div>

                        <div class="p-5 bg-slate-900 text-white rounded-2xl flex justify-between items-center shadow-inner h-[80px]">
                            <div>
                                <p class="text-[9px] uppercase font-bold tracking-widest text-slate-400">Total Bayar</p>
                                <h3 class="text-xl font-black text-emerald-400 mt-0.5" id="display-total-kas">Rp 0</h3>
                            </div>
                            <span id="display-minggu-hitung" class="text-[10px] font-mono font-bold bg-white/10 px-2.5 py-1 rounded-lg">0 Minggu</span>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-500/30 transition-all active:scale-[0.98] text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                        <i class="fas fa-paper-plane"></i> Simpan Setoran Kas
                    </button>
                </form>
            </div>

            <div class="mt-12 bg-white dark:bg-slate-900 rounded-[1.2rem] border border-gray-100 dark:border-slate-800 shadow-2xl overflow-hidden">
                <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-black uppercase text-slate-800 dark:text-slate-100">Riwayat Setoran Uang Kas Masuk</h3>
                        <p class="text-xs text-slate-400 font-bold">Periode: <?= $bulan_pilihan_string ?> <?= $tahun_pilihan ?></p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-slate-800 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50 dark:bg-slate-900">
                                <th class="p-5 text-center w-16">No</th>
                                <th class="p-5">Tanggal</th>
                                <th class="p-5">Nama Murid</th>
                                <th class="p-5 text-center">Minggu</th>
                                <th class="p-5 text-right">Jumlah Setor</th>
                                <th class="p-5">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50 font-bold text-sm text-slate-700 dark:text-slate-300">
                            <?php 
                            $no_masuk = 1;
                            if (mysqli_num_rows($q_riwayat_masuk) > 0):
                                while($rm = mysqli_fetch_assoc($q_riwayat_masuk)):
                            ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-5 text-center text-slate-400 font-mono"><?= $no_masuk++ ?></td>
                                    <td class="p-5 font-mono text-xs"><?= date('d-m-Y', strtotime($rm['tanggal'])) ?></td>
                                    <td class="p-5 font-black text-slate-900 dark:text-white"><?= $rm['nama'] ?></td>
                                    <td class="p-5 text-center"><span class="px-2 py-1 bg-emerald-500/10 text-emerald-500 rounded-lg text-xs">M<?= $rm['minggu'] ?></span></td>
                                    <td class="p-5 text-right text-emerald-500 font-mono">Rp <?= number_format($rm['jumlah'], 0, ',', '.') ?></td>
                                    <td class="p-5 text-xs text-slate-400"><?= htmlspecialchars($rm['keterangan'] ?? '-') ?></td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-slate-400 font-bold uppercase text-xs tracking-wider">
                                        Belum ada data setoran kas masuk bulan ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-keluar" class="tab-content hidden">
            <div class="mt-8 bg-white dark:bg-slate-900 rounded-[1.2rem] border border-gray-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-slate-900 dark:bg-white p-8 flex items-center gap-4 border-b-4 border-red-500">
                    <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-500/30">
                        <i class="fas fa-hand-holding-dollar text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl dark:text-slate-900 font-black text-white dark:text-black uppercase tracking-tight">Input Kas Keluar</h2>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Catat pengeluaran dana kelas</p>
                    </div>
                </div>
                <form action="proses/proses_kas_keluar.php" method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Keluar</label>
                        <div class="relative group">
                            <i class="fas fa-calendar-day absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white dark:bg-slate-800 dark:text-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Untuk Keperluan</label>
                        <div class="relative group">
                            <i class="fas fa-shopping-cart absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                            <input type="text" name="keperluan" placeholder="Misal: Foto Copy Modul" required class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white dark:bg-slate-800 dark:text-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nominal (Rp)</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-slate-400 group-focus-within:text-red-500 transition-colors">Rp</span>
                            <input type="number" name="jumlah" placeholder="0" required class="w-full pl-14 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white dark:bg-slate-800 dark:text-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan Tambahan</label>
                        <textarea name="keterangan" placeholder="Detail barang yang dibeli..." class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white dark:bg-slate-800 dark:text-white outline-none transition-all font-bold text-slate-700 resize-none" rows="1"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-slate-900 dark:bg-white hover:bg-red-600 dark:hover:bg-red-500 text-white dark:text-slate-900 font-black py-5 rounded-2xl shadow-xl shadow-slate-900/20 transition-all active:scale-[0.98] text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                            <i class="dark:text-slate-900 fas fa-minus-circle"></i>
                            <span>Keluarkan Dana</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-12 bg-white dark:bg-slate-900 rounded-[1.2rem] border border-gray-100 dark:border-slate-800 shadow-2xl overflow-hidden">
                <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-black uppercase text-slate-800 dark:text-slate-100">Riwayat Pengeluaran Uang Kas</h3>
                        <p class="text-xs text-slate-400 font-bold">Periode: <?= $bulan_pilihan_string ?> <?= $tahun_pilihan ?></p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-slate-800 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50 dark:bg-slate-900">
                                <th class="p-5 text-center w-16">No</th>
                                <th class="p-5">Tanggal</th>
                                <th class="p-5">Keperluan</th>
                                <th class="p-5 text-right">Nominal Keluar</th>
                                <th class="p-5">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50 font-bold text-sm text-slate-700 dark:text-slate-300">
                            <?php 
                            $no_keluar = 1;
                            if (mysqli_num_rows($q_riwayat_keluar) > 0):
                                while($rk = mysqli_fetch_assoc($q_riwayat_keluar)):
                            ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-5 text-center text-slate-400 font-mono"><?= $no_keluar++ ?></td>
                                    <td class="p-5 font-mono text-xs"><?= date('d-m-Y', strtotime($rk['tanggal'])) ?></td>
                                    <td class="p-5 font-black text-slate-900 dark:text-white"><?= htmlspecialchars($rk['keperluan']) ?></td>
                                    <td class="p-5 text-right text-red-500 font-mono">Rp <?= number_format($rk['jumlah'], 0, ',', '.') ?></td>
                                    <td class="p-5 text-xs text-slate-400"><?= htmlspecialchars($rk['keterangan'] ?? '-') ?></td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-slate-400 font-bold uppercase text-xs tracking-wider">
                                        Belum ada pengeluaran dana kas bulan ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        function switchTab(type) {
            const tabMasuk = document.getElementById('tab-masuk');
            const tabKeluar = document.getElementById('tab-keluar');
            const btnMasuk = document.getElementById('btn-masuk');
            const btnKeluar = document.getElementById('btn-keluar');

            if (type === 'masuk') {
                tabMasuk.classList.remove('hidden');
                tabKeluar.classList.add('hidden');
                btnMasuk.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all bg-emerald-500 text-white shadow-lg";
                btnKeluar.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all text-slate-500 hover:bg-slate-100";
            } else {
                tabKeluar.classList.remove('hidden');
                tabMasuk.classList.add('hidden');
                btnKeluar.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all bg-red-500 text-white shadow-lg";
                btnMasuk.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all text-slate-500 hover:bg-slate-100";
            }
        }

        function validasiDanHitung(inputElement) {
            let nilai = parseInt(inputElement.value);
            if (nilai > 10000) inputElement.value = 10000;
            if (nilai < 0 || isNaN(nilai)) inputElement.value = 1;
            hitungTotalKas();
        }

        function hitungTotalKas() {
            let total = 0;
            let jumlahMinggu = 0;
            for (let i = 1; i <= 4; i++) {
                const checkbox = document.getElementById(`m${i}`);
                const inputNominal = document.getElementById(`nominal_m${i}`);
                let nominalUang = parseInt(inputNominal.value) || 0;

                if (checkbox && checkbox.checked) {
                    total += nominalUang;
                    jumlahMinggu++;
                    checkbox.parentElement.parentElement.classList.add('border-emerald-500', 'dark:border-emerald-500', 'bg-emerald-50/10');
                } else if (checkbox) {
                    checkbox.parentElement.parentElement.classList.remove('border-emerald-500', 'dark:border-emerald-500', 'bg-emerald-50/10');
                }
            }
            document.getElementById('display-total-kas').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('display-minggu-hitung').innerText = `${jumlahMinggu} Minggu`;
        }

        function cekSinkronisasiBulan() {
            const selectBulan = document.getElementById('select-bulan');
            if(!selectBulan) return;
            
            const bulanSekarang = new Date().getMonth(); 
            const namaBulanList = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const indexBulanDipilih = namaBulanList.indexOf(selectBulan.value);
            const warningText = document.getElementById('warning-bulan-lalu');

            if (warningText) {
                if (indexBulanDipilih < bulanSekarang && indexBulanDipilih !== -1) {
                    warningText.classList.remove('hidden');
                } else {
                    warningText.classList.add('hidden');
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            cekSinkronisasiBulan();
            hitungTotalKas();
        });
    </script>

    <script src="assets/js/sidebar.js"></script>
</body>
</html>