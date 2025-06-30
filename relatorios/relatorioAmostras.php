<?php
include_once '../auth/configurarLogin.php';
include_once '../menu.php';
include_once '../classes/Conexao.php';

$conn = Conexao::getConexao();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Amostras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .no-data {
            color: red;
        }
    </style>
</head>
<body>

<h2>Relatório de Amostras por Período</h2>

<form method="GET" action="">
    <label>Data Início:</label>
    <input type="date" name="data_inicio" required>
    
    <label style="margin-left: 20px;">Data Fim:</label>
    <input type="date" name="data_fim" required>

    <input type="submit" value="Gerar Relatório" style="margin-left: 20px;">
</form>

<?php
if (isset($_GET['data_inicio']) && isset($_GET['data_fim'])) {
    $data_inicio = $_GET['data_inicio'];
    $data_fim = $_GET['data_fim'];

    $sql = "SELECT a.id_amostra, a.volume, a.temperatura, a.status, a.motivo_recusa, c.data AS data_coleta, 
                   m.nome AS motorista, ca.placa AS caminhao
            FROM amostra a
            INNER JOIN coleta c ON a.id_coleta = c.id_coleta
            INNER JOIN motorista m ON c.id_motorista = m.id_motorista
            INNER JOIN caminhao ca ON c.id_caminhao = ca.id_caminhao
            WHERE c.data BETWEEN :data_inicio AND :data_fim
            ORDER BY c.data ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':data_inicio', $data_inicio);
    $stmt->bindParam(':data_fim', $data_fim);
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
        $aprovadas = array_filter($resultados, function($a) { return $a['status'] == 'Aprovado'; });
        $reprovadas = array_filter($resultados, function($a) { return $a['status'] == 'Recusado' || $a['status'] == 'Reprovado'; });
        $coletas = [];
        foreach ($resultados as $linha) {
            $coletas[$linha['caminhao']][] = $linha;
        }
        foreach ($coletas as $placa => $amostras) {
            $liberado = true;
            foreach ($amostras as $a) {
                if ($a['status'] != 'Aprovado') $liberado = false;
            }
            echo "<h3>Caminhão: <strong>$placa</strong> - ";
            echo $liberado ? '<span style=\'color:green\'>Liberado para descarga</span>' : '<span style=\'color:red\'>Bloqueado</span>';
            echo "</h3>";
            echo "<table><tr><th>ID Amostra</th><th>Data da Coleta</th><th>Motorista</th><th>pH</th><th>Gordura (%)</th><th>Proteína (%)</th><th>Temperatura (°C)</th><th>Cont. Células</th><th>Status</th><th>Motivo da Recusa</th></tr>";
            foreach ($amostras as $linha) {
                $cor = $linha['status'] == 'Aprovado' ? '#d4edda' : ($linha['status'] == 'Recusado' || $linha['status'] == 'Reprovado' ? '#f8d7da' : '#fff3cd');
                $badge = $linha['status'] == 'Aprovado' ? 'bg-success' : (($linha['status'] == 'Recusado' || $linha['status'] == 'Reprovado') ? 'bg-danger' : 'bg-info');
                $status = $linha['status'] == 'Aprovado' ? 'Aprovado' : (($linha['status'] == 'Recusado' || $linha['status'] == 'Reprovado') ? 'Reprovado' : 'Em andamento');
                echo "<tr style='background:$cor;'>\n<td>{$linha['id_amostra']}</td>\n<td>{$linha['data_coleta']}</td>\n<td>{$linha['motorista']}</td>\n<td>{$linha['ph']}</td>\n<td>{$linha['gordura']}</td>\n<td>{$linha['proteina']}</td>\n<td>{$linha['temperatura']}</td>\n<td>{$linha['contagem_celulas_somaticas']}</td>\n<td><span class='badge $badge'>$status</span></td>\n<td>" . (!empty($linha['motivo_recusa']) ? $linha['motivo_recusa'] : '-') . "</td>\n</tr>";
            }
            echo "</table><br>";
        }
    } else {
        echo "<p class='no-data'><strong>Nenhuma amostra encontrada nesse período.</strong></p>";
    }
}
?>

</body>
</html>