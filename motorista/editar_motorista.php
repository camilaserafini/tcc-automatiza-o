<?php
require_once __DIR__ . '/../classes/Conexao.php';
require_once __DIR__ . '/../classes/Motorista.php';

// 1. Verificação do ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID do motorista inválido.</div>";
    return;
}
$id_motorista = $_GET['id'];

// 2. Lógica de atualização (quando o formulário é enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_motorista = $_POST['nome_motorista'] ?? '';
    
    if (!empty($nome_motorista)) {
        $motorista = new Motorista();
        $motorista->setNomeMotorista($nome_motorista);
        
        if ($motorista->atualizar($id_motorista)) {
            echo "<div class='alert alert-success'>Motorista atualizado com sucesso! Redirecionando...</div>";
            echo "<script>setTimeout(() => window.location.href='dashboard.php?pagina=listar_motoristas', 1500);</script>";
            return; // Impede que o formulário seja renderizado novamente
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar o motorista.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>O nome do motorista não pode ser vazio.</div>";
    }
}

// 3. Busca dos dados para preencher o formulário
$motorista = new Motorista();
$dados = $motorista->buscarPorId($id_motorista);

if (!$dados) {
    echo "<div class='alert alert-warning'>Motorista não encontrado.</div>";
    return;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Motorista</title>
</head>
<body>

<div class="container-fluid">
    <h3><i class="fas fa-edit"></i> Editar Motorista</h3>
    <hr>
    <form method="POST" action="../dashboard.php?pagina=editar_motorista&id=<?= htmlspecialchars($id_motorista) ?>">
        <div class="mb-3">
            <label for="nome_motorista" class="form-label">Nome do Motorista:</label>
            <input type="text" name="nome_motorista" id="nome_motorista" class="form-control" value="<?= htmlspecialchars($dados['nome_motorista']) ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Atualizar</button>
        <a href="../dashboard.php?pagina=listar_motoristas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php
echo "</main></div>";
?>
</body>
</html>