<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "sistema_base";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados.");
}

$conn->set_charset("utf8mb4");
?>