<?php
// Habilitar exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Se o usuário já estiver logado, redireciona para o dashboard
if (isset($_SESSION['id_usuario'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = $_GET['erro'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Coleta de Leite</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .login-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .login-header h3 {
            margin: 0;
            padding: 20px;
            font-weight: bold;
        }
        .login-icon {
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="card-header text-center login-header">
                <h3><i class="fas fa-truck-moving"></i> Sistema de Coleta</h3>
            </div>
            <div class="card-body p-4">

                <?php if ($erro == 1): ?>
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle"></i> Usuário ou senha inválidos!
                    </div>
                <?php elseif ($erro == 2): ?>
                     <div class="alert alert-warning text-center">
                        <i class="fas fa-shield-alt"></i> Faça login para acessar o sistema.
                    </div>
                <?php endif; ?>

                <form action="auth/autentica.php" method="POST">
                    <div class="form-group">
                        <label for="login"><i class="fas fa-user"></i> E-mail</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="form-group">
                        <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">
                        <i class="fas fa-sign-in-alt"></i> Entrar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 