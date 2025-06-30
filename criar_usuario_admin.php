<?php
include_once("classes/Conexao.php");

try {
    $conn = Conexao::getConexao();
    
    $nome = "Administrador";
    $email = "admin@admin.com";
    $senha = "admin123"; // Esta será a senha para login
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuario (nome_usuario, email, senha_usuario) VALUES (:nome, :email, :senha)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    
    if ($stmt->execute()) {
        echo "Usuário admin criado com sucesso!<br>";
        echo "Email: admin@admin.com<br>";
        echo "Senha: admin123";
    } else {
        echo "Erro ao criar usuário.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?> 