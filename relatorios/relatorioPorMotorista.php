<?php
require_once 'classes/Conexao.php';
$conn = Conexao::getConexao();
$id_motorista_val = isset($_GET['id_motorista']) ? htmlspecialchars($_GET['id_motorista']) : '';
// Busca todos os motoristas para o menu dropdown
$motoristas = $conn->query("SELECT id_motorista, nome_motorista FROM motorista ORDER BY nome_motorista")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-id-card"></i> Relatório de Coletas por Motorista</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="pagina" value="relatorioPorMotorista">
                <div class="row align-items-end">
                    <div class="col-md-10">
                        <label for="id_motorista" class="form-label">Selecione o Motorista:</label>
                        <select id="id_motorista" name="id_motorista" class="form-select" required>
                            <option value="">-- Selecione um motorista --</option>
                            <?php foreach ($motoristas as $motorista): ?>
                                <option value="<?php echo $motorista['id_motorista']; ?>" <?php echo ($id_motorista_val == $motorista['id_motorista']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($motorista['nome_motorista']); ?>
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
    if (!empty($id_motorista_val)) {
        try {
            // Correção na query: data -> data_hora_chegada, m.nome -> m.nome_motorista, removido temperatura
            $sql = "SELECT c.id_coleta, c.data_hora_chegada, m.nome_motorista, 
                           ca.placa, c.volume
                    FROM coleta c
                    INNER JOIN motorista m ON c.id_motorista = m.id_motorista
                    INNER JOIN caminhao ca ON c.id_caminhao = ca.id_caminhao
                    WHERE m.id_motorista = :id_motorista
                    ORDER BY c.data_hora_chegada DESC";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_motorista', $id_motorista_val);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<h4>Resultados para o motorista selecionado</h4>';

            if ($resultados) {
                echo '<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-striped table-hover">';
                echo '<thead class="table-primary"><tr><th>ID Coleta</th><th>Data/Hora</th><th>Caminhão</th><th>Volume (L)</th></tr></thead><tbody>';
                foreach ($resultados as $linha) {
                    $data_formatada = date('d/m/Y H:i', strtotime($linha['data_hora_chegada']));
                    echo "<tr><td>{$linha['id_coleta']}</td><td>{$data_formatada}</td><td>{$linha['placa']}</td><td>{$linha['volume']}</td></tr>";
                }
                echo '</tbody></table></div></div></div>';
            } else {
                echo '<div class="alert alert-info mt-3">Nenhuma coleta encontrada para este motorista.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger mt-3">Erro ao buscar dados: ' . $e->getMessage() . '</div>';
        }
    }
    ?>
</div>
