<?php
session_start();
// Caminhos corrigidos para funcionar a partir da raiz (dashboard.php)
require_once __DIR__ . '/../auth/verifica_login.php';
require_once __DIR__ . '/../classes/Conexao.php';
require_once __DIR__ . '/../classes/Coleta.php';
require_once __DIR__ . '/../classes/Amostra.php';

// Validação dos dados recebidos
if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_POST['id_motorista']) || empty($_POST['id_caminhao']) || empty($_POST['volume'])) {
    $_SESSION['msg_simulador'] = "<div class='alert alert-danger'>Erro: Dados essenciais não foram enviados.</div>";
    header("Location: /dashboard.php?page=simulador");
    exit();
}

$pdo = Conexao::getConexao();

try {
    $pdo->beginTransaction();

    // 1. Criar a Coleta
    $coleta = new Coleta();
    $coleta->setIdMotorista($_POST['id_motorista']);
    $coleta->setIdCaminhao($_POST['id_caminhao']);
    $coleta->setVolume($_POST['volume']);
    $coleta->setDataHoraChegada(date('Y-m-d H:i:s')); 
    
    $idColeta = $coleta->salvar();

    if (!$idColeta) {
        throw new Exception("Falha ao salvar a coleta principal.");
    }

    // 2. Criar as Amostras
    if (isset($_POST['amostras']) && is_array($_POST['amostras'])) {
        foreach ($_POST['amostras'] as $numeroTanque => $dadosAmostra) {
            $amostra = new Amostra();
            $amostra->setIdColeta($idColeta);
            $amostra->setTemperatura($dadosAmostra['temperatura']);
            $amostra->setPh($dadosAmostra['ph']);
            $amostra->setGordura($dadosAmostra['gordura']);
            // O número do tanque pode ser salvo em um campo apropriado, se existir.
            // Por exemplo: $amostra->setNumeroTanque($numeroTanque);
            
            if (!$amostra->salvar()) {
                throw new Exception("Falha ao salvar uma das amostras.");
            }
        }
    } else {
        throw new Exception("Nenhum dado de amostra recebido.");
    }

    // 3. Confirmar a transação
    $pdo->commit();

    $_SESSION['success_message'] = "Coleta e suas 4 amostras salvas com sucesso!";
    header('Location: ../dashboard.php?pagina=listar_coleta');
    exit();

} catch (Exception $e) {
    // 4. Reverter em caso de erro
    $pdo->rollBack();
    $_SESSION['error_message'] = "Erro ao salvar a simulação: " . $e->getMessage();
    header('Location: ../dashboard.php?pagina=simulador'); // Volta para o simulador
    exit();
}
?>