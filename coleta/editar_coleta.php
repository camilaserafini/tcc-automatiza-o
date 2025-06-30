<?php
include_once 'auth/verifica_login.php';
include_once 'classes/Conexao.php';
if (!isset($_GET['id_coleta'])) {
    die("ID da coleta não fornecido.");
}
$conn = Conexao::getConexao();
$stmt = $conn->prepare("SELECT * FROM coleta WHERE id_coleta = :id");
$stmt->bindParam(':id', $_GET['id_coleta'], PDO::PARAM_INT);
$stmt->execute();
$coleta = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$coleta) {
    die("Coleta não encontrada.");
}
$motoristas = $conn->query("SELECT * FROM motorista")->fetchAll(PDO::FETCH_ASSOC);
$caminhoes = $conn->query("SELECT * FROM caminhao")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Coleta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include_once 'includes/menu.php'; ?>
<div class="content">
    <h2>Editar Coleta</h2>
    <form action="coleta/salvar_coleta.php" method="POST" class="mt-3">
        <input type="hidden" name="id_coleta" value="<?php echo $coleta['id_coleta']; ?>">
        <div class="mb-3">
            <label for="data_hora_chegada" class="form-label">Data e Hora da Chegada:</label>
            <input type="datetime-local" name="data_hora_chegada" id="data_hora_chegada" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($coleta['data_hora_chegada'])); ?>" required>
        </div>
        <div class="mb-3">
            <label for="id_motorista" class="form-label">Motorista:</label>
            <select name="id_motorista" id="id_motorista" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach ($motoristas as $m): ?>
                    <option value="<?php echo $m['id_motorista']; ?>" <?php if ($m['id_motorista'] == $coleta['id_motorista']) echo 'selected'; ?>>
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
                    <option value="<?php echo $c['id_caminhao']; ?>" <?php if ($c['id_caminhao'] == $coleta['id_caminhao']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($c['placa']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Atualizar</button>
        <a href="dashboard.php?pagina=listar_coleta" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>