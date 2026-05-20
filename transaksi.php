<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

$title = "Transaksi Kas";
$subtitle = "Kas Kelas";

// LOGIKA BARU: Hanya mengambil murid yang statusnya 'aktif'
$query_murid = mysqli_query($conn, "SELECT id_murid, nama, kelas FROM murid WHERE status='aktif' ORDER BY nama ASC");

// Ambil data riwayat transaksi kas masuk terbaru
$query_riwayat_masuk = mysqli_query($conn, "SELECT t.*, m.nama, m.kelas 
                                           FROM transaksi t 
                                           INNER JOIN murid m ON t.id_murid = m.id_murid 
                                           WHERE t.jenis='masuk' 
                                           ORDER BY t.tanggal DESC LIMIT 10");

$query_riwayat_keluar = mysqli_query($conn, "SELECT * FROM transaksi 
                                            WHERE jenis='keluar' 
                                            ORDER BY tanggal DESC LIMIT 10");
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
            <div class="mt-8 bg-white dark:bg-slate-900 rounded-[1.2rem] border border-gray-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-white dark:bg-white p-8 flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl dark:text-slate-900 font-black text-black uppercase tracking-tight">Input Kas Masuk</h2>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Catat setoran murid</p>
                    </div>
                </div>

                <form action="proses/proses_kas_masuk.php" method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Murid</label>
                            <select name="id_murid" required class="w-full pl-6 pr-5 py-4 rounded-2xl bg-slate-50 outline-none font-bold text-slate-700">
                                <option value="">-- Pilih Nama Murid --</option>
                                <?php while($m = mysqli_fetch_assoc($query_murid)): ?>
                                    <option value="<?= $m['id_murid'] ?>"><?= $m['nama'] ?> (<?= $m['kelas'] ?>)</option>
                                <?php endwhile; ?>
                            </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Transaksi</label>
                        <div class="relative group">
                            <i class="fas fa-calendar-alt absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required
                                class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah Setoran (Rp)</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-slate-400 group-focus-within:text-emerald-500 transition-colors">Rp</span>
                            <input type="number" name="jumlah" placeholder="Contoh: 50000" required
                                class="w-full pl-14 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan</label>
                        <textarea name="keterangan" placeholder="Contoh: Kas Minggu ke-1"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700 resize-none" rows="1"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" 
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-500/30 transition-all active:scale-[0.98] text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                            <i class="fas fa-paper-plane"></i>
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-12 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-slate-200/60 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-slate-50/30">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                            <i class="fas fa-history text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl dark:text-white font-black text-slate-900 tracking-tight">Kas Masuk</h3>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Transaksi Terbaru</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[11px] font-black tracking-[0.15em]">
                                <th class="px-8 py-6 text-center">No</th>
                                <th class="px-8 py-6">Informasi Murid</th>
                                <th class="px-8 py-6 text-center">Bulan</th>
                                <th class="px-8 py-6 text-center">Jumlah</th>
                                <th class="px-8 py-6 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                                <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($query_riwayat_masuk)): 
                            ?>
                            <tr class="group hover:bg-slate-50 transition-all duration-300">
                                <td class="px-8 py-6 text-center text-slate-400 font-bold"><?= str_pad($no++, 2, "0", STR_PAD_LEFT) ?></td>
                                <td class="px-8 py-6">
                                    <p class="font-black dark:text-white text-slate-900 text-sm uppercase italic"><?= htmlspecialchars($row['nama'] ?? 'Tanpa Nama') ?></p>
                                </td>
                                <td class="px-8 py-6 text-center text-slate-500 text-xs font-bold">
                                    <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-emerald-600 font-black text-sm">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-slate-500 text-xs italic"><?= htmlspecialchars($row['keterangan']) ?></p>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-keluar" class="tab-content hidden">
            <div class="mt-8 bg-white dark:bg-slate-900 rounded-[1.2rem] border border-gray-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-slate-900 dark:bg-white p-8 flex items-center gap-4 border-b-4 border-red-500">
                    <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-500/30">
                        <i class="fas fa-hand-holding-dollar text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl dark:text-slate-900 font-black text-white uppercase tracking-tight">Input Kas Keluar</h2>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Catat pengeluaran dana kelas</p>
                    </div>
                </div>

                <form action="proses/proses_kas_keluar.php" method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Keluar</label>
                        <div class="relative group">
                            <i class="fas fa-calendar-day absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required
                                class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Untuk Keperluan</label>
                        <div class="relative group">
                            <i class="fas fa-shopping-cart absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                            <input type="text" name="keperluan" placeholder="Misal: Foto Copy Modul" required
                                class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nominal (Rp)</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-slate-400 group-focus-within:text-red-500 transition-colors">Rp</span>
                            <input type="number" name="jumlah" placeholder="0" required
                                class="w-full pl-14 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan Tambahan</label>
                        <textarea name="keterangan" placeholder="Detail barang yang dibeli..."
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-red-500 focus:bg-white outline-none transition-all font-bold text-slate-700 resize-none" rows="1"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" 
                        class="w-full bg-slate-900 dark:bg-white hover:bg-red-600 dark:hover:bg-red-500 text-white dark:text-slate-900 font-black py-5 rounded-2xl shadow-xl shadow-slate-900/20 transition-all active:scale-[0.98] text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                            <i class="dark:text-slate-900 fas fa-minus-circle"></i>
                            <span>Keluarkan Dana</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-12 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-slate-200/60 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-slate-50/30">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                            <i class="fas fa-receipt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl dark:text-white font-black text-slate-900 tracking-tight">Log Pengeluaran</h3>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Arus Dana Keluar</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[11px] font-black tracking-[0.15em]">
                                <th class="px-8 py-6 text-center">No</th>
                                <th class="px-8 py-6">Detail Pengeluaran</th>
                                <th class="px-8 py-6 text-center">Nominal</th>
                                <th class="px-8 py-6">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php 
                            $no = 1;
                            // Query untuk mengambil data kas keluar
                            $query_keluar = mysqli_query($conn, "SELECT * FROM transaksi WHERE jenis='keluar' ORDER BY tanggal DESC LIMIT 10");
                            while($row_k = mysqli_fetch_assoc($query_keluar)): 
                            ?>
                            <tr class="group hover:bg-red-50/30 transition-all duration-300">
                                <td class="px-8 py-6 text-center text-slate-400 font-bold"><?= str_pad($no++, 2, "0", STR_PAD_LEFT) ?></td>
                                <td class="px-8 py-6">
                                    <div>
                                        <p class="font-black dark:text-white text-slate-900 text-sm uppercase tracking-tight">
                                            <?= htmlspecialchars($row_k['keterangan'] ?? 'Pengeluaran') ?>
                                        </p>
                                        <p class="text-slate-400 text-[10px] font-bold italic">
                                            <?= date('d F Y', strtotime($row_k['tanggal'])) ?>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-red-100 text-red-600 px-4 py-1.5 rounded-xl text-xs font-black">
                                        - Rp <?= number_format($row_k['jumlah'], 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-slate-500 dark:text-white text-sm font-medium italic">
                                        Disimpan oleh sistem
                                    </p>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            
                            <?php if(mysqli_num_rows($query_keluar) == 0): ?>
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-bold italic">Belum ada riwayat pengeluaran.</td>
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
    </script>
    <script src="assets/js/sidebar.js"></script>
</body>
</html>