<?php
// include_once 'auth/configurarLogin.php';
// Verificar se a sessão já foi iniciada antes de tentar iniciar novamente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nivel = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : '';
?>

<ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link text-white" href="dashboard.php"><i class="fas fa-home"></i> Início</a></li>
    <?php if ($nivel === 'admin' || $nivel === 'user' || $nivel === 'sistema'): ?>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=listar_motoristas"><i class="fas fa-id-card"></i> Motoristas</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=listar_caminhao"><i class="fas fa-truck"></i> Caminhões</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=listar_coleta"><i class="fas fa-box"></i> Coletas</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=listar_amostra"><i class="fas fa-vial"></i> Amostras</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=relatorios"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=sensor"><i class="fas fa-satellite-dish"></i> Sensor</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=listar_usuarios"><i class="fas fa-users"></i> Usuários</a></li>
    <?php endif; ?>
    <?php if ($nivel === 'tecnico'): ?>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=listar_amostra"><i class="fas fa-vial"></i> Amostras</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=relatorios"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
    <?php endif; ?>
    <?php if ($nivel === 'motorista'): ?>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php?pagina=listar_coleta"><i class="fas fa-box"></i> Minhas Coletas</a></li>
    <?php endif; ?>
    <li class="nav-item mt-3"><a class="nav-link text-white bg-danger rounded" href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
</ul>