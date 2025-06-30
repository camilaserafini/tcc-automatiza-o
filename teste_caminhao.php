<?php
require_once 'classes/Caminhao.php';

$caminhao = new Caminhao(1, 'ABC-1234');
echo "ID: " . $caminhao->getIdCaminhao() . "<br>";
echo "Placa: " . $caminhao->getPlaca();
?>
