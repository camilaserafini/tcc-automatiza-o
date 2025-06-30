<?php
include_once("../classes/Conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $senha = isset($_POST['senha']) && !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;
    $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : null;

    try {
        $conn = Conexao::getConexao();

        if ($id_usuario) {
            // Atualizar usuário
            if ($senha) {
                // Atualiza com nova senha
                $sql = "UPDATE usuario SET nome_usuario = :nome, email = :email, senha = :senha WHERE id_usuario = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
            } else {
                // Atualiza sem alterar a senha
                $sql = "UPDATE usuario SET nome_usuario = :nome, email = :email WHERE id_usuario = :id";
                $stmt = $conn->prepare($sql);
            }

            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: listar.php");
                exit;
            } else {
                echo "Erro ao atualizar usuário.";
            }

        } else {
            // Inserir novo usuário - senha obrigatória no cadastro
            if (!$senha) {
                echo "Erro: A senha é obrigatória para cadastrar novo usuário.";
                exit;
            }

            $sql = "INSERT INTO usuario (nome_usuario, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: listar.php");
                exit;
            } else {
                echo "Erro ao cadastrar usuário.";
            }
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "Acesso inválido.";
}
?>
<br><a href="listar.php">Voltar para a listagem</a>