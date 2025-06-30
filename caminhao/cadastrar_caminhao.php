<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// include_once '../auth/configurarLogin.php'; // Removido pois não existe
include_once __DIR__ . '/../includes/menu.php';
?>

<div class="content">
    <h2>Cadastrar Caminhão</h2>

    <form action="caminhao/salvar_caminhao.php" method="post" class="mt-3">
        <div class="mb-3">
            <label for="placa">Placa do Caminhão:</label>
            <input type="text" name="placa" id="placa" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="../dashboard.php?pagina=listar_caminhao" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php
echo "</main></div>";
?>