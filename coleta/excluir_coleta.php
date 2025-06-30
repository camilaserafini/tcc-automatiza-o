<?php
include_once '../auth/verifica_login.php';
include_once '../classes/Conexao.php';

$id_coleta = isset($_GET['id_coleta']) ? intval($_GET['id_coleta']) : 0;

if ($id_coleta > 0) {
    try {
        $conn = Conexao::getConexao();
        // Exclui as amostras relacionadas primeiro
        $stmtAmostra = $conn->prepare('DELETE FROM amostra WHERE id_coleta = :id');
        $stmtAmostra->bindParam(':id', $id_coleta, PDO::PARAM_INT);
        $stmtAmostra->execute();

        // Agora exclui a coleta
        $stmt = $conn->prepare('DELETE FROM coleta WHERE id_coleta = :id');
        $stmt->bindParam(':id', $id_coleta, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro ao excluir: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }
}
header('Location: ../dashboard.php?pagina=listar_coleta');
exit; 