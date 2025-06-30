<?php
require_once 'classes/Conexao.php';

$conexao = Conexao::getConexao();

if ($conexao) {
    echo "Conexão realizada com sucesso!";
}
?>
