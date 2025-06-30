<?php
require_once __DIR__ . '/../classes/config.php';
require_once __DIR__ . '/../classes/Conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_coleta = filter_input(INPUT_POST, 'id_coleta', FILTER_VALIDATE_INT);
    $ph = filter_input(INPUT_POST, 'ph', FILTER_VALIDATE_FLOAT);
    $gordura = filter_input(INPUT_POST, 'gordura', FILTER_VALIDATE_FLOAT);
    $proteina = filter_input(INPUT_POST, 'proteina', FILTER_VALIDATE_FLOAT);
    $temperatura = filter_input(INPUT_POST, 'temperatura', FILTER_VALIDATE_FLOAT);
    $contagem_celulas = filter_input(INPUT_POST, 'contagem_celulas', FILTER_VALIDATE_INT);

    if (!$id_coleta || $ph === false || $gordura === false || $proteina === false || $temperatura === false || $contagem_celulas === false) {
        die('Dados inválidos. Por favor, preencha corretamente.');
    }

    try {
        $pdo = Conexao::getConexao();

        $sql = "INSERT INTO amostra (id_coleta, ph, gordura, proteina, temperatura, contagem_celulas_somaticas)
                VALUES (:id_coleta, :ph, :gordura, :proteina, :temperatura, :contagem_celulas_somaticas)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_coleta', $id_coleta, PDO::PARAM_INT);
        $stmt->bindParam(':ph', $ph);
        $stmt->bindParam(':gordura', $gordura);
        $stmt->bindParam(':proteina', $proteina);
        $stmt->bindParam(':temperatura', $temperatura);
        $stmt->bindParam(':contagem_celulas_somaticas', $contagem_celulas, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../dashboard.php?pagina=listar_amostra");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar amostra: " . $e->getMessage());
    }
} else {
    die('Método inválido.');
}