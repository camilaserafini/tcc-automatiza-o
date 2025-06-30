<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php?erro=2');
    exit;
}
?>