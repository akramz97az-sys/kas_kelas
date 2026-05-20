<header class="sticky top-0 z-[9990] bg-white/70 dark:bg-slate-950/80 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-800 px-6 py-4 mb-8 transition-all duration-300">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-5">
            <div id="menu-btn" class="bg-slate-900 w-11 h-11 flex items-center justify-center rounded-xl text-white cursor-pointer hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 active:scale-90">
                <i class="fas fa-bars text-sm"></i>
            </div>
            
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-1">
                    <?php echo isset($title) ? $title : "Default Title"; ?>
                </h1>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                        <a href="home.php"><?php echo isset($subtitle) ? $subtitle : "Selamat Datang"; ?></a>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="flex gap-3">
            <button id="dark-mode-toggle" class="relative z-[9999] w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-emerald-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 border border-transparent dark:border-slate-700">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:block"></i>
            </button>
            <div class="w-[1px] h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>

            <div class="relative group">
            <select onchange="location = this.value;" class="appearance-none bg-slate-100 text-slate-700 pl-4 pr-8 py-2.5 rounded-xl border border-transparent focus:border-emerald-500 outline-none font-bold text-sm cursor-pointer">
                <?php
                $y_now = date('Y');
                for($y = $y_now; $y >= $y_now-1; $y--):
                    $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $y) ? 'selected' : '';
                    echo "<option value='?tahun=$y&bulan=".(isset($_GET['bulan']) ? $_GET['bulan'] : date('m'))."' $selected>$y</option>";
                endfor;
                ?>
            </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none group-hover:text-emerald-500 transition-colors"></i>
            </div>

            <div class="relative group">
            <select onchange="location = this.value;" class="appearance-none bg-emerald-500 text-white pl-5 pr-10 py-2.5 rounded-xl border border-emerald-400 shadow-lg shadow-emerald-500/20 outline-none font-black text-sm cursor-pointer hover:bg-emerald-600">
                <?php
                $months = [
                    '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni',
                    '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
                ];
                foreach($months as $m_num => $m_name):
                    $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $m_num) ? 'selected' : ( (!isset($_GET['bulan']) && $m_num == date('m')) ? 'selected' : '' );
                    echo "<option value='?bulan=$m_num&tahun=".(isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'))."' $selected>$m_name</option>";
                endforeach;
                ?>
            </select>
                <i class="fas fa-calendar-alt absolute right-4 top-1/2 -translate-y-1/2 text-emerald-200 pointer-events-none group-hover:text-white transition-colors"></i>
            </div>
        </div>
    </div>
</header>
<script>
  
  document.addEventListener('DOMContentLoaded', () => {
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const html = document.documentElement;
        tailwind.config = {
        darkMode: 'class', // Ini kuncinya!
        }

        if (!darkModeToggle) {
            console.error("Tombol dark mode tidak ditemukan!");
            return;
        }

        // Fungsi untuk update tampilan
        const applyTheme = (theme) => {
            if (theme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        };

        // Cek Local Storage atau System Preference
        const savedTheme = localStorage.getItem('theme');
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
            applyTheme('dark');
        }

        // Event Click
        darkModeToggle.addEventListener('click', (e) => {
            e.preventDefault(); // Mencegah form submit kalau tombol di dalam form
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            console.log("Theme switched to:", isDark ? 'dark' : 'light');
        });
    });
</script>