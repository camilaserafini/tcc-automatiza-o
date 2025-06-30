<?php
include_once("../classes/Conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recebe e valida os dados do formulário
    $id_coleta = filter_input(INPUT_POST, 'id_coleta', FILTER_VALIDATE_INT);
    $id_caminhao = filter_input(INPUT_POST, 'id_caminhao', FILTER_VALIDATE_INT);
    $id_motorista = filter_input(INPUT_POST, 'id_motorista', FILTER_VALIDATE_INT);
    $data_hora_chegada = filter_input(INPUT_POST, 'data_hora_chegada', FILTER_SANITIZE_STRING);
    $volume = filter_input(INPUT_POST, 'volume', FILTER_VALIDATE_FLOAT);

    if (!$id_caminhao || !$id_motorista || empty($data_hora_chegada) || $volume === false) {
        echo "❌ Dados inválidos ou incompletos.";
        exit;
    }

    // Corrige o formato datetime-local para o formato aceito pelo MySQL
    $data_hora_chegada = str_replace('T', ' ', $data_hora_chegada);

    try {
        $conn = Conexao::getConexao();

        if ($id_coleta) {
            // Atualizar coleta existente
            $sql = "UPDATE coleta 
                    SET id_caminhao = :id_caminhao, id_motorista = :id_motorista, data_hora_chegada = :data_hora_chegada, volume = :volume 
                    WHERE id_coleta = :id_coleta";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_coleta', $id_coleta, PDO::PARAM_INT);
        } else {
            // Inserir nova coleta
            $sql = "INSERT INTO coleta (id_caminhao, id_motorista, data_hora_chegada, volume) 
                    VALUES (:id_caminhao, :id_motorista, :data_hora_chegada, :volume)";
            $stmt = $conn->prepare($sql);
        }

        // Bind comum para ambos casos
        $stmt->bindParam(':id_caminhao', $id_caminhao, PDO::PARAM_INT);
        $stmt->bindParam(':id_motorista', $id_motorista, PDO::PARAM_INT);
        $stmt->bindParam(':data_hora_chegada', $data_hora_chegada);
        $stmt->bindParam(':volume', $volume);

        $stmt->execute();

        header("Location: ../dashboard.php?pagina=listar_coleta");
        exit;
    } catch (PDOException $e) {
        echo "❌ Erro ao salvar: " . htmlspecialchars($e->getMessage());
        exit;
    }
} else {
    echo "Acesso inválido.";
    exit;
}