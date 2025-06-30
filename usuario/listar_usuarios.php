<?php
// include_once __DIR__ . '/../auth/configurarLogin.php'; // Removido pois não existe
include_once __DIR__ . '/../includes/menu.php';
include_once __DIR__ . '/../classes/Conexao.php';

try {
    $conn = Conexao::getConexao();
    $sql = "SELECT * FROM usuario ORDER BY id_usuario DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . htmlspecialchars($e->getMessage()));
}
?>

<div class="content">
    <h2>Usuários Cadastrados</h2>

    <?php if (count($usuarios) > 0): ?>
        <table class="tabela" border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Usuário</th>
                    <th>Nível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['id_usuario'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome_usuario'] ?? ''); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($usuario['nivel'] ?? '')); ?></td>
                        <td>
                            <a href="editar.php?id_usuario=<?php echo urlencode($usuario['id_usuario'] ?? ''); ?>" aria-label="Editar usuário <?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>">✏️ Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum usuário cadastrado.</p>
    <?php endif; ?>

    <p><a href="cadastrar.php" class="btn">➕ Cadastrar novo usuário</a></p>
</div>