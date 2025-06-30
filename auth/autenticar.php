<?php
session_start();
include_once("../classes/Conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        $conn = Conexao::getConexao();

        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($senha, $usuario['senha_usuario'])) {
                $_SESSION['usuario_logado'] = $usuario['nome_usuario'];
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                header("Location: ../index.php");
                exit;
            }
        }

        echo "E-mail ou senha inválidos.<br><a href='login.php'>Voltar</a>";

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "Acesso inválido.";
}
?>