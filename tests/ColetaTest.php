<?php
use PHPUnit\Framework\TestCase;

class ColetaTest extends TestCase
{
    private $coleta;

    protected function setUp(): void
    {
        $this->coleta = new Coleta();
    }

    public function testConstrutorComTodosParametros()
    {
        $idCaminhao = 1;
        $idMotorista = 2;
        $dataHora = "2024-01-15 10:30:00";
        $volume = 1000.5;
        $temperatura = 4.2;
        $status = "Pendente";

        $coleta = new Coleta($idCaminhao, $idMotorista, $dataHora, $volume, $temperatura, $status);

        $this->assertEquals($idCaminhao, $coleta->getIdCaminhao());
        $this->assertEquals($idMotorista, $coleta->getIdMotorista());
        $this->assertEquals($dataHora, $coleta->getDataHoraChegada());
        $this->assertEquals($volume, $coleta->getVolume());
        $this->assertEquals($temperatura, $coleta->getTemperatura());
        $this->assertEquals($status, $coleta->getStatus());
    }

    public function testConstrutorSemParametros()
    {
        $coleta = new Coleta();

        $this->assertNull($coleta->getIdCaminhao());
        $this->assertNull($coleta->getIdMotorista());
        $this->assertNull($coleta->getDataHoraChegada());
        $this->assertNull($coleta->getVolume());
        $this->assertNull($coleta->getTemperatura());
        $this->assertNull($coleta->getStatus());
    }

    public function testSetAndGetIdColeta()
    {
        $id = 1;
        $this->coleta->setIdColeta($id);

        $this->assertEquals($id, $this->coleta->getIdColeta());
    }

    public function testSetAndGetIdCaminhao()
    {
        $id = 5;
        $this->coleta->setIdCaminhao($id);

        $this->assertEquals($id, $this->coleta->getIdCaminhao());
    }

    public function testSetAndGetIdMotorista()
    {
        $id = 10;
        $this->coleta->setIdMotorista($id);

        $this->assertEquals($id, $this->coleta->getIdMotorista());
    }

    public function testSetAndGetDataHoraChegada()
    {
        $dataHora = "2024-01-20 14:45:30";
        $this->coleta->setDataHoraChegada($dataHora);

        $this->assertEquals($dataHora, $this->coleta->getDataHoraChegada());
    }

    public function testSetAndGetVolume()
    {
        $volume = 1500.75;
        $this->coleta->setVolume($volume);

        $this->assertEquals($volume, $this->coleta->getVolume());
    }

    public function testSetAndGetTemperatura()
    {
        $temperatura = 3.8;
        $this->coleta->setTemperatura($temperatura);

        $this->assertEquals($temperatura, $this->coleta->getTemperatura());
    }

    public function testSetAndGetStatus()
    {
        $status = "Aprovado";
        $this->coleta->setStatus($status);

        $this->assertEquals($status, $this->coleta->getStatus());
    }

    public function testVolumeZero()
    {
        $this->coleta->setVolume(0);
        $this->assertEquals(0, $this->coleta->getVolume());
    }

    public function testTemperaturaNegativa()
    {
        $this->coleta->setTemperatura(-2.5);
        $this->assertEquals(-2.5, $this->coleta->getTemperatura());
    }

    public function testStatusVazio()
    {
        $this->coleta->setStatus("");
        $this->assertEquals("", $this->coleta->getStatus());
    }

    public function testIdColetaInicialNull()
    {
        $this->assertNull($this->coleta->getIdColeta());
    }
}
?> 