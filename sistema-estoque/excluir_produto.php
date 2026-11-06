
<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexao.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    http_response_code(400);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "ID inválido."
    ]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);

$sucesso = $stmt->execute();

echo json_encode([
    "sucesso" => $sucesso,
    "mensagem" => $sucesso
        ? "Produto excluído."
        : "Erro ao excluir produto."
]);

$stmt->close();
$conn->close();
?>
