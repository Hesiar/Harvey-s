<?php
    session_start();

    if (isset($_COOKIE['usuario_id'])) {
        setcookie("usuario_id", "", time() - 3600, "/");
    }

    $_SESSION = [];
    session_destroy();

    header("Location: maqueta.php");
    exit;
?>
