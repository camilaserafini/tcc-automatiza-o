<?php
require_once '../classes/Conexao.php';

if (isset($_GET['id_amostra'])) {
    $id_amostra = (int)$_GET['id_amostra'];
    try {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare('UPDATE amostra SET status = "Finalizada", status_finalizacao = "Finalizada", data_hora_finalizacao = NOW() WHERE id_amostra = :id_amostra');
        $stmt->bindParam(':id_amostra', $id_amostra, PDO::PARAM_INT);
        $stmt->execute();
        echo "<script>window.location.href = '../dashboard.php?pagina=listar_amostra&msg=amostra_finalizada';</script>";
        exit;
    } catch (PDOException $e) {
        die('Erro ao finalizar amostra: ' . $e->getMessage());
    }
} else {
    echo "<script>window.location.href = '../dashboard.php?pagina=listar_amostra';</script>";
    exit;
} 