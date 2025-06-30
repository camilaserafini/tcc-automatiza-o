<?php
/**
 * Dashboard de Métricas de Usabilidade
 * 
 * Visualização gráfica e interativa dos resultados dos testes de usabilidade
 */

// Simula dados de testes de usabilidade
$dados = [
    'navegacao' => [
        'nome' => 'Navegação',
        'pontuacao' => 85.7,
        'testes' => [
            'Menu principal visível' => true,
            'Links funcionais' => true,
            'Breadcrumbs presentes' => false,
            'Botão voltar funcionando' => true,
            'Navegação por teclado' => true,
            'URLs amigáveis' => true,
            'Estado ativo do menu' => true
        ]
    ],
    'formularios' => [
        'nome' => 'Formulários',
        'pontuacao' => 71.4,
        'testes' => [
            'Labels associados aos campos' => true,
            'Validação em tempo real' => false,
            'Mensagens de erro claras' => true,
            'Campos obrigatórios marcados' => true,
            'Autocomplete funcionando' => false,
            'Submit com Enter' => true,
            'Limpeza de formulário' => true
        ]
    ],
    'responsividade' => [
        'nome' => 'Responsividade',
        'pontuacao' => 85.7,
        'testes' => [
            'Mobile (320px)' => true,
            'Tablet (768px)' => true,
            'Desktop (1024px)' => true,
            'Menu hambúrguer mobile' => false,
            'Imagens responsivas' => true,
            'Texto legível em mobile' => true,
            'Touch targets adequados' => true
        ]
    ],
    'acessibilidade' => [
        'nome' => 'Acessibilidade',
        'pontuacao' => 71.4,
        'testes' => [
            'Alt text em imagens' => false,
            'Contraste de cores' => true,
            'Navegação por tab' => true,
            'ARIA labels' => false,
            'Tamanho de fonte ajustável' => true,
            'Sem dependência de cor' => true,
            'Estrutura semântica' => true
        ]
    ],
    'performance' => [
        'nome' => 'Performance',
        'pontuacao' => 42.9,
        'testes' => [
            'Tempo de carregamento < 3s' => true,
            'CSS minificado' => false,
            'JS minificado' => false,
            'Imagens otimizadas' => true,
            'Cache configurado' => false,
            'Compressão gzip' => false,
            'Sem recursos desnecessários' => true
        ]
    ],
    'seguranca' => [
        'nome' => 'Segurança',
        'pontuacao' => 71.4,
        'testes' => [
            'HTTPS configurado' => false,
            'Headers de segurança' => false,
            'CSRF protection' => true,
            'XSS protection' => true,
            'SQL injection protection' => true,
            'Senhas criptografadas' => true,
            'Sessões seguras' => true
        ]
    ],
    'consistencia' => [
        'nome' => 'Consistência',
        'pontuacao' => 100.0,
        'testes' => [
            'Cores consistentes' => true,
            'Tipografia uniforme' => true,
            'Espaçamentos padronizados' => true,
            'Ícones consistentes' => true,
            'Terminologia uniforme' => true,
            'Layout consistente' => true,
            'Comportamento previsível' => true
        ]
    ]
];

// Calcula estatísticas gerais
$total_categorias = count($dados);
$pontuacao_geral = array_sum(array_column($dados, 'pontuacao')) / $total_categorias;
$total_testes = 0;
$total_passou = 0;

foreach ($dados as $categoria) {
    foreach ($categoria['testes'] as $teste => $resultado) {
        $total_testes++;
        if ($resultado) $total_passou++;
    }
}

$percentual_geral = ($total_passou / $total_testes) * 100;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Métricas - Usabilidade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            margin: 2rem auto;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .metric-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-5px);
        }
        .metric-value {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .metric-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background: #f0f0f0;
            overflow: hidden;
        }
        .progress-bar-custom {
            height: 100%;
            border-radius: 10px;
            transition: width 1s ease;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 1.5rem 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .test-detail {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 0.5rem 0;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        .status-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="header-gradient text-center">
                <h1><i class="fas fa-chart-line"></i> Dashboard de Métricas de Usabilidade</h1>
                <p class="lead mb-0">Sistema de Coleta de Leite - Análise Completa</p>
            </div>

            <!-- Métricas Principais -->
            <div class="row">
                <div class="col-md-3">
                    <div class="metric-card text-center">
                        <div class="metric-value text-primary"><?php echo round($pontuacao_geral, 1); ?>%</div>
                        <div class="metric-label">Pontuação Geral</div>
                        <div class="progress-custom mt-2">
                            <div class="progress-bar-custom bg-primary" style="width: <?php echo $pontuacao_geral; ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card text-center">
                        <div class="metric-value text-success"><?php echo $total_passou; ?></div>
                        <div class="metric-label">Testes Aprovados</div>
                        <div class="progress-custom mt-2">
                            <div class="progress-bar-custom bg-success" style="width: <?php echo ($total_passou/$total_testes)*100; ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card text-center">
                        <div class="metric-value text-info"><?php echo $total_categorias; ?></div>
                        <div class="metric-label">Categorias Avaliadas</div>
                        <div class="progress-custom mt-2">
                            <div class="progress-bar-custom bg-info" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card text-center">
                        <div class="metric-value text-warning"><?php echo $total_testes; ?></div>
                        <div class="metric-label">Total de Critérios</div>
                        <div class="progress-custom mt-2">
                            <div class="progress-bar-custom bg-warning" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h4><i class="fas fa-chart-pie"></i> Distribuição por Categoria</h4>
                        <canvas id="pieChart" width="400" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h4><i class="fas fa-chart-bar"></i> Comparativo de Pontuações</h4>
                        <canvas id="barChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detalhes por Categoria -->
            <div class="row">
                <?php foreach ($dados as $key => $categoria): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="metric-card">
                        <h5><i class="fas fa-layer-group"></i> <?php echo $categoria['nome']; ?></h5>
                        <div class="metric-value text-<?php echo $categoria['pontuacao'] >= 80 ? 'success' : ($categoria['pontuacao'] >= 60 ? 'warning' : 'danger'); ?>">
                            <?php echo $categoria['pontuacao']; ?>%
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar-custom bg-<?php echo $categoria['pontuacao'] >= 80 ? 'success' : ($categoria['pontuacao'] >= 60 ? 'warning' : 'danger'); ?>" 
                                 style="width: <?php echo $categoria['pontuacao']; ?>%"></div>
                        </div>
                        
                        <div class="mt-3">
                            <h6>Detalhes dos Testes:</h6>
                            <?php foreach ($categoria['testes'] as $teste => $resultado): ?>
                            <div class="test-detail">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?php echo $teste; ?></span>
                                    <span class="status-badge status-<?php echo $resultado ? 'success' : 'danger'; ?>">
                                        <?php echo $resultado ? 'PASSOU' : 'FALHOU'; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Recomendações -->
            <div class="row">
                <div class="col-12">
                    <div class="metric-card">
                        <h4><i class="fas fa-lightbulb"></i> Recomendações de Melhoria</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="text-danger"><i class="fas fa-exclamation-triangle"></i> Prioridade Alta</h5>
                                <ul>
                                    <li>Implementar breadcrumbs</li>
                                    <li>Adicionar validação em tempo real</li>
                                    <li>Configurar HTTPS</li>
                                    <li>Menu hambúrguer mobile</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-warning"><i class="fas fa-info-circle"></i> Prioridade Média</h5>
                                <ul>
                                    <li>Alt text em imagens</li>
                                    <li>ARIA labels</li>
                                    <li>Minificar recursos</li>
                                    <li>Configurar cache</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-success"><i class="fas fa-check-circle"></i> Pontos Fortes</h5>
                                <ul>
                                    <li>Consistência visual</li>
                                    <li>Navegação intuitiva</li>
                                    <li>Responsividade básica</li>
                                    <li>Segurança básica</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dados para os gráficos
        const categorias = <?php echo json_encode(array_column($dados, 'nome')); ?>;
        const pontuacoes = <?php echo json_encode(array_column($dados, 'pontuacao')); ?>;
        const cores = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
            '#9966FF', '#FF9F40', '#FF6384'
        ];

        // Gráfico de Pizza
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: categorias,
                datasets: [{
                    data: pontuacoes,
                    backgroundColor: cores,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de Barras
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: categorias,
                datasets: [{
                    label: 'Pontuação (%)',
                    data: pontuacoes,
                    backgroundColor: cores,
                    borderColor: cores.map(cor => cor.replace('0.8', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Animação das barras de progresso
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar-custom');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
</body>
</html> 