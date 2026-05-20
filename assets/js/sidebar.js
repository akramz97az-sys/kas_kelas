
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    // 1. Fungsi Buka
    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        // Delay sedikit agar animasi fade-in overlay jalan
        setTimeout(() => {
            overlay.classList.add('opacity-100');
        }, 10);
    }

    // 2. Fungsi Tutup
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.remove('opacity-100');
        // Tunggu animasi selesai baru sembunyikan total
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    // 3. Pasang Event Listener ke Tombol Menu
    // Pastikan tombol di header kamu punya id="menu-btn"
    document.getElementById('menu-btn').addEventListener('click', function(e) {
        e.stopPropagation();
        openSidebar();
    });

    // 4. KUNCI UTAMA: Klik di luar (area blur) langsung tutup
    overlay.addEventListener('click', function() {
        closeSidebar();
    });

    // function toggleModal(show) {
    //     const modal = document.getElementById('modalTambah');
    //     const modalBox = modal.querySelector('div');
    
    //     if (show) {
    //         modal.classList.remove('hidden');
    //         setTimeout(() => {
    //             modal.classList.add('opacity-100');
    //             modalBox.classList.remove('scale-90');
    //             modalBox.classList.add('scale-100');
    //         }, 10);
    //     } else {
    //         modal.classList.remove('opacity-100');
    //         modalBox.classList.remove('scale-100');
    //         modalBox.classList.add('scale-90');
    //         setTimeout(() => {
    //             modal.classList.add('hidden');
    //         }, 300);
    //     }
    // }
    
    // // Menutup modal jika klik di area luar box (overlay)
    // document.getElementById('modalTambah').addEventListener('click', function(e) {
    //     if (e.target === this) toggleModal(false);
    // });
    