#!/bin/bash

echo "Executando testes unitários do Sistema de Coleta de Leite..."
echo

# Verifica se o PHPUnit existe
if [ ! -f "phpunit.phar" ]; then
    echo "ERRO: phpunit.phar não encontrado!"
    echo "Baixe o PHPUnit em: https://phpunit.de/"
    exit 1
fi

# Verifica se o PHP está instalado
if ! command -v php &> /dev/null; then
    echo "ERRO: PHP não está instalado ou não está no PATH!"
    exit 1
fi

# Executa os testes
php phpunit.phar

echo
echo "Testes concluídos!" 