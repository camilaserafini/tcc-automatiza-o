<?php
// Habilitar exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'auth/verifica_login.php';
$usuario_nome = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Usuário';
$nivel = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Coleta de Leite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary-rgb: 58, 97, 184;
            --bs-secondary-rgb: 108, 117, 125;
        }
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        #sidebar {
            width: 260px;
            background: #3a61b8;
            color: white;
            min-height: 100vh;
        }
        #content {
            flex-grow: 1;
            padding: 40px 40px 40px 40px;
            transition: margin-left .3s;
            min-width: 0;
        }
        .card {
            max-width: 1100px;
            margin: 0 auto 30px auto;
            border-radius: 12px;
        }
        .nav-link {
            color: #fff !important;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        .nav-link:hover, .nav-link.active {
            background: #2451a6 !important;
            color: #fff !important;
        }
        .bg-danger {
            background-color: #dc3545 !important;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .hub-card {
            transition: transform .2s, box-shadow .2s;
        }
        .hub-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary" id="sidebar">
        <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="fas fa-truck-droplet fa-2x me-2"></i>
            <span class="fs-4">Coleta de Leite</span>
        </a>
        <hr>
        <?php include 'includes/menu.php'; ?>
    </div>

    <div id="content">
        <!-- Bloco de feedback -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info mt-3">
                <?php
                switch ($_GET['msg']) {
                    case 'chegada_registrada': echo 'Chegada registrada com sucesso!'; break;
                    case 'amostra_liberada': echo 'Amostra liberada com sucesso!'; break;
                    case 'parametros_reprovados': echo 'Parâmetros fora do padrão!'; break;
                    case 'relatorio_gerado': echo 'Relatório gerado com sucesso!'; break;
                    case 'erro_chegada': echo 'Erro ao registrar chegada.'; break;
                    case 'erro_sensor': echo 'Erro ao analisar sensores.'; break;
                    case 'erro_coleta': echo 'Erro ao coletar amostra.'; break;
                }
                ?>
            </div>
        <?php endif; ?>

        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <span class="navbar-brand">
                    <i class="fas fa-bars"></i>
                    Sistema de Coleta de Leite
                </span>
                <div class="d-flex">
                    <span class="navbar-text">
                        Bem-vindo, <strong><?php echo htmlspecialchars($usuario_nome); ?></strong>!
                    </span>
                </div>
            </div>
        </nav>

        <div class="card">
            <div class="card-body">
                <?php
                if (isset($_GET['pagina'])) {
                    $pagina = $_GET['pagina'];
                    // Sistema de roteamento completo
                    switch ($pagina) {
                        case 'listar_motoristas': include 'motorista/listar_motoristas.php'; break;
                        case 'cadastrar_motorista': include 'motorista/cadastrar_motorista.php'; break;
                        case 'editar_motorista': include 'motorista/editar_motorista.php'; break;
                        case 'excluir_motorista': include 'motorista/excluir_motorista.php'; break;
                        case 'listar_caminhao': include 'caminhao/listar_caminhao.php'; break;
                        case 'cadastrar_caminhao': include 'caminhao/cadastrar_caminhao.php'; break;
                        case 'editar_caminhao': include 'caminhao/editar_caminhao.php'; break;
                        case 'excluir_caminhao': include 'caminhao/excluir_caminhao.php'; break;
                        case 'listar_usuarios': include 'usuario/listar_usuarios.php'; break;
                        case 'cadastrar_usuario': include 'usuario/cadastrar_usuario.php'; break;
                        case 'editar_usuario': include 'usuario/editar_usuario.php'; break;
                        case 'listar_coleta': include 'coleta/listar_coleta.php'; break;
                        case 'cadastrar_coleta': include 'coleta/cadastrar_coleta.php'; break;
                        case 'editar_coleta': include 'coleta/editar_coleta.php'; break;
                        case 'excluir_coleta': include 'coleta/excluir_coleta.php'; break;
                        case 'coletar_amostra': include 'coleta/coletar_amostra.php'; break;
                        case 'listar_amostra': include 'amostra/listar_amostra.php'; break;
                        case 'cadastrar_amostra': include 'amostra/cadastrar_amostra.php'; break;
                        case 'editar_amostra': include 'amostra/editar_amostra.php'; break;
                        case 'excluir_amostra': include 'amostra/excluir_amostra.php'; break;
                        case 'sensor': include 'sensor/simulador_sensor.php'; break;
                        case 'simulador': include 'sensor/simulador_sensor.php'; break;
                        case 'monitoramento_coleta': include 'sensor/monitoramento_coleta.php'; break;
                        case 'relatorios': include 'relatorios/indexRelatorios.php'; break;
                        case 'relatorioData': include 'relatorios/relatorioData.php'; break;
                        case 'relatorioPorCaminhao': include 'relatorios/relatorioPorCaminhao.php'; break;
                        case 'relatorioPorMotorista': include 'relatorios/relatorioPorMotorista.php'; break;
                        default:
                            echo "<h2><i class='fas fa-exclamation-triangle'></i> Página não encontrada</h2><p>O conteúdo que você procura não foi encontrado.</p>";
                            break;
                    }
                } else {
                    echo "<h2><i class='fas fa-tachometer-alt'></i> Painel Principal</h2>";
                    echo "<p class='lead'>Bem-vindo ao Sistema de Gerenciamento de Coleta de Leite.</p><hr>";
                    $hub_items = [
                        'listar_motoristas' => ['nome' => 'Motoristas', 'icone' => 'fa-id-card'],
                        'listar_caminhao'   => ['nome' => 'Caminhões', 'icone' => 'fa-truck'],
                        'listar_usuarios'   => ['nome' => 'Usuários', 'icone' => 'fa-users'],
                        'listar_coleta'     => ['nome' => 'Coletas', 'icone' => 'fa-box'],
                        'listar_amostra'    => ['nome' => 'Amostras', 'icone' => 'fa-vial'],
                        'sensor'            => ['nome' => 'Sensor', 'icone' => 'fa-satellite-dish'],
                        'relatorios'        => ['nome' => 'Relatórios', 'icone' => 'fa-chart-bar'],
                    ];
                    echo '<div class="row text-center">';
                    foreach ($hub_items as $key => $item) {
                        echo '<div class="col-md-4 col-lg-3 mb-4">';
                        echo '  <a href="dashboard.php?pagina=' . $key . '" class="card hub-card text-decoration-none h-100">';
                        echo '    <div class="card-body d-flex flex-column justify-content-center align-items-center">';
                        echo '      <i class="fas ' . $item['icone'] . ' fa-3x mb-3 text-primary"></i>';
                        echo '      <h5 class="card-title mb-0">' . $item['nome'] . '</h5>';
                        echo '    </div>';
                        echo '  </a>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 