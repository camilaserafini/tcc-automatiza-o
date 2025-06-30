<?php
use PHPUnit\Framework\TestCase;

class MotoristaTest extends TestCase
{
    private $motorista;

    protected function setUp(): void
    {
        $this->motorista = new Motorista();
    }

    public function testConstrutorComNome()
    {
        $nome = "João Silva";
        $motorista = new Motorista($nome);
        
        $this->assertEquals($nome, $motorista->getNomeMotorista());
    }

    public function testConstrutorSemNome()
    {
        $motorista = new Motorista();
        
        $this->assertNull($motorista->getNomeMotorista());
    }

    public function testSetAndGetIdMotorista()
    {
        $id = 1;
        $this->motorista->setIdMotorista($id);
        
        $this->assertEquals($id, $this->motorista->getIdMotorista());
    }

    public function testSetAndGetNomeMotorista()
    {
        $nome = "Maria Santos";
        $this->motorista->setNomeMotorista($nome);
        
        $this->assertEquals($nome, $this->motorista->getNomeMotorista());
    }

    public function testSetNomeVazio()
    {
        $this->motorista->setNomeMotorista("");
        
        $this->assertEquals("", $this->motorista->getNomeMotorista());
    }

    public function testSetNomeNull()
    {
        $this->motorista->setNomeMotorista(null);
        
        $this->assertNull($this->motorista->getNomeMotorista());
    }

    public function testIdMotoristaInicialNull()
    {
        $this->assertNull($this->motorista->getIdMotorista());
    }

    public function testSalvarMotorista()
    {
        $this->motorista->setNomeMotorista("Pedro Oliveira");
        
        // Mock da conexão para teste
        $this->markTestSkipped('Teste de banco de dados requer configuração específica');
    }

    public function testBuscarPorId()
    {
        $this->markTestSkipped('Teste de banco de dados requer configuração específica');
    }

    public function testAtualizarMotorista()
    {
        $this->markTestSkipped('Teste de banco de dados requer configuração específica');
    }
}
?> 