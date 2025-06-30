<div class="sidebar">
    <h3>Coleta de Leite</h3>
    <a href="/index.php"><i class="fas fa-home"></i> Painel Principal</a>
    <a href="/motorista/listar_motoristas.php"><i class="fas fa-id-card"></i> Motoristas</a>
    <a href="/caminhao/listar_caminhao.php"><i class="fas fa-truck"></i> Caminhões</a>
    <a href="/usuario/listar_usuarios.php"><i class="fas fa-user"></i> Usuários</a>
    <a href="/coleta/listar_coleta.php"><i class="fas fa-clipboard-list"></i> Coletas</a>
    <a href="/amostra/listar_amostra.php"><i class="fas fa-vial"></i> Amostras</a>
    <a href="/relatorios/relatorioColetas.php"><i class="fas fa-chart-bar"></i> Relatórios</a>
    <hr>
    <a href="/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
</div>

<style>
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
    .sidebar a:hover {
        background-color: #0b5ed7;
    }
    .content {
        margin-left: 220px;
        padding: 20px;
    }
</style>

<!-- Bootstrap + Font Awesome (só uma vez por página) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
