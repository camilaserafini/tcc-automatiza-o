<?php
require_once __DIR__ . '/../classes/SistemaControle.php';
require_once __DIR__ . '/../classes/Conexao.php';
session_start();

$conn = Conexao::getConexao();
$id_usuario = $_SESSION['id_usuario'] ?? null;
$nivel = $_SESSION['nivel'] ?? '';
$motorista = null;
if ($id_usuario) {
    $stmt = $conn->prepare('SELECT id_motorista FROM motorista WHERE id_usuario = :id_usuario');
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $motorista = $row['id_motorista'];
    }
}
// Se não encontrou motorista, usa o padrão (ex: 1)
if ($motorista === null) {
    $motorista = 1; // Ajuste para o id_motorista que faz sentido no seu sistema
}
$caminhao = $_POST['id_caminhao'] ?? null;

if ($motorista !== null && $caminhao) {
    $sistema = new SistemaControle();
    $sistema->registrarChegada($motorista, $caminhao);
    header('Location: ../dashboard.php?pagina=listar_caminhao&msg=chegada_registrada');
    exit;
} else {
    header('Location: ../dashboard.php?pagina=listar_caminhao&msg=erro_chegada');
    exit;
}
?> 