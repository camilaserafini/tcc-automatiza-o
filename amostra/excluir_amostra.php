<?php
require_once __DIR__ . '/../classes/Conexao.php';

if (isset($_GET['id_amostra'])) {
    $id_amostra = (int)$_GET['id_amostra'];
    try {
        $conn = Conexao::getConexao();
        $stmt = $conn->prepare('DELETE FROM amostra WHERE id_amostra = :id_amostra');
        $stmt->bindParam(':id_amostra', $id_amostra, PDO::PARAM_INT);
        $stmt->execute();
        echo "<script>window.location.href = '../dashboard.php?pagina=listar_amostra&msg=amostra_excluida';</script>";
        exit;
    } catch (PDOException $e) {
        die('Erro ao excluir amostra: ' . $e->getMessage());
    }
} else {
    echo "<script>window.location.href = '../dashboard.php?pagina=listar_amostra';</script>";
    exit;
} 