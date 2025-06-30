<?php
require_once "../classes/Conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_amostra = isset($_POST['id_amostra']) ? filter_var($_POST['id_amostra'], FILTER_VALIDATE_INT) : null;
    $id_coleta = filter_input(INPUT_POST, 'id_coleta', FILTER_VALIDATE_INT);
    $ph = filter_input(INPUT_POST, 'ph', FILTER_VALIDATE_FLOAT);
    $gordura = filter_input(INPUT_POST, 'gordura', FILTER_VALIDATE_FLOAT);
    $proteina = filter_input(INPUT_POST, 'proteina', FILTER_VALIDATE_FLOAT);
    $temperatura = filter_input(INPUT_POST, 'temperatura', FILTER_VALIDATE_FLOAT);
    $contagem_celulas = filter_input(INPUT_POST, 'contagem_celulas_somaticas', FILTER_VALIDATE_INT);

    // Basic validation
    if (!$id_coleta || $ph === false || $gordura === false || $proteina === false || $temperatura === false || $contagem_celulas === false) {
        die("❌ Dados inválidos. Por favor, preencha corretamente.");
    }

    try {
        $conn = Conexao::getConexao();

        $motivo_recusa = [];
        $status = 'Aprovado';
        if ($gordura > 4.0) {
            $status = 'Reprovado';
            $motivo_recusa[] = 'Gordura acima do limite permitido';
        }
        if ($ph < 6.6 || $ph > 6.9) {
            $status = 'Reprovado';
            $motivo_recusa[] = 'pH fora do padrão (6.6 - 6.9)';
        }
        if ($temperatura > 7.0) {
            $status = 'Reprovado';
            $motivo_recusa[] = 'Temperatura acima do limite permitido';
        }
        if ($proteina < 2.8) {
            $status = 'Reprovado';
            $motivo_recusa[] = 'Proteína abaixo do mínimo permitido (2.8)';
        }
        if ($contagem_celulas > 500000) {
            $status = 'Reprovado';
            $motivo_recusa[] = 'Contagem de células acima do limite (500.000)';
        }
        $motivo_recusa_str = $status == 'Reprovado' ? implode('; ', $motivo_recusa) : null;

        if ($id_amostra) {
            // Update existing sample
            $sql = "UPDATE amostra 
                    SET id_coleta = :id_coleta, ph = :ph, gordura = :gordura, proteina = :proteina, temperatura = :temperatura, contagem_celulas_somaticas = :contagem_celulas_somaticas, status = :status, motivo_recusa = :motivo_recusa 
                    WHERE id_amostra = :id_amostra";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_amostra', $id_amostra, PDO::PARAM_INT);
        } else {
            // Insert new sample
            $sql = "INSERT INTO amostra (id_coleta, ph, gordura, proteina, temperatura, contagem_celulas_somaticas, status, motivo_recusa) 
                    VALUES (:id_coleta, :ph, :gordura, :proteina, :temperatura, :contagem_celulas_somaticas, :status, :motivo_recusa)";
            $stmt = $conn->prepare($sql);
        }

        // Bind parameters
        $stmt->bindParam(':id_coleta', $id_coleta, PDO::PARAM_INT);
        $stmt->bindParam(':ph', $ph);
        $stmt->bindParam(':gordura', $gordura);
        $stmt->bindParam(':proteina', $proteina);
        $stmt->bindParam(':temperatura', $temperatura);
        $stmt->bindParam(':contagem_celulas_somaticas', $contagem_celulas, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':motivo_recusa', $motivo_recusa_str);

        $stmt->execute();

        header("Location: ../dashboard.php?pagina=listar_amostra");
        exit;
    } catch (PDOException $e) {
        die("❌ Erro ao salvar amostra: " . $e->getMessage());
    }
} else {
    die("Acesso inválido.");
}