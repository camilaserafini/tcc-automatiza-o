<?php
require_once "classes/Conexao.php";
$conn = Conexao::getConexao();

$sql = "SELECT * FROM amostra";
$stmt = $conn->query($sql);
$amostras = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($amostras as $amostra) {
    $status = 'Aprovado';
    $motivo = [];

    if ($amostra['gordura'] > 4.0) {
        $status = 'Reprovado';
        $motivo[] = 'Gordura acima do limite permitido';
    }
    if ($amostra['ph'] < 6.6 || $amostra['ph'] > 6.9) {
        $status = 'Reprovado';
        $motivo[] = 'pH fora do padrão (6.6 - 6.9)';
    }
    if ($amostra['temperatura'] > 7.0) {
        $status = 'Reprovado';
        $motivo[] = 'Temperatura acima do limite permitido';
    }
    if ($amostra['proteina'] < 2.8) {
        $status = 'Reprovado';
        $motivo[] = 'Proteína abaixo do mínimo permitido (2.8)';
    }
    if ($amostra['contagem_celulas_somaticas'] > 500000) {
        $status = 'Reprovado';
        $motivo[] = 'Contagem de células acima do limite (500.000)';
    }
    $motivo_str = $status == 'Reprovado' ? implode('; ', $motivo) : null;

    $update = $conn->prepare("UPDATE amostra SET status = :status, motivo_recusa = :motivo WHERE id_amostra = :id");
    $update->bindParam(':status', $status);
    $update->bindParam(':motivo', $motivo_str);
    $update->bindParam(':id', $amostra['id_amostra']);
    $update->execute();
}
echo "Status das amostras atualizado!"; 