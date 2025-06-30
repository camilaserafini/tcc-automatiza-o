<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo '<div style="color:red;font-weight:bold">DEBUG: Entrou no cadastrar_amostra.php</div>';
include_once 'auth/verifica_login.php';
include_once 'classes/Conexao.php';

// Buscar coletas para exibir no <select>
try {
    $conn = Conexao::getConexao();
    $stmt = $conn->query("SELECT c.id_coleta, m.nome_motorista, ca.placa FROM coleta c JOIN motorista m ON c.id_motorista = m.id_motorista JOIN caminhao ca ON c.id_caminhao = ca.id_caminhao ORDER BY c.id_coleta DESC");
    $coletas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar coletas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Amostra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include_once 'includes/menu.php'; ?>
<div class="content">
    <h2>Cadastrar Amostra</h2>
    <?php if (empty($coletas)): ?>
        <div class="alert alert-warning">
            Nenhuma coleta cadastrada. Cadastre uma coleta antes de adicionar uma amostra.
        </div>
    <?php else: ?>
    <form action="amostra/salvar_amostra.php" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="id_coleta" class="form-label">ID da Coleta:</label>
            <select name="id_coleta" id="id_coleta" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($coletas as $coleta): ?>
                    <option value="<?php echo $coleta['id_coleta']; ?>">
                        <?php echo $coleta['id_coleta'] . ' - Motorista: ' . htmlspecialchars($coleta['nome_motorista']) . ' - Caminhão: ' . htmlspecialchars($coleta['placa']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="ph" class="form-label">pH:</label>
            <input type="number" step="0.01" name="ph" id="ph" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="gordura" class="form-label">Gordura (%):</label>
            <input type="number" step="0.01" name="gordura" id="gordura" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="proteina" class="form-label">Proteína (%):</label>
            <input type="number" step="0.01" name="proteina" id="proteina" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="temperatura" class="form-label">Temperatura (°C):</label>
            <input type="number" step="0.1" name="temperatura" id="temperatura" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="contagem_celulas_somaticas" class="form-label">Contagem de Células:</label>
            <input type="number" name="contagem_celulas_somaticas" id="contagem_celulas_somaticas" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        <a href="dashboard.php?pagina=listar_amostra" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </form>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>