<?php
require_once '../classes/SistemaControle.php';
session_start();

$tanque = $_POST['id_tanque'] ?? null;

if ($tanque) {
    $sistema = new SistemaControle();
    $aprovado = $sistema->analisarSensores($tanque);
    if ($aprovado) {
        $sistema->coletarAmostra($tanque);
        header('Location: ../amostra/listar_amostra.php?msg=amostra_liberada');
    } else {
        header('Location: ../amostra/listar_amostra.php?msg=parametros_reprovados');
    }
    exit;
} else {
    header('Location: ../amostra/listar_amostra.php?msg=erro_sensor');
    exit;
}
?> 