<?php
session_start();

// 1. Hapus semua variabel session
$_SESSION = [];

// 2. Hapus cookie sesi (opsional tapi disarankan untuk keamanan penuh)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan sesi
session_destroy();

// 4. Redirect ke halaman login
header("Location: login.php");
exit;
?>