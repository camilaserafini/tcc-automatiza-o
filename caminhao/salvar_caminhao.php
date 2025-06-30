<?php
include_once("../classes/Conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = trim($_POST['placa']);
    $id_caminhao = isset($_POST['id_caminhao']) ? intval($_POST['id_caminhao']) : null;

    if (empty($placa)) {
        die("Placa é obrigatória.");
    }

    try {
        $conn = Conexao::getConexao();

        if ($id_caminhao) {
            // Atualizar caminhão
            $sql = "UPDATE caminhao SET placa = :placa WHERE id_caminhao = :id_caminhao";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':placa', $placa);
            $stmt->bindParam(':id_caminhao', $id_caminhao, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Inserir caminhão novo
            $sql = "INSERT INTO caminhao (placa) VALUES (:placa)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':placa', $placa);
            $stmt->execute();
        }

        // Redirecionar para a lista após salvar
        header("Location: listar_caminhao.php");
        exit;

    } catch (PDOException $e) {
        die("Erro ao salvar caminhão: " . $e->getMessage());
    }

} else {
    die("Acesso inválido.");
}
?>