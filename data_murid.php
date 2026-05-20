<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
    include_once $root . "/config/app.php";

    $query = mysqli_query($conn, "SELECT * FROM murid ORDER BY nama ASC");
    $no = 1;

    $query_murid = mysqli_query($conn, "SELECT COUNT(*) as total FROM murid");
    $total_murid = mysqli_fetch_assoc($query_murid)['total'] ?? 0;

$title = "Data Murid";
$subtitle = "Kas Kelas";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Murid | Kas Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6 font-[sans-serif] bg-gray-50 dark:bg-slate-950 transition-colors duration-300">
    <?php include "layout/sidebar.php"; ?>

    <div class="max-w-7xl mx-auto">
        <?php include "layout/header.php"; ?>

        <div class="mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
                <div class=" relative w-full md:w-1/3 group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama atau NIS..." 
                        class=" dark:bg-slate-900 w-full pl-12 pr-4 py-4 bg-white border-none rounded-[1.5rem] outline-none focus:ring-4 focus:ring-emerald-500/10 shadow-xl shadow-slate-200/50 transition-all font-medium text-slate-700">
                </div>

                <button onclick="toggleModal(true)" class="w-full md:w-auto bg-slate-900 hover:bg-emerald-600 text-white px-8 py-4 rounded-[1.5rem] font-black flex items-center justify-center gap-3 shadow-2xl shadow-slate-900/20 transition-all active:scale-95 group">
                    <div class="bg-emerald-500 p-1.5 rounded-lg group-hover:bg-white group-hover:text-emerald-600 transition-colors">
                        <i class="fas fa-plus text-xs"></i>
                    </div>
                    <span>TAMBAH MURID</span>
                </button>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-slate-200/60 overflow-hidden transition-all">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-slate-50/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                            <i class="fas fa-users-viewfinder text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl dark:text-white font-black text-slate-900 tracking-tight">Database Murid</h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.1em]">Total: <?= $total_murid ?></p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[11px] font-black tracking-[0.2em]">
                                <th class="px-8 py-6 text-center">No</th>
                                <th class="px-8 py-6">Identitas Murid</th>
                                <th class="px-8 py-6">Kelas</th>
                                <th class="px-8 py-6 text-center">Status</th>
                                <th class="px-8 py-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            if(mysqli_num_rows($query) > 0) :
                                while($row = mysqli_fetch_assoc($query)) :
                                    $inisial = strtoupper(substr($row['nama'], 0, 2));
                            ?>
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                <td class="px-8 py-6 text-center text-slate-400 font-bold text-sm italic"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-11 h-11 bg-slate-100 rounded-full flex items-center justify-center font-black text-slate-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                        <?= $inisial ?>
                                        </div>
                                        <div>
                                            <p class="font-black dark:text-white text-slate-900 uppercase text-sm tracking-tight"><?= $row['nama'] ?></p>
                                            <p class="text-slate-400 text-xs font-mono">NISN: <?= $row['nisn'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-black text-[10px] uppercase"><?= $row['kelas'] ?></span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                <?php if($row['status'] == 'aktif'): ?>
                                    <span class="bg-emerald-50 text-emerald-500 border border-emerald-100 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-tighter">Aktif</span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-500 border border-red-100 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-tighter">Non-Aktif</span>
                                <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                                class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button onclick="confirmDelete(<?= $row['id_murid'] ?>, '<?= $row['nama'] ?>')" 
                                                class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="p-10 text-center text-slate-400 font-bold">Belum ada daftar data murid</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[10000] hidden flex items-center justify-center opacity-0 transition-all duration-300">
        <div class="bg-white dark:bg-slate-900 w-[90%] max-w-md rounded-[2.5rem] shadow-2xl p-10 transform scale-90 transition-all duration-300 border border-white/20">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="text-2xl dark:text-white font-black text-slate-900 tracking-tight uppercase">Entry Murid</h2>
                </div>
                <button onclick="toggleModal(false)" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="proses/tambah_murid.php" method="POST" class="space-y-6">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Induk Siswa</label>
                    <input type="text" name="nisn" required placeholder="001234..."
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Siswa</label>
                    <input type="text" name="nama" required placeholder="Contoh: Akram Ziyad"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Rombongan Belajar</label>
                    <input type="text" name="kelas" required placeholder="XI PPLG 1"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Awal</label>
                    <select name="status" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>
                <button type="submit" 
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-5 rounded-[1.5rem] mt-4 shadow-xl shadow-emerald-500/30 transition-all active:scale-95 text-sm uppercase tracking-widest">
                    Simpan Database
                </button>
            </form>
        </div>
    </div>
    <div id="modalEdit" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[10000] hidden flex items-center justify-center opacity-0 transition-all duration-300">
        <div class="bg-white dark:bg-slate-900 w-[90%] max-w-md rounded-[2.5rem] shadow-2xl p-10 transform scale-90 transition-all duration-300">
            <h2 class="text-2xl dark:text-white font-black text-slate-900 mb-6 uppercase">Edit Data Murid</h2>
            
            <form action="proses/edit_murid.php" method="POST" class="space-y-6">
                <input type="hidden" name="id_murid" id="edit_id">

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">NISN</label>
                    <input type="text" name="nisn" id="edit_nisn" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 outline-none font-bold">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit_nama" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 outline-none font-bold">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Kelas</label>
                    <input type="text" name="kelas" id="edit_kelas" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 outline-none font-bold">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Status</label>
                    <select name="status" id="edit_status" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 outline-none font-bold">
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-black py-5 rounded-[1.5rem] shadow-xl">
                    UPDATE DATA
                </button>
                <button type="button" onclick="closeEditModal()" class="w-full text-slate-400 font-bold text-sm">Batal</button>
            </form>
        </div>
    </div>
    <?php if(isset($_GET['pesan'])): ?>
        <div id="alert-notif" class="mb-6 p-4 rounded-2xl font-bold text-sm animate-bounce 
            <?= $_GET['pesan'] == 'hapus_sukses' ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' ?>">
            <?php 
                if($_GET['pesan'] == 'hapus_sukses') echo "🗑️ Data murid telah dihapus permanen.";
                if($_GET['pesan'] == 'edit_sukses') echo "✨ Data murid berhasil diperbarui.";
                if($_GET['pesan'] == 'tambah_sukses') echo "✅ Murid baru berhasil terdaftar.";
            ?>
        </div>
    <?php endif; ?>
    <script>
        function toggleModal(show) {
            const modal = document.getElementById('modalTambah');
            const content = modal.querySelector('div');
            
            if (show) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('flex', 'opacity-100');
                    content.classList.add('scale-100');
                }, 10);
            } else {
                modal.classList.remove('opacity-100');
                content.classList.remove('scale-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }
    </script>
</body>
<script src="assets/js/sidebar.js"></script>
<script>
    function confirmDelete(id, nama) {
    if (confirm("Apakah Anda yakin ingin menghapus murid: " + nama + "?")) {
        window.location.href = "proses/hapus_murid.php?id=" + id;
    }
}
function openEditModal(data) {
    const modal = document.getElementById('modalEdit');
    // Isi field input dengan data dari baris tabel
    document.getElementById('edit_id').value = data.id_murid;
    document.getElementById('edit_nisn').value = data.nisn;
    document.getElementById('edit_nama').value = data.nama;
    document.getElementById('edit_kelas').value = data.kelas;
    document.getElementById('edit_status').value = data.status;

    modal.classList.remove('hidden');
    setTimeout(() => { modal.classList.add('flex', 'opacity-100'); modal.querySelector('div').classList.add('scale-100'); }, 10);
}

function closeEditModal() {
    const modal = document.getElementById('modalEdit');
    modal.classList.add('hidden');
}
const alertNotif = document.getElementById('alert-notif');

    if (alertNotif) {
        // Tunggu 3 detik (3000ms), lalu jalankan fungsi hilang
        setTimeout(() => {
            // Beri efek transparan dan geser sedikit ke atas
            alertNotif.style.opacity = '0';
            alertNotif.style.transform = 'translateY(-10px)';
            
            // Setelah animasi selesai (500ms), hapus elemen dari layar
            setTimeout(() => {
                alertNotif.style.display = 'none';
                
                // Opsional: Bersihkan URL dari parameter ?pesan agar saat refresh notif tidak muncul lagi
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 500);
        }, 3000);
}
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        // Kita ambil teks dari kolom Identitas (Nama & NISN)
        let text = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        if (text.includes(filter)) {
            row.style.display = ''; // Tampilkan
        } else {
            row.style.display = 'none'; // Sembunyikan
        }
    });
});
</script>
</html>