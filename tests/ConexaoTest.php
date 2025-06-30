<?php
use PHPUnit\Framework\TestCase;

class ConexaoTest extends TestCase
{
    public function testGetConexaoRetornaPDO()
    {
        $conexao = Conexao::getConexao();
        
        $this->assertInstanceOf(PDO::class, $conexao);
    }

    public function testGetConexaoRetornaMesmaInstancia()
    {
        $conexao1 = Conexao::getConexao();
        $conexao2 = Conexao::getConexao();
        
        $this->assertSame($conexao1, $conexao2);
    }

    public function testConexaoTemConfiguracaoCorreta()
    {
        $conexao = Conexao::getConexao();
        
        // Verifica se a conexão está configurada para lançar exceções
        $this->assertEquals(PDO::ERRMODE_EXCEPTION, $conexao->getAttribute(PDO::ATTR_ERRMODE));
    }

    public function testConexaoPodeExecutarQuery()
    {
        $conexao = Conexao::getConexao();
        
        // Testa se consegue executar uma query simples
        $stmt = $conexao->query("SELECT 1");
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertEquals(['1' => '1'], $resultado);
    }

    public function testConexaoSingleton()
    {
        // Limpa a instância estática (se possível)
        $reflection = new ReflectionClass('Conexao');
        $property = $reflection->getProperty('instancia');
        $property->setAccessible(true);
        $property->setValue(null, null);
        
        $conexao1 = Conexao::getConexao();
        $conexao2 = Conexao::getConexao();
        
        $this->assertSame($conexao1, $conexao2);
    }
}
?> 