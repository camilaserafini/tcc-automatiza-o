@echo off
echo Executando testes unitarios do Sistema de Coleta de Leite...
echo.

REM Verifica se o PHPUnit existe
if not exist "phpunit-9.6.15.phar" (
    echo ERRO: phpunit-9.6.15.phar nao encontrado!
    echo Baixando PHPUnit...
    powershell -Command "Invoke-WebRequest -Uri 'https://phar.phpunit.de/phpunit-9.6.15.phar' -OutFile 'phpunit-9.6.15.phar'"
)

REM Executa os testes
C:\xampp\php\php.exe phpunit-9.6.15.phar

echo.
echo Testes concluidos!
pause 