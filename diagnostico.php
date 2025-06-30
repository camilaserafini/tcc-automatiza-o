<?php
// Script de diagnóstico para identificar problemas no sistema
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Diagnóstico do Sistema</h2>";

// 1. Verificar versão do PHP
echo "<h3>1. Versão do PHP</h3>";
echo "Versão: " . phpversion() . "<br>";
echo "PDO MySQL disponível: " . (extension_loaded('pdo_mysql') ? 'Sim' : 'Não') . "<br>";
echo "Sessões disponíveis: " . (extension_loaded('session') ? 'Sim' : 'Não') . "<br>";

// 2. Verificar conexão com banco
echo "<h3>2. Teste de Conexão com Banco</h3>";
try {
    require_once 'classes/Conexao.php';
    $conexao = Conexao::getConexao();
    echo "✅ Conexão com banco de dados: OK<br>";
    
    // Testar se a tabela usuario existe
    $stmt = $conexao->query("SHOW TABLES LIKE 'usuario'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela 'usuario' existe<br>";
        
        // Contar usuários
        $stmt = $conexao->query("SELECT COUNT(*) as total FROM usuario");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total de usuários: " . $result['total'] . "<br>";
    } else {
        echo "❌ Tabela 'usuario' não encontrada<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
}

// 3. Verificar arquivos importantes
echo "<h3>3. Verificação de Arquivos</h3>";
$arquivos = [
    'classes/Conexao.php',
    'auth/autentica.php',
    'auth/verifica_login.php',
    'includes/menu.php',
    'dashboard.php',
    'login.php'
];

foreach ($arquivos as $arquivo) {
    if (file_exists($arquivo)) {
        echo "✅ $arquivo existe<br>";
    } else {
        echo "❌ $arquivo não encontrado<br>";
    }
}

// 4. Verificar permissões de sessão
echo "<h3>4. Teste de Sessão</h3>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Sessões funcionando<br>";
} else {
    echo "❌ Problema com sessões<br>";
}

// 5. Verificar se XAMPP está rodando
echo "<h3>5. Status do XAMPP</h3>";
$host = 'localhost';
$port = 3306;

$connection = @fsockopen($host, $port, $errno, $errstr, 5);
if (is_resource($connection)) {
    echo "✅ MySQL rodando na porta $port<br>";
    fclose($connection);
} else {
    echo "❌ MySQL não está rodando na porta $port<br>";
}

echo "<h3>Diagnóstico Concluído</h3>";
echo "<p>Se você ainda está tendo problemas, verifique:</p>";
echo "<ul>";
echo "<li>Se o XAMPP está rodando (Apache e MySQL)</li>";
echo "<li>Se o banco de dados 'coleta_leite' existe</li>";
echo "<li>Se a tabela 'usuario' existe e tem dados</li>";
echo "<li>Se há erros no log do Apache (xampp/apache/logs/error.log)</li>";
echo "</ul>";
?> 