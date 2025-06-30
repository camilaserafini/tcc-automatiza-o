<?php
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
    private $usuario;

    protected function setUp(): void
    {
        $this->usuario = new Usuario();
    }

    public function testConstrutorComLoginESenha()
    {
        $login = "admin";
        $senha = "123456";
        $usuario = new Usuario($login, $senha);

        $this->assertEquals($login, $usuario->getLogin());
        $this->assertEquals($senha, $usuario->getSenha());
    }

    public function testConstrutorSemParametros()
    {
        $usuario = new Usuario();

        $this->assertNull($usuario->getLogin());
        $this->assertNull($usuario->getSenha());
    }

    public function testSetAndGetIdUsuario()
    {
        $id = 1;
        $this->usuario->setIdUsuario($id);

        $this->assertEquals($id, $this->usuario->getIdUsuario());
    }

    public function testSetAndGetLogin()
    {
        $login = "usuario_teste";
        $this->usuario->setLogin($login);

        $this->assertEquals($login, $this->usuario->getLogin());
    }

    public function testSetAndGetSenha()
    {
        $senha = "senha_segura_123";
        $this->usuario->setSenha($senha);

        $this->assertEquals($senha, $this->usuario->getSenha());
    }

    public function testSetLoginVazio()
    {
        $this->usuario->setLogin("");
        $this->assertEquals("", $this->usuario->getLogin());
    }

    public function testSetSenhaVazia()
    {
        $this->usuario->setSenha("");
        $this->assertEquals("", $this->usuario->getSenha());
    }

    public function testSetLoginNull()
    {
        $this->usuario->setLogin(null);
        $this->assertNull($this->usuario->getLogin());
    }

    public function testSetSenhaNull()
    {
        $this->usuario->setSenha(null);
        $this->assertNull($this->usuario->getSenha());
    }

    public function testIdUsuarioInicialNull()
    {
        $this->assertNull($this->usuario->getIdUsuario());
    }

    public function testLoginComCaracteresEspeciais()
    {
        $login = "user@domain.com";
        $this->usuario->setLogin($login);
        $this->assertEquals($login, $this->usuario->getLogin());
    }

    public function testSenhaComCaracteresEspeciais()
    {
        $senha = "P@ssw0rd!";
        $this->usuario->setSenha($senha);
        $this->assertEquals($senha, $this->usuario->getSenha());
    }
}
?> 