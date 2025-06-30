<?php
include_once '../auth/verifica_login.php';
include_once '../classes/Conexao.php';

$id_motorista = isset($_GET['id_motorista']) ? intval($_GET['id_motorista']) : 0;

if ($id_motorista > 0) {
    try {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare('DELETE FROM motorista WHERE id_motorista = :id');
        $stmt->bindParam(':id', $id_motorista, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro ao excluir: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }
}
header('Location: ../dashboard.php?pagina=listar_motoristas');
exit; 