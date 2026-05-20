<?php
$title = "Kas Masuk";
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
            <div class="bg-white dark:bg-white p-8 flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl dark:text-slate-900 font-black text-black uppercase tracking-tight">Input Kas Masuk</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Catat setoran murid baru</p>
                </div>
            </div>

            <form action="proses/proses_kas_masuk.php" method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Murid</label>
                    <div class="relative group">
                        <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                        <select class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                            <option value="" selected disabled hidden>-- Pilih Nama Murid --</option>
                            <option value="1">Akram Ziyad</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Transaksi</label>
                    <div class="relative group">
                        <i class="fas fa-calendar-alt absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                        <input type="date" name="tanggal" required
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

                <div class="space-y-2 md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" placeholder="Contoh: Lunas bulan Januari"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700 resize-none" rows="2"></textarea>
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
                            <th class="px-8 py-6 text-center">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr class="group hover:bg-slate-50 transition-all duration-300">
                            <td class="px-8 py-6 text-center text-slate-400 font-bold">01</td>
                            <td class="px-8 py-6">
                                <div>
                                    <p class="font-black dark:text-white text-slate-900 text-sm uppercase italic">Akram Ziyad</p>
                                    <p class="text-slate-400 text-[10px] font-bold">5 Januari 2025</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter">Januari</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-emerald-600 font-black text-sm">Rp 50.000</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                                    <div class="w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center text-[8px] text-white">F</div>
                                    <span class="text-slate-700 font-bold text-xs italic">Fynn</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/js/sidebar.js"></script>
</body>
