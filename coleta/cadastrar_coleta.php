<?php
include_once __DIR__ . '/../classes/Conexao.php';
$conn = Conexao::getConexao();
$motoristas = $conn->query("SELECT * FROM motorista")->fetchAll(PDO::FETCH_ASSOC);
$caminhoes = $conn->query("SELECT * FROM caminhao")->fetchAll(PDO::FETCH_ASSOC);
$agora = date('Y-m-d\TH:i'); // Valor padrão para o campo datetime-local
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Coleta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include_once __DIR__ . '/../includes/menu.php'; ?>
<div class="content">
    <h2>Cadastrar Nova Coleta</h2>
    <form action="../coleta/salvar_coleta.php" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="data_hora_chegada" class="form-label">Data e Hora da Chegada:</label>
            <input type="datetime-local" name="data_hora_chegada" id="data_hora_chegada" class="form-control" required value="<?= $agora ?>">
        </div>
        <div class="mb-3">
            <label for="id_motorista" class="form-label">Motorista:</label>
            <select name="id_motorista" id="id_motorista" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach ($motoristas as $m): ?>
                    <option value="<?php echo $m['id_motorista']; ?>">
                        <?php echo htmlspecialchars($m['nome_motorista']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_caminhao" class="form-label">Caminhão:</label>
            <select name="id_caminhao" id="id_caminhao" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach ($caminhoes as $c): ?>
                    <option value="<?php echo $c['id_caminhao']; ?>">
                        <?php echo htmlspecialchars($c['placa']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="volume" class="form-label">Volume (L):</label>
            <input type="number" step="0.01" name="volume" id="volume" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        <a href="listar_coleta.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>