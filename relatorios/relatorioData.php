<?php
// O login já é verificado pelo dashboard.php.
// O 'page' é passado pela URL para manter o estado do formulário.
$data_inicio_val = isset($_GET['data_inicio']) ? htmlspecialchars($_GET['data_inicio']) : '';
$data_fim_val = isset($_GET['data_fim']) ? htmlspecialchars($_GET['data_fim']) : '';
?>
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-calendar-alt"></i> Relatório de Coletas por Período</h2>

    <div class="card mb-4">
        <div class="card-body">
            <!-- O formulário envia os dados para a própria página do dashboard -->
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="pagina" value="relatorioData">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label for="data_inicio" class="form-label">Data Início:</label>
                        <input type="date" id="data_inicio" name="data_inicio" class="form-control" required value="<?php echo $data_inicio_val; ?>">
                    </div>
                    <div class="col-md-5">
                        <label for="data_fim" class="form-label">Data Fim:</label>
                        <input type="date" id="data_fim" name="data_fim" class="form-control" required value="<?php echo $data_fim_val; ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Gerar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (!empty($data_inicio_val) && !empty($data_fim_val)) {
        // Corrigido o caminho para a Conexao.php
        require_once 'classes/Conexao.php';
        try {
            $con = Conexao::getConexao();

            $data_inicio = $data_inicio_val . ' 00:00:00';
            $data_fim = $data_fim_val . ' 23:59:59';
            
            // A coluna 'temperatura' não existe na tabela 'coleta', foi removida da query.
            $sql = "SELECT c.id_coleta, c.data_hora_chegada as data, m.nome_motorista AS motorista, 
                           ca.placa AS caminhao, c.volume
                    FROM coleta c
                    INNER JOIN motorista m ON c.id_motorista = m.id_motorista
                    INNER JOIN caminhao ca ON c.id_caminhao = ca.id_caminhao
                    WHERE c.data_hora_chegada BETWEEN :data_inicio AND :data_fim
                    ORDER BY c.data_hora_chegada DESC";

            $stmt = $con->prepare($sql);
            $stmt->bindParam(':data_inicio', $data_inicio);
            $stmt->bindParam(':data_fim', $data_fim);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<h4>Resultados para o período de ' . date('d/m/Y', strtotime($data_inicio_val)) . ' a ' . date('d/m/Y', strtotime($data_fim_val)) . '</h4>';

            if ($resultados) {
                echo '<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-striped table-hover">';
                echo '<thead class="table-primary"><tr><th>ID</th><th>Data/Hora</th><th>Motorista</th><th>Caminhão</th><th>Volume (L)</th></tr></thead><tbody>';
                foreach ($resultados as $linha) {
                    $data_formatada = date('d/m/Y H:i', strtotime($linha['data']));
                    echo "<tr><td>{$linha['id_coleta']}</td><td>{$data_formatada}</td><td>{$linha['motorista']}</td><td>{$linha['caminhao']}</td><td>{$linha['volume']}</td></tr>";
                }
                echo '</tbody></table></div></div></div>';
            } else {
                echo '<div class="alert alert-info mt-3">Nenhuma coleta encontrada no período selecionado.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger mt-3">Erro ao buscar dados: ' . $e->getMessage() . '</div>';
        }
    }
    ?>
</div>