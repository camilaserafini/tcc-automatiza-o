<?php
// Caminhos corrigidos para funcionar a partir da raiz (dashboard.php)
include_once 'auth/verifica_login.php';
include_once 'classes/Conexao.php';

$con = Conexao::getConexao();

$sql = "SELECT c.*, m.nome_motorista, ca.placa 
        FROM coleta c
        JOIN motorista m ON c.id_motorista = m.id_motorista
        JOIN caminhao ca ON c.id_caminhao = ca.id_caminhao
        ORDER BY c.data_hora_chegada DESC";
$stmt = $con->prepare($sql);
$stmt->execute();
$coletas = $stmt->fetchAll();
?>

<!-- Content -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list"></i> Lista de Coletas</h2>
        <a href="dashboard.php?pagina=cadastrar_coleta" class="btn btn-primary"><i class="fas fa-plus"></i> Nova Coleta</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Data/Hora</th>
                            <th>Motorista</th>
                            <th>Caminhão</th>
                            <th>Volume</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coletas as $coleta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($coleta['id_coleta']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($coleta['data_hora_chegada']))); ?></td>
                            <td><?php echo htmlspecialchars($coleta['nome_motorista']); ?></td>
                            <td><?php echo htmlspecialchars($coleta['placa']); ?></td>
                            <td><?php echo htmlspecialchars((string)($coleta['volume'] ?? '')); ?> L</td>
                            <td>
                                <?php 
                                    $finalizacao = isset($coleta['status_finalizacao']) && $coleta['status_finalizacao'] === 'Finalizada';
                                    $status = $coleta['status'] ?? '';
                                ?>
                                <?php if ($finalizacao): ?>
                                    <span class="badge bg-secondary">Finalizada</span>
                                <?php else: ?>
                                    <?php if ($status === 'Concluído'): ?>
                                        <span class="badge bg-success">Concluído</span>
                                    <?php elseif ($status === 'Pendente'): ?>
                                        <span class="badge bg-warning">Pendente</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Em andamento</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="dashboard.php?pagina=editar_coleta&id_coleta=<?php echo $coleta['id_coleta']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Editar</a>
                                <a href="coleta/excluir_coleta.php?id_coleta=<?php echo $coleta['id_coleta']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i> Excluir</a>
                                <a href="dashboard.php?pagina=coletar_amostra&id_coleta=<?php echo $coleta['id_coleta']; ?>" class="btn btn-sm btn-success"><i class="fas fa-vial"></i> Amostra</a>
                                <?php if (($coleta['status_finalizacao'] ?? '') !== 'Finalizada'): ?>
                                    <a href="coleta/finalizar_coleta.php?id_coleta=<?= $coleta['id_coleta'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Finalizar esta coleta?');"><i class="fas fa-check"></i> Finalizar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>