<?php
include_once("classes/Conexao.php"); // Ajuste o caminho caso precise

try {
    $conn = Conexao::getConexao();

    $nome = "Admin";
    $email = "admin@exemplo.com";
    $senha = password_hash("123456", PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    echo "Usuário admin criado com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao criar usuário: " . $e->getMessage();
}