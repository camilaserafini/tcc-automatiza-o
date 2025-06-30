<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: /login.php?erro=2');
    exit;
}
// Definir página padrão
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
// Mapear páginas para includes
$pages = [
    'motoristas' => 'motorista/listar_motoristas.php',
    'caminhoes' => 'caminhao/listar_caminhao.php',
    'usuarios' => 'usuario/listar_usuarios.php',
    'coletas' => 'coleta/listar_coleta.php',
    'amostras' => 'amostra/listar_amostra.php',
    'relatorios' => 'relatorios/indexRelatorios.php',
    'home' => null
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema de Coleta de Leite</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body { overflow-x: hidden; }
        .sidebar {
            height: 100vh;
            background-color: #0d6efd;
            color: white;
            position: fixed;
            width: 220px;
            padding-top: 20px;
            z-index: 1000;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            transition: background 0.3s;
        }
        .sidebar a.active, .sidebar a:hover {
            background-color: #0b5ed7;
        }
        .content {
            margin-left: 220px;
            padding: 30px 30px 30px 30px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .topbar {
            margin-left: 220px;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #e3e3e3;
            display: flex;
            align-items: center;
            padding: 0 30px;
            justify-content: space-between;
        }
        .username {
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Coleta de Leite</h3>
        <a href="dashboard.php?page=home" class="<?= $page=='home'?'active':'' ?>"><i class="fas fa-home"></i> Painel Principal</a>
        <a href="dashboard.php?page=motoristas" class="<?= $page=='motoristas'?'active':'' ?>"><i class="fas fa-id-card"></i> Motoristas</a>
        <a href="dashboard.php?page=caminhoes" class="<?= $page=='caminhoes'?'active':'' ?>"><i class="fas fa-truck"></i> Caminhões</a>
        <a href="dashboard.php?page=usuarios" class="<?= $page=='usuarios'?'active':'' ?>"><i class="fas fa-user"></i> Usuários</a>
        <a href="dashboard.php?page=coletas" class="<?= $page=='coletas'?'active':'' ?>"><i class="fas fa-clipboard-list"></i> Coletas</a>
        <a href="dashboard.php?page=amostras" class="<?= $page=='amostras'?'active':'' ?>"><i class="fas fa-vial"></i> Amostras</a>
        <a href="dashboard.php?page=relatorios" class="<?= $page=='relatorios'?'active':'' ?>"><i class="fas fa-chart-bar"></i> Relatórios</a>
        <hr>
        <a href="/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
    <!-- Topbar -->
    <div class="topbar">
        <span><i class="fas fa-tachometer-alt"></i> Sistema de Coleta de Leite</span>
        <span class="username"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['login']) ?></span>
    </div>
    <!-- Main Content -->
    <div class="content">
        <?php
        if ($page === 'home') {
            echo '<h2><i class="fas fa-home"></i> Painel Principal</h2>';
            echo '<p class="lead">Bem-vindo ao Sistema de Gerenciamento de Coleta de Leite.</p><hr>';
            
            echo '<h4><i class="fas fa-satellite-dish"></i> Simulador de Nível do Tanque</h4>';
            include 'simulador/simulador_sensor.php';

        } elseif (isset($pages[$page]) && $pages[$page]) {
            include $pages[$page];
        } else {
            echo '<div class="alert alert-danger">Página não encontrada.</div>';
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 