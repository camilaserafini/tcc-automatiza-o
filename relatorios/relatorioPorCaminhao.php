<?php
require_once 'classes/Conexao.php';
$conn = Conexao::getConexao();
$id_caminhao_val = isset($_GET['id_caminhao']) ? htmlspecialchars($_GET['id_caminhao']) : '';
// Busca todos os caminhões para o menu dropdown
$caminhoes = $conn->query("SELECT id_caminhao, placa FROM caminhao ORDER BY placa")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-truck"></i> Relatório de Coletas por Caminhão</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="pagina" value="relatorioPorCaminhao">
                <div class="row align-items-end">
                    <div class="col-md-10">
                        <label for="id_caminhao" class="form-label">Selecione o Caminhão:</label>
                        <select id="id_caminhao" name="id_caminhao" class="form-select" required>
                            <option value="">-- Selecione um caminhão --</option>
                            <?php foreach ($caminhoes as $caminhao): ?>
                                <option value="<?php echo $caminhao['id_caminhao']; ?>" <?php echo ($id_caminhao_val == $caminhao['id_caminhao']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($caminhao['placa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Gerar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (!empty($id_caminhao_val)) {
        try {
            // Correção na query: data -> data_hora_chegada, m.nome -> m.nome_motorista, removido temperatura
            $sql = "SELECT c.id_coleta, c.data_hora_chegada, m.nome_motorista, 
                           ca.placa, c.volume
                    FROM coleta c
                    INNER JOIN motorista m ON c.id_motorista = m.id_motorista
                    INNER JOIN caminhao ca ON c.id_caminhao = ca.id_caminhao
                    WHERE c.id_caminhao = :id_caminhao
                    ORDER BY c.data_hora_chegada DESC";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_caminhao', $id_caminhao_val);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<h4>Resultados para o caminhão selecionado</h4>';

            if ($resultados) {
                echo '<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-striped table-hover">';
                echo '<thead class="table-primary"><tr><th>ID Coleta</th><th>Data/Hora</th><th>Motorista</th><th>Volume (L)</th></tr></thead><tbody>';
                foreach ($resultados as $linha) {
                    $data_formatada = date('d/m/Y H:i', strtotime($linha['data_hora_chegada']));
                    echo "<tr><td>{$linha['id_coleta']}</td><td>{$data_formatada}</td><td>{$linha['nome_motorista']}</td><td>{$linha['volume']}</td></tr>";
                }
                echo '</tbody></table></div></div></div>';
            } else {
                echo '<div class="alert alert-info mt-3">Nenhuma coleta encontrada para este caminhão.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger mt-3">Erro ao buscar dados: ' . $e->getMessage() . '</div>';
        }
    }
    ?>
</div>