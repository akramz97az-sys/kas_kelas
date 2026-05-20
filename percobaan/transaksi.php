<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

// LOGIKA BARU: Hanya mengambil murid yang statusnya 'aktif'
$query_murid = mysqli_query($conn, "SELECT id_murid, nama, kelas FROM murid WHERE status='aktif' ORDER BY nama ASC");

// Ambil data riwayat transaksi kas masuk terbaru
$query_riwayat = mysqli_query($conn, "SELECT t.*, m.nama FROM transaksi t 
                                     LEFT JOIN murid m ON t.id_murid = m.id_murid 
                                     WHERE t.jenis='masuk' 
                                     ORDER BY t.tanggal DESC LIMIT 10");
?>

<div id="tab-masuk" class="tab-content">
    <div class="mt-8 bg-white dark:bg-slate-900 rounded-[1.2rem] border border-gray-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
        <div class="bg-white dark:bg-white p-8 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                <i class="fas fa-file-invoice-dollar text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl dark:text-slate-900 font-black text-black uppercase tracking-tight">Input Kas Masuk</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Catat setoran murid aktif</p>
            </div>
        </div>

        <form action="proses/proses_kas_masuk.php" method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Murid (Hanya Aktif)</label>
                <div class="relative group">
                    <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <select name="id_murid" required class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                        <option value="" selected disabled hidden>-- Pilih Nama Murid --</option>
                        <?php while($m = mysqli_fetch_assoc($query_murid)): ?>
                            <option value="<?= $m['id_murid'] ?>"><?= htmlspecialchars($m['nama']) ?> (<?= $m['kelas'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                </div>
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
                    <h3 class="text-xl dark:text-white font-black text-slate-900 tracking-tight">Riwayat Kas Masuk</h3>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">10 Transaksi Terakhir</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 uppercase text-[11px] font-black tracking-[0.15em]">
                        <th class="px-8 py-6 text-center">No</th>
                        <th class="px-8 py-6">Nama Murid</th>
                        <th class="px-8 py-6 text-center">Tanggal</th>
                        <th class="px-8 py-6 text-center">Jumlah</th>
                        <th class="px-8 py-6">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($query_riwayat)): 
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