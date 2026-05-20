<?php
$title ="Laporan Kas";
$subtitle ="Kas Kelas";
include "config/app.php"; // Pastikan koneksi database ada

// Logika Filter
$where = "WHERE 1=1";
if (isset($_GET['filter'])) {
    if (!empty($_GET['jenis'])) {
        $jenis = mysqli_real_escape_string($conn, $_GET['jenis']);
        $where .= " AND jenis = '$jenis'";
    }
    if (!empty($_GET['bulan'])) {
        $bulan = mysqli_real_escape_string($conn, $_GET['bulan']);
        $where .= " AND MONTH(tanggal) = '$bulan'";
    }
    if (!empty($_GET['tahun'])) {
        $tahun = mysqli_real_escape_string($conn, $_GET['tahun']);
        $where .= " AND YEAR(tanggal) = '$tahun'";
    }
}

// Ambil total untuk card (berdasarkan filter)
$total_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi $where AND jenis='masuk'"))['total'] ?? 0;
$total_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi $where AND jenis='keluar'"))['total'] ?? 0;
$saldo_akhir = $total_masuk - $total_keluar;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kas | Kas kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] dark:bg-slate-900 transition-colors duration-300">
<?php include "layout/sidebar.php"?>
    <div class="max-w-7xl mx-auto">
    <?php include "layout/header.php"?>
        
        <div class="p-6 dark:bg-slate-900 bg-white rounded-3xl border border-gray-100 shadow-xl ring-1 ring-black/5 overflow-hidden">
            <form action="" method="GET" class="space-y-5">
                <input type="hidden" name="filter" value="true">
                <h2 class="dark:text-white font-black uppercase tracking-tight"><b>Filter Laporan</b></h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-500 font-semibold mb-2 ml-1">Jenis</label>
                        <select name="jenis" class="w-full px-5 py-3 rounded-2xl border-2 border-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm">
                            <option value="">--Semua Jenis--</option>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-500 font-semibold mb-2 ml-1">Bulan</label>
                        <select name="bulan" class="w-full px-5 py-3 rounded-2xl border-2 border-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm">
                            <option value="">--Semua Bulan--</option>
                            <?php
                            $bulan_indo = [1=>"Januari", 2=>"Februari", 3=>"Maret", 4=>"April", 5=>"Mei", 6=>"Juni", 7=>"Juli", 8=>"Agustus", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Desember"];
                            foreach($bulan_indo as $num => $nama) echo "<option value='$num'>$nama</option>";
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-500 font-semibold mb-2 ml-1">Tahun</label>
                        <select name="tahun" class="w-full px-5 py-3 rounded-2xl border-2 border-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm">
                            <option value="">--Semua Tahun--</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#10b981] hover:bg-emerald-600 text-white font-black py-4 rounded-2xl mt-4 shadow-lg shadow-emerald-500/30 transition-all active:scale-95 text-lg">
                    Tampilkan Laporan
                </button>
            </form>
        </div>

        <br>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 shadow-xl flex justify-between items-center group hover:scale-[1.02] transition-all ring-1 ring-black/5">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Masuk (Filtered)</p>
                    <h3 class="text-2xl dark:text-white font-bold text-slate-800">Rp <?= number_format($total_masuk, 0, ',', '.') ?></h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <i class="fas fa-arrow-trend-up"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 shadow-xl flex justify-between items-center group hover:scale-[1.02] transition-all ring-1 ring-black/5">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Keluar (Filtered)</p>
                    <h3 class="text-2xl dark:text-white font-bold text-slate-800">Rp <?= number_format($total_keluar, 0, ',', '.') ?></h3>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-3xl shadow-xl flex justify-between items-center group hover:scale-[1.02] transition-all border-b-4 border-b-blue-500 ring-1 ring-black/5">
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Saldo Akhir</p>
                    <h3 class="text-2xl font-bold text-white">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h3>
                </div>
                <div class="w-12 h-12 bg-blue-500 text-white rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="dark:bg-slate-900 bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden ring-1 ring-black/5">
            <div class="p-6 border-b border-gray-50 bg-slate-50/50">
                <h3 class="text-xl font-black dark:text-white text-slate-800 uppercase tracking-tight italic">Detail Transaksi Terfilter</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-slate-400 uppercase text-[11px] font-black tracking-widest">
                            <th class="px-6 py-4 text-center">No</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-8 py-4 text-center">Jenis</th>
                            <th class="px-8 py-4 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT * FROM transaksi $where ORDER BY tanggal DESC");
                        while($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-5 text-center text-slate-400 font-bold"><?= str_pad($no++, 2, "0", STR_PAD_LEFT) ?></td>
                            <td class="px-6 py-5 dark:text-white font-bold text-slate-800"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td class="px-6 py-5 text-slate-500 font-medium italic"><?= $row['keterangan'] ?></td>
                            <td class="px-8 py-5 text-center">
                                <span class="<?= $row['jenis'] == 'masuk' ? 'bg-emerald-500' : 'bg-red-500' ?> text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase"><?= $row['jenis'] ?></span>
                            </td>
                            <td class="px-8 py-5 text-right font-black <?= $row['jenis'] == 'masuk' ? 'text-emerald-600' : 'text-red-600' ?>">
                                <?= ($row['jenis'] == 'masuk' ? '+' : '-') ?> Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
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