<?php
include_once '../auth/verifica_login.php';
include_once '../menu.php';
require_once '../classes/Conexao.php';

$id_coleta = filter_input(INPUT_GET, 'id_coleta', FILTER_VALIDATE_INT);

if (!$id_coleta) {
    echo "ID da coleta não informado!";
    exit;
}

try {
    $conn = Conexao::getConexao();

    $sql = "SELECT * FROM amostra WHERE id_coleta = :id_coleta";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_coleta', $id_coleta, PDO::PARAM_INT);
    $stmt->execute();
    $amostras = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Amostras da Coleta #<?= htmlspecialchars($id_coleta) ?></title>
    <style>
        table { border-collapse: collapse; width: 100%; max-width: 700px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>

<h2>Amostras da Coleta #<?= htmlspecialchars($id_coleta) ?></h2>

<?php if (empty($amostras)): ?>
    <p>Nenhuma amostra cadastrada para esta coleta.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID Amostra</th>
                <th>pH</th>
                <th>Gordura</th>
                <th>Proteína</th>
                <th>Temperatura</th>
                <th>Contagem de Células</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($amostras as $amostra): ?>
            <tr>
                <td><?= htmlspecialchars($amostra['id_amostra']) ?></td>
                <td><?= htmlspecialchars($amostra['ph']) ?></td>
                <td><?= htmlspecialchars($amostra['gordura']) ?></td>
                <td><?= htmlspecialchars($amostra['proteina']) ?></td>
                <td><?= htmlspecialchars($amostra['temperatura']) ?></td>
                <td><?= htmlspecialchars($amostra['contagem_celulas_somaticas']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<p><a href="coletar_amostra.php?id_coleta=<?= htmlspecialchars($id_coleta) ?>">Adicionar Nova Amostra</a></p>
<p><a href="listar.php">Voltar à lista de Coletas</a></p>
</body>
</html>