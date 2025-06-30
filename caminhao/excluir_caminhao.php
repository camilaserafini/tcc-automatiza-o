<?php
include_once __DIR__ . '/../auth/verifica_login.php';
include_once __DIR__ . '/../classes/Conexao.php';

$id_caminhao = isset($_GET['id_caminhao']) ? intval($_GET['id_caminhao']) : 0;

if ($id_caminhao > 0) {
    try {
        $conn = Conexao::getConexao();
        // Buscar todas as coletas relacionadas ao caminhão
        $stmtBusca = $conn->prepare('SELECT id_coleta FROM coleta WHERE id_caminhao = :id');
        $stmtBusca->bindParam(':id', $id_caminhao, PDO::PARAM_INT);
        $stmtBusca->execute();
        $coletas = $stmtBusca->fetchAll(PDO::FETCH_COLUMN);
        // Excluir amostras de cada coleta
        foreach ($coletas as $id_coleta) {
            $stmtAmostra = $conn->prepare('DELETE FROM amostra WHERE id_coleta = :id_coleta');
            $stmtAmostra->bindParam(':id_coleta', $id_coleta, PDO::PARAM_INT);
            $stmtAmostra->execute();
        }
        // Exclui as coletas relacionadas
        $stmtColeta = $conn->prepare('DELETE FROM coleta WHERE id_caminhao = :id');
        $stmtColeta->bindParam(':id', $id_caminhao, PDO::PARAM_INT);
        $stmtColeta->execute();
        // Agora exclui o caminhão
        $stmt = $conn->prepare('DELETE FROM caminhao WHERE id_caminhao = :id');
        $stmt->bindParam(':id', $id_caminhao, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro ao excluir: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }
}
header('Location: ../dashboard.php?pagina=listar_caminhao');
exit; 