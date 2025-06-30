<?php
include_once 'auth/verifica_login.php';
require_once 'classes/Motorista.php';
require_once 'classes/Conexao.php';

// Lógica de cadastro (quando o formulário é enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_motorista = $_POST['nome_motorista'] ?? null;

    if ($nome_motorista) {
        $motorista = new Motorista();
        $motorista->setNomeMotorista($nome_motorista);
        
        if ($motorista->salvar()) {
            echo "<div class='alert alert-success'>Motorista cadastrado com sucesso! Redirecionando...</div>";
            echo "<script>setTimeout(() => window.location.href='dashboard.php?pagina=listar_motoristas', 1500);</script>";
            return;
        } else {
            echo "<div class='alert alert-danger'>Erro ao cadastrar o motorista.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>O nome do motorista é obrigatório.</div>";
    }
}

$conn = Conexao::getConexao();
// Buscar todos os usuários para o select
$usuarios = $conn->query("SELECT id_usuario, nome_usuario FROM usuario ORDER BY nome_usuario")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Motorista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include_once 'includes/menu.php'; ?>
<div class="content">
    <h2>Cadastrar Novo Motorista</h2>
    <form method="POST" action="dashboard.php?pagina=cadastrar_motorista" class="mt-3">
        <div class="mb-3">
            <label for="nome_motorista" class="form-label">Nome do Motorista:</label>
            <input type="text" name="nome_motorista" id="nome_motorista" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_usuario" class="form-label">Usuário do Sistema:</label>
            <select name="id_usuario" id="id_usuario" class="form-select" required>
                <option value="">Selecione o usuário</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nome_usuario']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        <a href="dashboard.php?pagina=listar_motoristas" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>