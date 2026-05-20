<?php
session_start();

  // Mengambil nama file yang sedang dibuka
  $current_page = basename($_SERVER['PHP_SELF']);
  

$root = $_SERVER['DOCUMENT_ROOT'] . "/project_kas_kelas_web";
include_once $root . "/config/app.php";

if (!isset($_SESSION['login']))
    header("Location:login.php")
?>
<div id="sidebar-overlay" 
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-md z-[9998] hidden opacity-0 transition-all duration-500">
</div>

<aside id="sidebar" 
    class="fixed left-0 top-0 w-72 h-screen z-[9999] transition-transform duration-300 transform -translate-x-full flex flex-col
           bg-[#1e293b] text-white 
           dark:bg-white dark:text-slate-900 dark:border-r-4 dark:border-emerald-500 shadow-[20px_0_50px_rgba(0,0,0,0.2)]">
    
    <div class="p-6 flex items-center justify-between border-b border-slate-700/50 dark:border-slate-100 bg-slate-800/20 dark:bg-emerald-50/30">
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-tr from-emerald-600 to-emerald-400 p-2.5 rounded-xl shadow-lg shadow-emerald-500/20">
                <i class="fas fa-credit-card text-white text-sm"></i>
            </div>
            <span class="font-black text-lg tracking-tight uppercase dark:text-slate-900">
                Kas <span class="text-emerald-400">Kelas</span>
            </span>
        </div>
        <button onclick="closeSidebar()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 dark:bg-slate-100 text-gray-400 hover:text-white dark:hover:text-emerald-600 transition-all">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto custom-scrollbar">
        <?php
            $menus = [
                    ['home.php', 'fa-th-large', 'Dashboard']
                ];
                if ($_SESSION['role'] == 'bendahara') {
                    $menus[] = ['data_murid.php', 'fa-users', 'Data Murid'];
                    $menus[] = ['transaksi.php', 'fa-exchange-alt', 'transaksi'];
                }
                $menus[] = ['arus_kas.php', 'fa-poll', 'Arus Kas'];
                $menus[] = ['laporan_kas.php', 'fa-file-alt', 'Laporan Kas'];
            foreach ($menus as $menu) :
                $active = ($current_page == $menu[0]);
        ?>
        <a href="<?= $menu[0] ?>" 
           class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold transition-all duration-300 group 
           <?= $active 
               ? 'bg-emerald-500 text-white shadow-xl shadow-emerald-500/20 translate-x-1' 
               : 'text-slate-400 dark:text-slate-500 hover:bg-slate-800 dark:hover:bg-emerald-50 hover:text-white dark:hover:text-emerald-600 hover:translate-x-1' 
           ?>">
            <i class="fas <?= $menu[1] ?> text-xl transition-transform group-hover:scale-110"></i>
            <span class="tracking-wide"><?= $menu[2] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>

    <div class="p-4 mt-auto border-t border-slate-700/30 dark:border-slate-100 bg-slate-900/30 dark:bg-slate-50">
        <div class="flex items-center justify-between bg-slate-800/80 dark:bg-white rounded-[2rem] p-1.5 pl-1.5 pr-5 border border-slate-700/50 dark:border-slate-200 hover:border-emerald-500 transition-all group shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-gradient-to-tr from-white to-slate-100 dark:from-emerald-500 dark:to-emerald-400 rounded-full flex items-center justify-center text-slate-800 dark:text-white border-[3px] border-slate-700 dark:border-white shadow-xl transition-all duration-500">
                    <i class="fas fa-user text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-white dark:text-slate-900 font-black text-sm tracking-wide"><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></span>
                    <span class="text-emerald-400 text-[9px] font-bold uppercase tracking-[0.15em]"><?= $_SESSION['role']?></span>
                </div>
            </div>
            <a href="account/logout.php" onclick="return confirm('Yakin mau keluar, bro?')" 
               class="w-10 h-10 flex items-center justify-center rounded-full text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-all">
                <i class="fas fa-sign-out-alt text-lg"></i>
            </a>
        </div>
    </div>
</aside>
<style>
    .custom-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Sembunyikan untuk IE, Edge dan Firefox */
.custom-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>