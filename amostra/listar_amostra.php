<?php
// Caminhos corrigidos para funcionar a partir da raiz (dashboard.php)
include_once __DIR__ . '/../auth/verifica_login.php';
include_once __DIR__ . '/../classes/Conexao.php';

$con = Conexao::getConexao();

$sql = "SELECT a.*, c.data_hora_chegada
        FROM amostra a
        JOIN coleta c ON a.id_coleta = c.id_coleta
        ORDER BY c.data_hora_chegada DESC, a.id_amostra DESC";
$stmt = $con->prepare($sql);
$stmt->execute();
$amostras = $stmt->fetchAll();

if (isset($_GET['nova_amostra'])) {
    $id = (int)$_GET['nova_amostra'];
    $sql = "SELECT a.*, c.data_hora_chegada, m.nome_motorista, ca.placa
            FROM amostra a
            JOIN coleta c ON a.id_coleta = c.id_coleta
            JOIN motorista m ON c.id_motorista = m.id_motorista
            JOIN caminhao ca ON c.id_caminhao = ca.id_caminhao
            WHERE a.id_amostra = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $amostra = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($amostra) {
        echo '<div class="alert alert-success mt-3">';
        echo '<strong>Amostra coletada!</strong><br>';
        echo 'Motorista: <b>' . htmlspecialchars($amostra['nome_motorista']) . '</b> | ';
        echo 'Caminhão: <b>' . htmlspecialchars($amostra['placa']) . '</b><br>';
        echo 'pH: ' . htmlspecialchars($amostra['ph']) . ' | Gordura: ' . htmlspecialchars($amostra['gordura']) . '% | Proteína: ' . htmlspecialchars($amostra['proteina']) . '% | Temperatura: ' . htmlspecialchars($amostra['temperatura']) . '°C | Cont. Células: ' . htmlspecialchars($amostra['contagem_celulas_somaticas']);
        echo '<br>Status: <span class="badge ' . ($amostra['status'] == 'Aprovado' ? 'bg-success' : 'bg-danger') . '">' . htmlspecialchars($amostra['status']) . '</span>';
        if (!empty($amostra['motivo_recusa'])) {
            echo '<br><b>Motivo:</b> <span class="text-danger">' . htmlspecialchars($amostra['motivo_recusa']) . '</span>';
        }
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Amostras</title>
    <link rel="stylesheet" href="../bitnami.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-info text-center m-3">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<!-- Content -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-vial"></i> Lista de Amostras</h2>
        <a href="dashboard.php?pagina=cadastrar_amostra" class="btn btn-primary"><i class="fas fa-plus"></i> Nova Amostra</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Amostra</th>
                            <th>Data Coleta</th>
                            <th>pH</th>
                            <th>Gordura</th>
                            <th>Proteína</th>
                            <th>Temperatura</th>
                            <th>Cont. Células</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($amostras as $amostra): ?>
                        <tr>
                            <td><?= htmlspecialchars($amostra['id_amostra'] ?? '') ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($amostra['data_hora_chegada'] ?? ''))) ?></td>
                            <td><?= htmlspecialchars($amostra['ph'] ?? '') ?></td>
                            <td><?= htmlspecialchars($amostra['gordura'] ?? '') ?> %</td>
                            <td><?= htmlspecialchars($amostra['proteina'] ?? '') ?> %</td>
                            <td><?= htmlspecialchars($amostra['temperatura'] ?? '') ?> °C</td>
                            <td><?= htmlspecialchars($amostra['contagem_celulas_somaticas'] ?? '') ?></td>
                            <td>
                                <?php
                                    $finalizacao = isset($amostra['status_finalizacao']) && $amostra['status_finalizacao'] === 'Finalizada' ? 'Finalizada' : 'Em andamento';
                                    $status = $amostra['status'] ?? '';
                                    $show_status = ($status && $status !== $finalizacao);
                                ?>
                                <span class="badge <?= $finalizacao === 'Finalizada' ? 'bg-secondary' : 'bg-info' ?>">
                                    <?= $finalizacao ?>
                                </span>
                                <?php if ($show_status): ?>
                                    <span class="badge <?= $status === 'Aprovado' ? 'bg-success' : ($status === 'Reprovado' ? 'bg-danger' : 'bg-info') ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($amostra['motivo_recusa'])): ?>
                                    <br><small class="text-danger"><b>Motivo:</b> <?= htmlspecialchars($amostra['motivo_recusa']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="dashboard.php?pagina=editar_amostra&id_amostra=<?= $amostra['id_amostra'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Editar</a>
                                <a href="dashboard.php?pagina=excluir_amostra&id_amostra=<?= $amostra['id_amostra'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i> Excluir</a>
                                <?php if (($amostra['status_finalizacao'] ?? '') !== 'Finalizada'): ?>
                                    <a href="/amostra/finalizar_amostra.php?id_amostra=<?= $amostra['id_amostra'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Finalizar esta amostra?');"><i class="fas fa-check"></i> Finalizar</a>
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
</body>
</html>