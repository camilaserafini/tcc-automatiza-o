<?php
use PHPUnit\Framework\TestCase;

class AmostraTest extends TestCase
{
    private $amostra;

    protected function setUp(): void
    {
        $this->amostra = new Amostra();
    }

    public function testConstrutorComTodosParametros()
    {
        $idColeta = 1;
        $volume = 500.5;
        $temperatura = 4.0;

        $amostra = new Amostra($idColeta, $volume, $temperatura);

        $this->assertEquals($idColeta, $amostra->getIdColeta());
        $this->assertEquals($volume, $amostra->getVolume());
        $this->assertEquals($temperatura, $amostra->getTemperatura());
    }

    public function testConstrutorSemParametros()
    {
        $amostra = new Amostra();

        $this->assertNull($amostra->getIdColeta());
        $this->assertNull($amostra->getVolume());
        $this->assertNull($amostra->getTemperatura());
    }

    public function testSetAndGetIdAmostra()
    {
        $id = 1;
        $this->amostra->setIdAmostra($id);

        $this->assertEquals($id, $this->amostra->getIdAmostra());
    }

    public function testSetAndGetIdColeta()
    {
        $id = 5;
        $this->amostra->setIdColeta($id);

        $this->assertEquals($id, $this->amostra->getIdColeta());
    }

    public function testSetAndGetVolume()
    {
        $volume = 750.25;
        $this->amostra->setVolume($volume);

        $this->assertEquals($volume, $this->amostra->getVolume());
    }

    public function testSetAndGetTemperatura()
    {
        $temperatura = 3.5;
        $this->amostra->setTemperatura($temperatura);

        $this->assertEquals($temperatura, $this->amostra->getTemperatura());
    }

    public function testVolumeZero()
    {
        $this->amostra->setVolume(0);
        $this->assertEquals(0, $this->amostra->getVolume());
    }

    public function testTemperaturaNegativa()
    {
        $this->amostra->setTemperatura(-1.5);
        $this->assertEquals(-1.5, $this->amostra->getTemperatura());
    }

    public function testIdAmostraInicialNull()
    {
        $this->assertNull($this->amostra->getIdAmostra());
    }

    public function testVolumeDecimal()
    {
        $volume = 123.456;
        $this->amostra->setVolume($volume);
        $this->assertEquals($volume, $this->amostra->getVolume());
    }

    public function testTemperaturaDecimal()
    {
        $temperatura = 4.123;
        $this->amostra->setTemperatura($temperatura);
        $this->assertEquals($temperatura, $this->amostra->getTemperatura());
    }
}
?> 