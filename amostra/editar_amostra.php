<?php
include_once 'auth/verifica_login.php';
include_once 'classes/Conexao.php';
if (!isset($_GET['id_amostra'])) {
    die("ID da amostra não fornecido.");
}
$id_amostra = $_GET['id_amostra'];
try {
    $conn = Conexao::getConexao();
    $sql = "SELECT * FROM amostra WHERE id_amostra = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_amostra, PDO::PARAM_INT);
    $stmt->execute();
    $amostra = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$amostra) {
        die("Amostra não encontrada.");
    }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Amostra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include_once 'includes/menu.php'; ?>
<div class="content">
    <h2>Editar Amostra</h2>
    <form action="amostra/salvar_amostra.php" method="POST" class="mt-3">
        <input type="hidden" name="id_amostra" value="<?php echo $amostra['id_amostra']; ?>">
        <div class="mb-3">
            <label for="id_coleta" class="form-label">ID da Coleta:</label>
            <input type="number" name="id_coleta" id="id_coleta" class="form-control" value="<?php echo $amostra['id_coleta']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="ph" class="form-label">pH:</label>
            <input type="number" step="0.01" name="ph" id="ph" class="form-control" value="<?php echo isset($amostra['ph']) ? htmlspecialchars($amostra['ph']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="gordura" class="form-label">Gordura (%):</label>
            <input type="number" step="0.01" name="gordura" id="gordura" class="form-control" value="<?php echo isset($amostra['gordura']) ? htmlspecialchars($amostra['gordura']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="proteina" class="form-label">Proteína (%):</label>
            <input type="number" step="0.01" name="proteina" id="proteina" class="form-control" value="<?php echo isset($amostra['proteina']) ? htmlspecialchars($amostra['proteina']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="temperatura" class="form-label">Temperatura (°C):</label>
            <input type="number" step="0.1" name="temperatura" id="temperatura" class="form-control" value="<?php echo isset($amostra['temperatura']) ? htmlspecialchars($amostra['temperatura']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="contagem_celulas_somaticas" class="form-label">Contagem de Células:</label>
            <input type="number" name="contagem_celulas_somaticas" id="contagem_celulas_somaticas" class="form-control" value="<?php echo isset($amostra['contagem_celulas_somaticas']) ? htmlspecialchars($amostra['contagem_celulas_somaticas']) : ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Alterações</button>
        <a href="listar_amostra.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>