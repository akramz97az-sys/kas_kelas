<?php
$title ="Arus Kas";
$subtitle ="Kas Kelas";
include "config/app.php";

// Ambil Saldo Keseluruhan
$total_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='masuk'"))['total'] ?? 0;
$total_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='keluar'"))['total'] ?? 0;
$saldo_akhir = $total_masuk - $total_keluar;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arus Kas|Kas Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
    <?php include "layout/sidebar.php"; ?>
    
    <div class="max-w-7xl mx-auto">
        <?php include "layout/header.php"; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 mb-8">
            <div class="bg-white p-7 dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-slate-200/50 flex justify-between items-center group hover:scale-[1.03] transition-all duration-300 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Kas Masuk</p>
                    <h3 class="text-2xl dark:text-white font-black text-slate-900">Rp <?= number_format($total_masuk, 0, ',', '.') ?></h3>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-arrow-trend-up text-xl"></i>
                </div>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50/50 rounded-full blur-3xl group-hover:bg-emerald-200/50 transition-colors"></div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-7 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-slate-200/50 flex justify-between items-center group hover:scale-[1.03] transition-all duration-300 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Kas Keluar</p>
                    <h3 class="text-2xl dark:text-white font-black text-slate-900 tracking-tight">Rp <?= number_format($total_keluar, 0, ',', '.') ?></h3>
                </div>
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fas fa-arrow-trend-down text-xl"></i>
                </div>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-red-50/50 rounded-full blur-3xl group-hover:bg-red-200/50 transition-colors"></div>
            </div>

            <div class="bg-slate-900 p-7 rounded-[2.5rem] shadow-2xl shadow-slate-900/20 flex justify-between items-center group hover:scale-[1.03] transition-all duration-300 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Saldo Akhir</p>
                    <h3 class="text-2xl font-black text-white tracking-tight">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h3>
                </div>
                <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/40 group-hover:rotate-12 transition-all duration-500">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-slate-200/60 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-slate-50/30">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-exchange-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl dark:text-white font-black text-slate-900 tracking-tight italic">Financial Records</h3>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Semua Arus Dana</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
            <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[11px] font-black tracking-[0.2em]">
                            <th class="px-8 py-6 text-center">No</th>
                            <th class="px-8 py-6">Waktu & Personil</th>
                            <th class="px-8 py-6">Deskripsi</th>
                            <th class="px-8 py-6 text-center">Tipe Arus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT transaksi.*, murid.nama FROM transaksi LEFT JOIN murid ON transaksi.id_murid = murid.id_murid ORDER BY tanggal DESC");
                        while($row = mysqli_fetch_assoc($query)):
                            $is_masuk = ($row['jenis'] == 'masuk');
                        ?>
                        <tr class="group <?= $is_masuk ? 'hover:bg-emerald-50/30' : 'hover:bg-red-50/30' ?> transition-all duration-300">
                            <td class="px-8 py-6 text-center text-slate-400 font-bold"><?= str_pad($no++, 2, "0", STR_PAD_LEFT) ?></td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-1 h-8 <?= $is_masuk ? 'bg-emerald-500' : 'bg-red-500' ?> rounded-full"></div>
                                    <div>
                                        <p class="font-black dark:text-white text-slate-900 text-sm uppercase"><?= $row['nama'] ?? 'Dana Keluar' ?></p>
                                        <p class="text-slate-400 text-[10px] font-bold"><?= date('d M Y', strtotime($row['tanggal'])) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 dark:text-white py-6 font-medium text-slate-600 text-sm italic"><?= $row['keterangan'] ?></td>
                            <td class="px-8 py-6 text-center">
                                <span class="<?= $is_masuk ? 'text-emerald-600' : 'text-red-500' ?> font-black text-sm">
                                    <?= ($is_masuk ? '+' : '-') ?> Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
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