<?php
date_default_timezone_set('America/Sao_Paulo');
if (basename($_SERVER['SCRIPT_NAME']) == 'listar_caminhao.php') {
    header('Location: ../dashboard.php?pagina=listar_caminhao');
    exit;
}
// Caminhos corrigidos para funcionar a partir da raiz (dashboard.php)
include_once __DIR__ . '/../auth/verifica_login.php';
include_once __DIR__ . '/../classes/Conexao.php';

try {
    $pdo = Conexao::getConexao();
    $stmt = $pdo->prepare("SELECT id_caminhao, placa, capacidade FROM caminhao ORDER BY placa");
    $stmt->execute();
    $caminhoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Falha ao buscar caminhões: " . $e->getMessage() . "</div>";
    $caminhoes = []; // Garante que a variável exista para evitar mais erros
}

// Buscar chegadas de hoje para todos os caminhões
$chegadasHoje = [];
try {
    $hoje = date('Y-m-d');
    $stmtChegada = $pdo->prepare("SELECT id_caminhao FROM coleta WHERE DATE(data_hora_chegada) = :hoje");
    $stmtChegada->bindParam(':hoje', $hoje);
    $stmtChegada->execute();
    foreach ($stmtChegada->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $chegadasHoje[$row['id_caminhao']] = true;
    }
} catch (PDOException $e) {
    // Ignorar erro de chegada
}
?>

<!-- Content -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck"></i> Lista de Caminhões</h2>
        <!-- Link corrigido para o sistema de dashboard -->
        <a href="dashboard.php?pagina=cadastrar_caminhao" class="btn btn-primary"><i class="fas fa-plus"></i> Novo Caminhão</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Placa</th>
                            <th>Capacidade</th>
                            <th>Chegada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($caminhoes as $caminhao): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($caminhao['id_caminhao'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($caminhao['placa'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($caminhao['capacidade'] ?? ''); ?></td>
                            <td>
                                <?php if (!empty($chegadasHoje[$caminhao['id_caminhao']])): ?>
                                    <span class="badge bg-success">Registrada</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pendente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Links corrigidos para o sistema de dashboard -->
                                <a href="dashboard.php?pagina=editar_caminhao&id_caminhao=<?php echo $caminhao['id_caminhao']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="caminhao/excluir_caminhao.php?id_caminhao=<?php echo $caminhao['id_caminhao']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                                <form action="motorista/registrar_chegada.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="id_caminhao" value="<?php echo $caminhao['id_caminhao']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" <?php if (!empty($chegadasHoje[$caminhao['id_caminhao']])) echo 'disabled'; ?>>
                                        <i class="fas fa-sign-in-alt"></i> Registrar Chegada
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>