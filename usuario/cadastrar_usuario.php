<?php
include_once("../classes/Conexao.php");
include_once '../auth/configurarLogin.php';

// Inicializa variáveis para o formulário
$nome = $usuario = $nivel = '';
$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $nivel = trim($_POST['nivel']);
    $senha = (isset($_POST['senha']) && !empty($_POST['senha'])) 
             ? password_hash($_POST['senha'], PASSWORD_DEFAULT) 
             : null;
    $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : null;
    try {
        $conn = Conexao::getConexao();
        if ($id_usuario) {
            if ($senha) {
                $sql = "UPDATE usuario SET nome = :nome, nome_usuario = :usuario, nivel = :nivel, senha = :senha WHERE id_usuario = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
            } else {
                $sql = "UPDATE usuario SET nome = :nome, nome_usuario = :usuario, nivel = :nivel WHERE id_usuario = :id";
                $stmt = $conn->prepare($sql);
            }
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':nivel', $nivel, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
            if ($stmt->execute()) {
                header("Location: listar_usuarios.php");
                exit;
            } else {
                $erro = "Erro ao atualizar usuário.";
            }
        } else {
            if (!$senha) {
                $erro = "A senha é obrigatória para cadastro.";
            } else {
                $sql = "INSERT INTO usuario (nome, nome_usuario, nivel, senha) VALUES (:nome, :usuario, :nivel, :senha)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $stmt->bindParam(':nivel', $nivel, PDO::PARAM_STR);
                $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    header("Location: listar_usuarios.php");
                    exit;
                } else {
                    $erro = "Erro ao cadastrar usuário.";
                }
            }
        }
    } catch (PDOException $e) {
        $erro = "Erro no banco de dados: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include_once '../menu.php'; ?>
<div class="content">
    <h2>Cadastrar Novo Usuário</h2>
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>
    <form method="POST" action="" class="mt-3">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
        </div>
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuário:</label>
            <input type="text" name="usuario" id="usuario" class="form-control" value="<?= htmlspecialchars($usuario) ?>" required>
        </div>
        <div class="mb-3">
            <label for="nivel" class="form-label">Nível:</label>
            <select name="nivel" id="nivel" class="form-select" required>
                <option value="admin" <?= $nivel == 'admin' ? 'selected' : '' ?>>Administrador</option>
                <option value="user" <?= $nivel == 'user' ? 'selected' : '' ?>>Usuário</option>
                <option value="motorista" <?= $nivel == 'motorista' ? 'selected' : '' ?>>Motorista</option>
                <option value="sistema" <?= $nivel == 'sistema' ? 'selected' : '' ?>>Sistema</option>
                <option value="tecnico" <?= $nivel == 'tecnico' ? 'selected' : '' ?>>Técnico</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        <a href="listar_usuarios.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>