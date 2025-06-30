<?php
include_once '../auth/configurarLogin.php';
require_once '../classes/Motorista.php';

$motorista = new Motorista();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cnh = $_POST['cnh'];
    $nome_motorista = $_POST['nome_motorista'] ?? '';
    $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : null;

    $motorista->setNome($nome);
    $motorista->setCnh($cnh);

    if (isset($_GET['id'])) {
        // editar
        $motorista->setId($_GET['id']);
        $motorista->atualizar();
    } else {
        // inserir
        $motorista->inserir();
    }

    header('Location: listar_motoristas.php');
    exit;
} else {
    header('Location: listar_motoristas.php');
    exit;
}