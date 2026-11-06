
<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexao.php';

$sql = "SELECT id, nome, categoria, quantidade, preco
        FROM produtos ORDER BY id DESC";

$resultado = $conn->query($sql);

$produtos = [];

while ($linha = $resultado->fetch_assoc()) {
    $produtos[] = $linha;
}

echo json_encode($produtos);

$conn->close();
?>
