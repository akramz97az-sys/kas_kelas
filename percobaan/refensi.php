<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Kas Mingguan - Fleksibel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] dark:bg-slate-950 transition-colors duration-300">

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-gray-100 shadow-2xl overflow-hidden">
            
            <div class="p-8 flex items-center gap-4 border-b border-gray-50 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-calculator text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Input Kas Mingguan</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Nominal otomatis 10k, tapi bisa diedit manual (Misal: 9k)</p>
                </div>
            </div>

            <form action="proses/proses_kas_mingguan.php" method="POST" class="p-8 space-y-6">
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Murid</label>
                    <select name="id_murid" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 font-bold text-slate-700 dark:text-slate-200 outline-none border border-transparent focus:border-emerald-500 focus:bg-white transition-all">
                        <option value="">-- Pilih Nama Murid --</option>
                        <option value="5">Adha Putra P (XI PPLG1)</option>
                        <option value="6">Akram Ziyad (XI PPLG1)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Periode Bulan</label>
                    <select name="bulan" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 font-bold text-slate-700 dark:text-slate-200 outline-none border border-transparent focus:border-emerald-500 focus:bg-white transition-all">
                        <option value="Mei">Mei 2026</option>
                        <option value="Juni">Juni 2026</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">Pilih Minggu & Atur Nominal Bayar</label>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="minggu[]" value="1" id="m1" onchange="hitungTotal()" class="w-5 h-5 rounded text-emerald-500 focus:ring-emerald-400 border-gray-300 cursor-pointer">
                                <label for="m1" class="font-black text-sm text-slate-800 dark:text-slate-200 cursor-pointer">Minggu 1 (M1)</label>
                            </div>
                            <div class="flex items-center gap-1 bg-white dark:bg-slate-900 border dark:border-slate-700 px-3 py-1.5 rounded-xl w-32 shadow-sm">
                                <span class="text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" name="nominal_m1" id="nominal_m1" min="1" max="10000" value="10000" oninput="hitungTotal()" class="w-full text-xs font-black text-slate-700 dark:text-slate-200 bg-transparent outline-none text-right">
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="minggu[]" value="2" id="m2" onchange="hitungTotal()" class="w-5 h-5 rounded text-emerald-500 focus:ring-emerald-400 border-gray-300 cursor-pointer">
                                <label for="m2" class="font-black text-sm text-slate-800 dark:text-slate-200 cursor-pointer">Minggu 2 (M2)</label>
                            </div>
                            <div class="flex items-center gap-1 bg-white dark:bg-slate-900 border dark:border-slate-700 px-3 py-1.5 rounded-xl w-32 shadow-sm">
                                <span class="text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" name="nominal_m2" id="nominal_m2" value="10000" oninput="hitungTotal()" class="w-full text-xs font-black text-slate-700 dark:text-slate-200 bg-transparent outline-none text-right">
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="minggu[]" value="3" id="m3" onchange="hitungTotal()" class="w-5 h-5 rounded text-emerald-500 focus:ring-emerald-400 border-gray-300 cursor-pointer">
                                <label for="m3" class="font-black text-sm text-slate-800 dark:text-slate-200 cursor-pointer">Minggu 3 (M3)</label>
                            </div>
                            <div class="flex items-center gap-1 bg-white dark:bg-slate-900 border dark:border-slate-700 px-3 py-1.5 rounded-xl w-32 shadow-sm">
                                <span class="text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" name="nominal_m3" id="nominal_m3" value="10000" oninput="hitungTotal()" class="w-full text-xs font-black text-slate-700 dark:text-slate-200 bg-transparent outline-none text-right">
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="minggu[]" value="4" id="m4" onchange="hitungTotal()" class="w-5 h-5 rounded text-emerald-500 focus:ring-emerald-400 border-gray-300 cursor-pointer">
                                <label for="m4" class="font-black text-sm text-slate-800 dark:text-slate-200 cursor-pointer">Minggu 4 (M4)</label>
                            </div>
                            <div class="flex items-center gap-1 bg-white dark:bg-slate-900 border dark:border-slate-700 px-3 py-1.5 rounded-xl w-32 shadow-sm">
                                <span class="text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" name="nominal_m4" id="nominal_m4" value="10000" oninput="hitungTotal()" class="w-full text-xs font-black text-slate-700 dark:text-slate-200 bg-transparent outline-none text-right">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="p-6 bg-slate-900 text-white rounded-2xl flex justify-between items-center shadow-inner">
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Total Pembayaran</p>
                        <h3 class="text-2xl font-black text-emerald-400 mt-1" id="display-total">Rp 0</h3>
                    </div>
                    <div class="text-right">
                        <span id="display-minggu-hitung" class="text-xs font-mono font-bold bg-white/10 px-3 py-1.5 rounded-xl">0 Minggu Terpilih</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-500/30 transition-all text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </form>

        </div>
    </div>

    <script>
    // Fungsi baru untuk memvalidasi ketikan bendahara secara realtime
    function validasiDanHitung(inputElement) {
        let nilai = parseInt(inputElement.value);

        // Jika bendahara menginput di atas 10.000, paksa balikkan ke 10.000
        if (nilai > 10000) {
            inputElement.value = 10000;
        }
        
        // Jika bendahara menginput angka minus atau 0, kosongkan atau biarkan mengetik ulang (min 1)
        if (nilai < 0) {
            inputElement.value = 1;
        }

        // Jalankan fungsi hitung total setelah diproteksi
        hitungTotal();
    }

    // Fungsi kalkulator total kas terkumpul
    function hitungTotal() {
        let total = 0;
        let jumlahMinggu = 0;

        for (let i = 1; i <= 4; i++) {
            const checkbox = document.getElementById(`m${i}`);
            const inputNominal = document.getElementById(`nominal_m${i}`);

            let nominalUang = parseInt(inputNominal.value) || 0;

            if (checkbox.checked) {
                total += nominalUang;
                jumlahMinggu++;
                
                checkbox.parentElement.parentElement.classList.add('border-emerald-500', 'bg-emerald-50/20');
            } else {
                checkbox.parentElement.parentElement.classList.remove('border-emerald-500', 'bg-emerald-50/20');
            }
        }

        // Tampilkan hasil format rupiah ke UI
        document.getElementById('display-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('display-minggu-hitung').innerText = `${jumlahMinggu} Minggu Terpilih`;
    }
</script>
</body>
</html>