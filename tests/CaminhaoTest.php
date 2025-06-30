<?php
use PHPUnit\Framework\TestCase;

class CaminhaoTest extends TestCase
{
    private $caminhao;

    protected function setUp(): void
    {
        $this->caminhao = new Caminhao();
    }

    public function testConstrutorComPlaca()
    {
        $placa = "ABC-1234";
        $caminhao = new Caminhao($placa);
        
        $this->assertEquals($placa, $caminhao->getPlaca());
    }

    public function testConstrutorSemPlaca()
    {
        $caminhao = new Caminhao();
        
        $this->assertNull($caminhao->getPlaca());
    }

    public function testSetAndGetIdCaminhao()
    {
        $id = 1;
        $this->caminhao->setIdCaminhao($id);
        
        $this->assertEquals($id, $this->caminhao->getIdCaminhao());
    }

    public function testSetAndGetPlaca()
    {
        $placa = "XYZ-5678";
        $this->caminhao->setPlaca($placa);
        
        $this->assertEquals($placa, $this->caminhao->getPlaca());
    }

    public function testSetPlacaVazia()
    {
        $this->caminhao->setPlaca("");
        
        $this->assertEquals("", $this->caminhao->getPlaca());
    }

    public function testSetPlacaNull()
    {
        $this->caminhao->setPlaca(null);
        
        $this->assertNull($this->caminhao->getPlaca());
    }

    public function testIdCaminhaoInicialNull()
    {
        $this->assertNull($this->caminhao->getIdCaminhao());
    }
}
?> 