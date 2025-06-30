<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Doca de Análise Automatizada</title>
    <link rel="stylesheet" href="../bitnami.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php
// Busca os dados necessários para os menus de seleção
require_once __DIR__ . '/../classes/Conexao.php';
$pdo = Conexao::getConexao();
$motoristas = $pdo->query("SELECT id_motorista, nome_motorista FROM motorista ORDER BY nome_motorista")->fetchAll(PDO::FETCH_ASSOC);
$caminhoes = $pdo->query("SELECT id_caminhao, placa FROM caminhao ORDER BY placa")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .tank-card { border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; background-color: #f9f9f9; }
    .tank-header { background-color: #e9ecef; padding: 10px 15px; border-bottom: 1px solid #ddd; font-weight: bold; }
    .tank-body { padding: 15px; }
    .loading-spinner { display: none; margin: 20px auto; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    #resultsContainer, #saveButtonContainer { display: none; }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-industry"></i> Doca de Análise Automatizada</h3></div>
        <div class="card-body">
            <form id="formAnalise" action="sensor/salvar_simulacao.php" method="POST">
                <div class="row">
                    <div class="col-md-3"><label for="id_motorista" class="form-label">Motorista:</label><select id="id_motorista" name="id_motorista" class="form-select" required><option value="">Selecione...</option><?php foreach ($motoristas as $m):?><option value="<?=$m['id_motorista']?>"><?=htmlspecialchars($m['nome_motorista'])?></option><?php endforeach;?></select></div>
                    <div class="col-md-3"><label for="id_caminhao" class="form-label">Caminhão:</label><select id="id_caminhao" name="id_caminhao" class="form-select" required><option value="">Selecione...</option><?php foreach ($caminhoes as $c):?><option value="<?=$c['id_caminhao']?>"><?=htmlspecialchars($c['placa'])?></option><?php endforeach;?></select></div>
                    <div class="col-md-3"><label for="volume" class="form-label">Volume Total (Litros):</label><input type="number" id="volume" name="volume" class="form-control" required></div>
                    <div class="col-md-3 d-flex align-items-end"><button type="button" id="btnAnalisar" class="btn btn-primary btn-lg w-100"><i class="fas fa-play"></i> Iniciar Análise</button></div>
                </div>
                <!-- Bloco da válvula de amostragem -->
                <?php
                require_once __DIR__ . '/../classes/ValvulaAmostragem.php';
                // Usar o id do caminhão como id_tanque para exemplo
                $id_tanque = isset($_POST['id_caminhao']) ? (int)$_POST['id_caminhao'] : 1;
                $status_valvula = ValvulaAmostragem::getStatus($id_tanque);
                ?>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="card p-3">
                            <strong>Status da Válvula:</strong> <span class="badge <?= $status_valvula === 'Aberta' ? 'bg-success' : 'bg-secondary' ?>"><?= $status_valvula ?></span>
                            <form method="POST" action="sensor/acao_valvula.php" class="d-inline">
                                <input type="hidden" name="id_tanque" value="<?= $id_tanque ?>">
                                <?php if ($status_valvula === 'Fechada'): ?>
                                    <input type="hidden" name="acao" value="Abrir">
                                    <button type="submit" class="btn btn-success ms-2">Abrir Válvula</button>
                                <?php else: ?>
                                    <input type="hidden" name="acao" value="Fechar">
                                    <button type="submit" class="btn btn-danger ms-2">Fechar Válvula</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Hidden fields for sample data will be populated by JS -->
                <div id="hidden-inputs-container"></div>
            </form>
            <!-- Botões principais sempre visíveis -->
            <div class="mt-4 mb-3 text-center">
                <form action="../sistema/analisar_sensores.php" method="post" style="display:inline-block;">
                    <input type="hidden" name="id_tanque" value="<?= $id_tanque ?>">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-microscope"></i> Analisar e Liberar Amostra
                    </button>
                </form>
                <form id="formColetarAmostra" action="../tecnico/coletar_amostra.php" method="post" style="display:inline-block; margin-left:10px;">
                    <input type="hidden" name="id_tanque" value="<?= $id_tanque ?>">
                    <input type="hidden" name="id_amostra" value="1">
                    <button type="submit" class="btn btn-info btn-lg">
                        <i class="fas fa-vial"></i> Coletar Amostra pela Válvula
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="loading-spinner" id="loader" style="display:none;"></div>
    <div id="analisandoMsg" class="text-center mt-4" style="display:none; font-size:1.3em; color:#2451a6;">
        <i class="fas fa-spinner fa-spin"></i> Analisando... Aguarde
    </div>

    <div id="resultsContainer" class="mt-4">
        <h4><i class="fas fa-clipboard-check"></i> Resultados da Análise</h4>
        <div class="row">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="col-md-3">
                    <div class="tank-card">
                        <div class="tank-header">Tanque <?=$i?></div>
                        <div class="tank-body">
                            <p><strong>Temp:</strong> <span id="res_temp_<?=$i?>">-</span> °C</p>
                            <p><strong>pH:</strong> <span id="res_ph_<?=$i?>">-</span></p>
                            <p><strong>Gordura:</strong> <span id="res_gordura_<?=$i?>">-</span> %</p>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        <div id="saveButtonContainer" class="text-center mt-3">
            <form id="formFecharValvula" action="sensor/acao_valvula.php" method="POST" style="display:none;">
                <input type="hidden" name="id_tanque" value="<?= $id_tanque ?>">
                <input type="hidden" name="acao" value="Fechar">
            </form>
        </div>
    </div>

    <div id="monitoramento" style="display:none;">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-broadcast-tower"></i> Monitoramento da Coleta em Tempo Real</div>
            <div class="card-body">
                <div class="mb-2">
                    <label>Progresso da Análise:</label>
                    <div class="progress">
                        <div id="barraProgresso" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">0%</div>
                    </div>
                </div>
                <div class="mb-2">
                    <strong>Status:</strong> <span id="statusAnalise">Aguardando início...</span>
                </div>
                <div>
                    <strong>Log de Eventos:</strong>
                    <pre id="logEventos" style="background:#111;color:#0f0;padding:10px;height:120px;overflow:auto;font-size:1em;">[--:--:--] Pronto para iniciar análise.
</pre>
                </div>
            </div>
        </div>
    </div>

    <div id="resultadoAnalise" class="mt-4" style="display:none;"></div>
    <div id="infoAnalise" class="mb-3" style="display:none;"></div>
</div>

<script>
function getHoraAgora() {
    const d = new Date();
    return '[' + d.toTimeString().substr(0,8) + ']';
}

const btnAnalisar = document.getElementById('btnAnalisar');
const btnLiberar = document.createElement('button');
btnLiberar.type = 'button';
btnLiberar.className = 'btn btn-success btn-lg';
btnLiberar.innerHTML = '<i class="fas fa-vial"></i> Coletar Amostra pela Válvula';
btnLiberar.style.marginLeft = '10px';
btnLiberar.onclick = function() {
    alert('Amostra coletada! (Simulação)');
};

btnAnalisar.addEventListener('click', function() {
    if (!document.getElementById('id_motorista').value || !document.getElementById('id_caminhao').value || !document.getElementById('volume').value) {
        alert('Por favor, preencha todos os dados antes de iniciar a análise.');
        return;
    }
    document.getElementById('monitoramento').style.display = 'block';
    document.getElementById('resultsContainer').style.display = 'none';
    document.getElementById('saveButtonContainer').style.display = 'none';
    document.getElementById('analisandoMsg').style.display = 'none';
    document.getElementById('resultadoAnalise').style.display = 'none';
    let barra = document.getElementById('barraProgresso');
    let status = document.getElementById('statusAnalise');
    let log = document.getElementById('logEventos');
    barra.style.width = '0%'; barra.textContent = '0%';
    status.textContent = 'Iniciando conexão com os sensores...';
    log.textContent = getHoraAgora() + ' Iniciando conexão com os sensores...\n';
    let progresso = 0;
    let tanqueAtual = 1;
    let resultados = [];
    function analisarTanque() {
        if (tanqueAtual > 4) {
            barra.style.width = '100%'; barra.textContent = '100%';
            status.textContent = 'Análise concluída!';
            setTimeout(() => {
                document.getElementById('monitoramento').style.display = 'none';
                document.getElementById('resultsContainer').style.display = 'block';
                document.getElementById('saveButtonContainer').style.display = 'block';
                mostrarResultadoAnalise(resultados);
            }, 800);
            return;
        }
        progresso = tanqueAtual * 25;
        barra.style.width = progresso + '%'; barra.textContent = progresso + '%';
        status.textContent = 'Analisando Tanque ' + tanqueAtual + '...';
        log.textContent += getHoraAgora() + ' Analisando Tanque ' + tanqueAtual + '...\n';
        setTimeout(() => {
            // Simula resultado
            const temp = (3.5 + Math.random() * 2).toFixed(1);
            const ph = (6.5 + Math.random() * 0.3).toFixed(1);
            const gordura = (3.2 + Math.random() * 1.1).toFixed(1);
            document.getElementById(`res_temp_${tanqueAtual}`).textContent = temp;
            document.getElementById(`res_ph_${tanqueAtual}`).textContent = ph;
            document.getElementById(`res_gordura_${tanqueAtual}`).textContent = gordura;
            log.textContent += getHoraAgora() + ` -> Resultado Tanque ${tanqueAtual}: Temp ${temp}°C, pH ${ph}, Gordura ${gordura}%\n`;
            // Lógica de aprovação/reprovação
            let aprovado = true;
            let motivo = [];
            if (gordura > 4.0) { aprovado = false; motivo.push('Gordura acima do limite (4.0%)'); }
            if (ph < 6.6 || ph > 6.9) { aprovado = false; motivo.push('pH fora do padrão (6.6 - 6.9)'); }
            if (temp > 7.0) { aprovado = false; motivo.push('Temperatura acima do limite (7.0°C)'); }
            resultados.push({tanque: tanqueAtual, aprovado, motivo, temp, ph, gordura});
            tanqueAtual++;
            analisarTanque();
        }, 700);
    }
    analisarTanque();
});

function mostrarResultadoAnalise(resultados) {
    let div = document.getElementById('resultadoAnalise');
    let info = document.getElementById('infoAnalise');
    let motoristaSel = document.getElementById('id_motorista');
    let caminhaoSel = document.getElementById('id_caminhao');
    let motoristaNome = motoristaSel.options[motoristaSel.selectedIndex].text;
    let caminhaoNome = caminhaoSel.options[caminhaoSel.selectedIndex].text;
    info.innerHTML = `<strong>Caminhão:</strong> ${caminhaoNome} &nbsp; | &nbsp; <strong>Motorista:</strong> ${motoristaNome}`;
    info.style.display = 'block';
    let html = '<h4>Resultado da Análise</h4>';
    let todosAprovados = true;
    html += '<div class="row">';
    resultados.forEach(r => {
        html += `<div class="col-md-3"><div class="tank-card" style="border:2px solid ${r.aprovado ? '#28a745' : '#dc3545'};background:${r.aprovado ? '#eafaf1' : '#f8d7da'};">`;
        html += `<div class="tank-header">Tanque ${r.tanque}</div><div class="tank-body">`;
        html += `<p><strong>Temp:</strong> ${r.temp} °C</p><p><strong>pH:</strong> ${r.ph}</p><p><strong>Gordura:</strong> ${r.gordura} %</p>`;
        if (r.aprovado) {
            html += '<span class="badge bg-success">Aprovado</span>';
        } else {
            todosAprovados = false;
            html += '<span class="badge bg-danger">Reprovado</span><br><small><b>Motivo:</b> ' + r.motivo.join('; ') + '</small>';
        }
        html += '</div></div></div>';
    });
    html += '</div>';
    // Status geral
    if (todosAprovados) {
        html += '<div class="alert alert-success mt-3"><strong>Status Geral: Aprovado</strong></div>';
        document.getElementById('saveButtonContainer').style.display = 'block';
        // Atualiza botão de coleta
        let btnColetar = document.getElementById('btnColetar');
        if (btnColetar) {
            btnColetar.innerHTML = '<i class="fa fa-flask"></i> Coletar Amostra <span class="badge bg-success ms-2">Aprovado</span>';
            btnColetar.disabled = false;
        }
    } else {
        html += '<div class="alert alert-danger mt-3"><strong>Status Geral: Reprovado</strong></div>';
        document.getElementById('saveButtonContainer').style.display = 'none';
        // Atualiza botão de coleta
        let btnColetar = document.getElementById('btnColetar');
        if (btnColetar) {
            btnColetar.innerHTML = '<i class="fa fa-flask"></i> Coletar Amostra <span class="badge bg-danger ms-2">Reprovado</span>';
            btnColetar.disabled = true;
        }
    }
    div.innerHTML = html;
    div.style.display = 'block';
}

const formColetar = document.getElementById('formColetarAmostra');
const formFechar = document.getElementById('formFecharValvula');
if (formColetar && formFechar) {
    formColetar.addEventListener('submit', function(e) {
        setTimeout(function() {
            formFechar.submit();
        }, 500);
    });
}

document.getElementById('id_caminhao').addEventListener('change', function() {
    var idCaminhao = this.value;
    // Atualiza todos os campos hidden de id_tanque
    document.querySelectorAll('input[name="id_tanque"]').forEach(function(input) {
        input.value = idCaminhao;
    });
});
</script>
</body>
</html>