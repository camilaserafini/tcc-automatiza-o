<?php
require_once '../classes/ValvulaAmostragem.php';

if (isset($_POST['id_tanque'], $_POST['acao'])) {
    $id_tanque = (int)$_POST['id_tanque'];
    $acao = $_POST['acao'] === 'Abrir' ? 'Aberta' : 'Fechada';
    ValvulaAmostragem::setStatus($id_tanque, $acao);
    header('Location: simulador_sensor.php?id_tanque=' . $id_tanque . '&msg=valvula_' . strtolower($acao));
    exit;
}
header('Location: simulador_sensor.php');
exit; 