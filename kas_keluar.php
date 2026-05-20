<?php
$title = "Kas Keluar";
$subtitle = "Kas Kelas"
?>
<head>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
    <?php include "layout/sidebar.php"?>
    
    <div class="max-w-7xl mx-auto">
        <?php include "layout/header.php"?>

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
                        <input type="date" name="tanggal" required
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
                        <tr class="group hover:bg-red-50/30 transition-all duration-300">
                            <td class="px-8 py-6 text-center text-slate-400 font-bold">01</td>
                            <td class="px-8 py-6">
                                <div>
                                    <p class="font-black dark:text-white text-slate-900 text-sm uppercase tracking-tight">Pembelian ATK</p>
                                    <p class="text-slate-400 text-[10px] font-bold italic">5 Januari 2025</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="bg-red-100 text-red-600 px-4 py-1.5 rounded-xl text-xs font-black">
                                    - Rp 150.000
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-slate-500 dark:text-white text-sm font-medium">Spidol Snowman & Kertas A4</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/js/sidebar.js"></script>
</body>
<script src="assets/js/sidebar.js"></script>