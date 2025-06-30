<?php
// O 'verifica_login.php' já é chamado pelo dashboard, então não é necessário aqui.
// Esta página apenas exibe os links para os relatórios.
?>

<div class="container-fluid">
    <h2><i class="fas fa-chart-bar"></i> Relatórios de Coletas</h2>
    <hr>
    <div class="list-group">
        <!-- Links corrigidos para o sistema de dashboard -->
        <a href="dashboard.php?pagina=relatorioData" class="list-group-item list-group-item-action">
            <i class="fas fa-calendar-alt"></i> Relatório por Período
        </a>
        <a href="dashboard.php?pagina=relatorioPorCaminhao" class="list-group-item list-group-item-action">
            <i class="fas fa-truck"></i> Relatório por Caminhão
        </a>
        <a href="dashboard.php?pagina=relatorioPorMotorista" class="list-group-item list-group-item-action">
            <i class="fas fa-id-card"></i> Relatório por Motorista
        </a>
    </div>
</div>
