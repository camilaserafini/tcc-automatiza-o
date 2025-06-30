<?php
require_once '../classes/Conexao.php';

if (isset($_GET['id_coleta'])) {
    $id_coleta = (int)$_GET['id_coleta'];
    try {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare('UPDATE coleta SET status_finalizacao = "Finalizada" WHERE id_coleta = :id_coleta');
        $stmt->bindParam(':id_coleta', $id_coleta, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: ../dashboard.php?pagina=listar_coleta&msg=coleta_finalizada');
        exit;
    } catch (PDOException $e) {
        die('Erro ao finalizar coleta: ' . $e->getMessage());
    }
} else {
    header('Location: ../dashboard.php?pagina=listar_coleta');
    exit;
} 