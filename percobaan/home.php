<?php 
  $root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
  include_once $root . "/config/app.php";

  // 1. Hitung Total Murid
  $query_murid = mysqli_query($conn, "SELECT COUNT(*) as total FROM murid");
  $total_murid = mysqli_fetch_assoc($query_murid)['total'];

  // 2. Hitung Total Kas Masuk
  $query_masuk = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='masuk'");
  $kas_masuk = mysqli_fetch_assoc($query_masuk)['total'] ?? 0;

  // 3. Hitung Total Kas Keluar
  $query_keluar = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE jenis='keluar'");
  $kas_keluar = mysqli_fetch_assoc($query_keluar)['total'] ?? 0;

  // 4. Hitung Saldo Akhir
  $saldo_akhir = $kas_masuk - $kas_keluar;

  // 5. Ambil Daftar Murid yang BELUM BAYAR (Contoh logika: murid yang tidak ada di tabel transaksi bulan ini)
  // Untuk sementara kita ambil semua murid yang statusnya 'aktif'
  $daftar_tunggu = mysqli_query($conn, "SELECT * FROM murid WHERE status='aktif' LIMIT 5");

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
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] dark:bg-slate-950 transition-colors duration-300">
    <?php include "../layout/sidebar.php"; ?>
    
    <div class="max-w-7xl mx-auto">
        <?php include "../layout/header.php"; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Total Murid</p>
                    <h2 class="text-4xl dark:text-white font-black text-slate-900 mt-2"><?= $total_murid ?></h2>
                    <div class="flex items-center gap-1 mt-2 text-emerald-500 text-xs font-bold">
                        <i class="fas fa-check-circle"></i>
                        <span>Database Aktif</span>
                    </div>
                </div>
                <div class="bg-emerald-50 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Total Masuk</p>
                    <h2 class="text-2xl dark:text-white font-black text-slate-900 mt-2">Rp <?= number_format($kas_masuk, 0, ',', '.') ?></h2>
                    <p class="text-emerald-500 text-[10px] font-bold mt-2 italic">Akumulasi</p>
                </div>
                <div class="bg-emerald-100 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-arrow-down text-xl"></i>
                </div>
            </div>

            <div class="bg-red-50/50 dark:bg-slate-900 p-6 rounded-[2rem] border border-red-100 shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-red-500 font-bold text-xs uppercase tracking-widest">Kas Keluar</p>
                    <h2 class="text-2xl dark:text-white font-black text-slate-900 mt-2">Rp <?= number_format($kas_keluar, 0, ',', '.') ?></h2>
                    <p class="text-red-400 text-[10px] font-bold mt-2">Biaya Operasional</p>
                </div>
                <div class="bg-red-500 w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-500/40">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl flex justify-between items-start group hover:scale-[1.03] transition-all duration-300">
                <div>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Saldo Kas</p>
                    <h2 class="text-2xl font-black text-white mt-2 leading-tight">Rp <?= number_format($saldo_akhir / 1000, 1) ?>k</h2>
                    <p class="text-emerald-400 text-[10px] font-bold mt-2">Saldo Saat Ini</p>
                </div>
                <div class="bg-white/10 w-12 h-12 rounded-2xl flex items-center justify-center text-white backdrop-blur-md">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-slate-50/30">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Daftar Murid</h3>
                    <p class="text-slate-400 font-medium text-sm mt-1">Manajemen status pembayaran murid</p>
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
                        while($row = mysqli_fetch_assoc($daftar_tunggu)) : 
                            // Ambil inisial nama
                            $inisial = strtoupper(substr($row['nama'], 0, 2));
                        ?>
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="px-8 py-6 text-slate-400 font-bold"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center font-black text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-colors">
                                        <?= $inisial ?>
                                    </div>
                                    <div>
                                        <p class="font-black dark:text-white text-slate-900 uppercase text-sm tracking-tight"><?= $row['nama'] ?></p>
                                        <p class="text-slate-400 text-xs font-mono">ID: <?= $row['id_murid'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 font-bold text-slate-600 text-sm dark:text-slate-300"><?= $row['kelas'] ?></td>
                            <td class="px-8 py-6 text-center">
                                <span class="bg-emerald-50 text-emerald-500 border border-emerald-100 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <a href="kas_masuk.php?id=<?= $row['id_murid'] ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl inline-flex items-center gap-2 transition-all shadow-lg shadow-emerald-500/20 active:scale-95 group">
                                    <i class="fas fa-plus-circle"></i>
                                    <span class="font-black text-xs uppercase">Bayar Kas</span>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>