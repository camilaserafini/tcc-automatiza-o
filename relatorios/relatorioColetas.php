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
    <title>Relatório de Coletas</title>
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

<h2>Relatório de Coletas por Período</h2>

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

    $sql = "SELECT c.id_coleta, c.data, m.nome AS motorista, ca.placa AS caminhao, 
                   c.volume, c.temperatura
            FROM coleta c
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
        echo "<h3>Resultados de <strong>$data_inicio</strong> até <strong>$data_fim</strong>:</h3>";
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Motorista</th>
                    <th>Caminhão</th>
                    <th>Volume (Litros)</th>
                    <th>Temperatura (°C)</th>
                </tr>";

        foreach ($resultados as $linha) {
            echo "<tr>
                    <td>{$linha['id_coleta']}</td>
                    <td>{$linha['data']}</td>
                    <td>{$linha['motorista']}</td>
                    <td>{$linha['caminhao']}</td>
                    <td>{$linha['volume']}</td>
                    <td>{$linha['temperatura']}</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p class='no-data'><strong>Nenhuma coleta encontrada nesse período.</strong></p>";
    }
}
?>

</body>
</html>