# Testes Unitários - Sistema de Coleta de Leite

Este diretório contém os testes unitários para o sistema de coleta de leite.

## Estrutura dos Testes

- `CaminhaoTest.php` - Testes para a classe Caminhao
- `MotoristaTest.php` - Testes para a classe Motorista  
- `ColetaTest.php` - Testes para a classe Coleta
- `UsuarioTest.php` - Testes para a classe Usuario
- `AmostraTest.php` - Testes para a classe Amostra
- `ConexaoTest.php` - Testes para a classe Conexao

## Como Executar os Testes

### Pré-requisitos

1. PHP 7.4 ou superior
2. PHPUnit instalado
3. Banco de dados MySQL configurado

### Executando os Testes

#### Opção 1: Usando PHPUnit diretamente
```bash
# Executar todos os testes
php phpunit.phar

# Executar um teste específico
php phpunit.phar tests/CaminhaoTest.php

# Executar com cobertura de código
php phpunit.phar --coverage-html tests/coverage
```

#### Opção 2: Usando Composer (se configurado)
```bash
# Executar todos os testes
vendor/bin/phpunit

# Executar um teste específico
vendor/bin/phpunit tests/CaminhaoTest.php
```

### Configuração do Banco de Dados para Testes

Para executar os testes que dependem do banco de dados, certifique-se de que:

1. O banco de dados `coleta_leite` existe
2. As tabelas necessárias estão criadas
3. As credenciais em `classes/Conexao.php` estão corretas

### Testes que Requerem Banco de Dados

Alguns testes estão marcados como `markTestSkipped` porque requerem configuração específica do banco de dados:

- `MotoristaTest::testSalvarMotorista()`
- `MotoristaTest::testBuscarPorId()`
- `MotoristaTest::testAtualizarMotorista()`

Para habilitar esses testes, remova a linha `$this->markTestSkipped()` e configure um banco de dados de teste.

## Cobertura de Código

Após executar os testes com cobertura, você pode visualizar o relatório em:
- HTML: `tests/coverage/index.html`
- Texto: `tests/coverage.txt`

## Adicionando Novos Testes

Para adicionar novos testes:

1. Crie um arquivo `NomeClasseTest.php` no diretório `tests/`
2. Estenda a classe `PHPUnit\Framework\TestCase`
3. Implemente métodos de teste que começam com `test`
4. Execute os testes para verificar se passam

## Exemplo de Teste

```php
<?php
use PHPUnit\Framework\TestCase;

class MinhaClasseTest extends TestCase
{
    private $objeto;

    protected function setUp(): void
    {
        $this->objeto = new MinhaClasse();
    }

    public function testMetodoDeveRetornarValorEsperado()
    {
        $resultado = $this->objeto->meuMetodo();
        $this->assertEquals('valor esperado', $resultado);
    }
}
?> 