<?php
session_start();
require_once '../classes/Conexao.php';
require_once '../classes/Usuario.php';

// Verifica se os dados foram enviados
if (isset($_POST['login']) && isset($_POST['senha'])) {
    $login = $_POST['login']; // Aqui o campo do formulário é 'login', mas representa o email
    $senha = $_POST['senha'];

    try {
        $pdo = Conexao::getConexao();
        // Busca o usuário pelo email
        $stmt = $pdo->prepare("SELECT id_usuario, email, nome_usuario, senha_usuario, nivel FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $login);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica a senha (aceita texto puro ou hash)
        if ($usuario && ($usuario['senha_usuario'] === $senha || password_verify($senha, $usuario['senha_usuario']))) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
            $_SESSION['nivel'] = $usuario['nivel'];
            header('Location: ../dashboard.php');
            exit;
        } else {
            header('Location: ../login.php?erro=1');
            exit;
        }
    } catch (PDOException $e) {
        die("Erro ao autenticar: " . $e->getMessage());
    }
} else {
    header('Location: ../login.php');
    exit;
}
?> 