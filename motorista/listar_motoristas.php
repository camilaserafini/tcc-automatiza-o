<?php
// Caminhos corrigidos para funcionar a partir da raiz (dashboard.php)
require_once 'auth/verifica_login.php';
require_once 'classes/Conexao.php';
require_once 'classes/Motorista.php';

try {
    $pdo = Conexao::getConexao();
    
    // Consulta SQL corrigida: removendo a coluna 'cnh' que não existe
    $stmt = $pdo->prepare("SELECT id_motorista, nome_motorista FROM motorista ORDER BY nome_motorista");
    $stmt->execute();
    
    $motoristas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Tratar o erro de forma mais elegante no futuro
    die("Erro ao buscar motoristas: " . $e->getMessage());
}
?>

<div class="container-fluid">
    <h2 class="text-center mb-4">Motoristas Cadastrados</h2>

    <!-- Botão para adicionar novo motorista -->
    <a href="dashboard.php?pagina=cadastrar_motorista" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Adicionar Motorista
    </a>

    <!-- Tabela de motoristas -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($motoristas) > 0): ?>
                        <?php foreach ($motoristas as $motorista): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($motorista['id_motorista']); ?></td>
                                <td><?php echo htmlspecialchars($motorista['nome_motorista']); ?></td>
                                <td>
                                    <a href="dashboard.php?pagina=editar_motorista&id=<?php echo $motorista['id_motorista']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="motorista/excluir_motorista.php?id_motorista=<?php echo $motorista['id_motorista']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este motorista?');">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Nenhum motorista cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 