<?php
include_once '../auth/configurarLogin.php';
include_once("../classes/Conexao.php");
if (!isset($_GET['id_usuario']) || !is_numeric($_GET['id_usuario'])) {
    die("ID do usuário não fornecido ou inválido.");
}
try {
    $conn = Conexao::getConexao();
    $sql = "SELECT * FROM usuario WHERE id_usuario = :id_usuario";
    $stmt = $conn->prepare($sql);
    $id_usuario = (int) $_GET['id_usuario'];
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        die("Usuário não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include_once '../menu.php'; ?>
<div class="content">
    <h2>Editar Usuário</h2>
    <form action="cadastrar_usuario.php" method="POST" class="mt-3">
        <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuário:</label>
            <input type="text" id="usuario" name="usuario" class="form-control" value="<?php echo htmlspecialchars($usuario['nome_usuario']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" id="senha" name="senha" class="form-control" placeholder="Deixe em branco para não alterar">
        </div>
        <div class="mb-3">
            <label for="nivel" class="form-label">Nível:</label>
            <select name="nivel" id="nivel" class="form-select" required>
                <option value="admin" <?php if ($usuario['nivel'] === 'admin') echo 'selected'; ?>>Administrador</option>
                <option value="user" <?php if ($usuario['nivel'] === 'user' || $usuario['nivel'] === 'usuario') echo 'selected'; ?>>Usuário</option>
                <option value="motorista" <?php if ($usuario['nivel'] === 'motorista') echo 'selected'; ?>>Motorista</option>
                <option value="sistema" <?php if ($usuario['nivel'] === 'sistema') echo 'selected'; ?>>Sistema</option>
                <option value="tecnico" <?php if ($usuario['nivel'] === 'tecnico') echo 'selected'; ?>>Técnico</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Atualizar</button>
        <a href="listar_usuarios.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>