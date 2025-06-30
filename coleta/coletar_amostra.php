<?php
require_once __DIR__ . '/../classes/config.php';
require_once __DIR__ . '/../classes/Conexao.php';

$id_coleta = filter_input(INPUT_GET, 'id_coleta', FILTER_VALIDATE_INT);

if (!$id_coleta) {
    die("ID da coleta inválido ou não informado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Registrar Amostra para Coleta #<?= htmlspecialchars($id_coleta) ?></title>
</head>
<body>
    <h1>Registrar Amostra para Coleta #<?= htmlspecialchars($id_coleta) ?></h1>
    <form action="coleta/salvar_amostra.php" method="post">
        <input type="hidden" name="id_coleta" value="<?= htmlspecialchars($id_coleta) ?>" />

        <label for="ph">pH:</label><br />
        <input type="number" step="0.01" name="ph" id="ph" required /><br /><br />

        <label for="gordura">Gordura (%):</label><br />
        <input type="number" step="0.01" name="gordura" id="gordura" required /><br /><br />

        <label for="proteina">Proteína (%):</label><br />
        <input type="number" step="0.01" name="proteina" id="proteina" required /><br /><br />

        <label for="temperatura">Temperatura (°C):</label><br />
        <input type="number" step="0.01" name="temperatura" id="temperatura" required /><br /><br />

        <label for="contagem_celulas">Contagem de Células:</label><br />
        <input type="number" name="contagem_celulas" id="contagem_celulas" required /><br /><br />

        <button type="submit">Salvar Amostra</button>
    </form>
</body>
</html>