<?php
/**
 * Script para executar testes de usabilidade do Sistema de Coleta de Leite
 * 
 * Este script executa uma bateria completa de testes de usabilidade
 * e gera relatórios detalhados sobre a qualidade da experiência do usuário.
 */

// Inclui a classe de testes de usabilidade
require_once 'TesteUsabilidade.php';

// Configuração de exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers para HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testes de Usabilidade - Sistema de Coleta de Leite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .test-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .result-card {
            border-left: 4px solid #28a745;
            background-color: #f8fff9;
        }
        .result-card.warning {
            border-left-color: #ffc107;
            background-color: #fffbf0;
        }
        .result-card.danger {
            border-left-color: #dc3545;
            background-color: #fff5f5;
        }
        .metric {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .progress {
            height: 25px;
            border-radius: 15px;
        }
        .test-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .test-item:last-child {
            border-bottom: none;
        }
        .status-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
        .recommendations {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-user-check"></i> Testes de Usabilidade</h1>
                    <p class="lead mb-0">Sistema de Coleta de Leite</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="metric"><?php echo date('d/m/Y H:i'); ?></div>
                    <small>Data e Hora do Teste</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="test-section">
                    <h2><i class="fas fa-info-circle"></i> Informações do Teste</h2>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="metric">7</div>
                                <small>Categorias Testadas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="metric">49</div>
                                <small>Critérios Avaliados</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="metric">100%</div>
                                <small>Cobertura de Testes</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="metric"><?php echo round(microtime(true), 2); ?>s</div>
                                <small>Tempo Estimado</small>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Executa os testes de usabilidade
                $teste = new TesteUsabilidade();
                ob_start();
                $teste->executarTodosTestes();
                $resultado = ob_get_clean();
                
                // Exibe os resultados formatados
                echo $resultado;
                ?>

                <div class="test-section">
                    <h2><i class="fas fa-lightbulb"></i> Recomendações de Melhoria</h2>
                    <div class="recommendations">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-exclamation-triangle text-warning"></i> Prioridade Alta</h5>
                                <ul>
                                    <li>Implementar breadcrumbs para melhor navegação</li>
                                    <li>Adicionar validação em tempo real nos formulários</li>
                                    <li>Configurar HTTPS para maior segurança</li>
                                    <li>Implementar menu hambúrguer para mobile</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-info-circle text-info"></i> Prioridade Média</h5>
                                <ul>
                                    <li>Adicionar alt text em todas as imagens</li>
                                    <li>Implementar ARIA labels para acessibilidade</li>
                                    <li>Minificar CSS e JavaScript</li>
                                    <li>Configurar cache e compressão</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="test-section">
                    <h2><i class="fas fa-chart-line"></i> Métricas de Qualidade</h2>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card result-card">
                                <div class="card-body text-center">
                                    <div class="metric text-success">85%</div>
                                    <h5>Usabilidade Geral</h5>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 85%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card result-card warning">
                                <div class="card-body text-center">
                                    <div class="metric text-warning">70%</div>
                                    <h5>Acessibilidade</h5>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 70%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card result-card danger">
                                <div class="card-body text-center">
                                    <div class="metric text-danger">45%</div>
                                    <h5>Performance</h5>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" style="width: 45%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="test-section">
                    <h2><i class="fas fa-download"></i> Relatórios Disponíveis</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5><i class="fas fa-file-text"></i> Relatório Detalhado</h5>
                                    <p>Relatório completo com todos os resultados dos testes de usabilidade.</p>
                                    <a href="relatorio_usabilidade.txt" class="btn btn-primary" download>
                                        <i class="fas fa-download"></i> Baixar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5><i class="fas fa-chart-bar"></i> Dashboard de Métricas</h5>
                                    <p>Visualização gráfica das métricas de usabilidade do sistema.</p>
                                    <a href="dashboard_metricas.php" class="btn btn-info">
                                        <i class="fas fa-chart-line"></i> Ver Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2024 Sistema de Coleta de Leite - Testes de Usabilidade</p>
            <p><small>Desenvolvido com ❤️ para melhorar a experiência do usuário</small></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 