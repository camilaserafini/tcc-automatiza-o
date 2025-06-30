<?php
include_once 'auth/verifica_login.php';
include_once 'includes/menu.php';
include_once 'classes/Conexao.php';

if (!isset($_GET['id_caminhao'])) {
    echo "<div class='alert alert-danger'>ID do caminhão não informado.</div>";
    return;
}

$id = $_GET['id_caminhao'];

try {
    $conn = Conexao::getConexao();
    $stmt = $conn->prepare("SELECT * FROM caminhao WHERE id_caminhao = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $caminhao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$caminhao) {
        echo "<div class='alert alert-danger'>Caminhão não encontrado.</div>";
        return;
    }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<div class="content">
    <h2>Editar Caminhão</h2>

    <form action="caminhao/salvar_caminhao.php" method="post" class="mt-3">
        <input type="hidden" name="id_caminhao" value="<?php echo htmlspecialchars($caminhao['id_caminhao']); ?>">

        <div class="mb-3">
            <label for="placa">Placa:</label>
            <input type="text" name="placa" id="placa" class="form-control"
                   value="<?php echo htmlspecialchars($caminhao['placa']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="../dashboard.php?pagina=listar_caminhao" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php
echo "</main></div>";
?>
