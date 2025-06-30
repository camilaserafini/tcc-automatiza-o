<?php
require_once '../classes/SistemaControle.php';
session_start();

$tanque = $_POST['id_tanque'] ?? null;
$amostra = $_POST['id_amostra'] ?? null;

if ($tanque) {
    $sistema = new SistemaControle();
    $id_nova_amostra = $sistema->coletarAmostra($tanque);
    if (!$id_nova_amostra) {
        // Tenta criar uma coleta automaticamente
        require_once '../classes/Conexao.php';
        $conn = Conexao::getConexao();
        // Exemplo: criar coleta com motorista e caminhão padrão (ajuste conforme necessário)
        $id_motorista = 1; // ID padrão ou obtenha de outra forma
        $id_caminhao = $tanque;
        $stmt = $conn->prepare('INSERT INTO coleta (id_motorista, id_caminhao, data_hora_chegada) VALUES (:id_motorista, :id_caminhao, NOW())');
        $stmt->bindParam(':id_motorista', $id_motorista, PDO::PARAM_INT);
        $stmt->bindParam(':id_caminhao', $id_caminhao, PDO::PARAM_INT);
        $stmt->execute();
        // Tenta coletar novamente
        $id_nova_amostra = $sistema->coletarAmostra($tanque);
    }
    if ($id_nova_amostra) {
        header('Location: ../amostra/listar_amostra.php?nova_amostra=' . $id_nova_amostra);
        exit;
    } else {
        header('Location: ../amostra/listar_amostra.php?msg=Não foi possível coletar a amostra. Verifique se o caminhão e motorista existem.');
        exit;
    }
} else {
    header('Location: ../amostra/listar_amostra.php?msg=erro_coleta');
    exit;
}
?> 