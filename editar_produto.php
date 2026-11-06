
<?php
header('Content-Type: application/json; charset=utf-8');

try {
    require 'conexao.php';

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    $precoTexto = str_replace(',', '.', $_POST['preco'] ?? '');
    $preco = filter_var($precoTexto, FILTER_VALIDATE_FLOAT);

    if (
        $id === false || $id === null || $id <= 0 ||
        $nome === '' || $categoria === '' ||
        $quantidade === false || $quantidade === null ||
        $quantidade < 0 ||
        $preco === false || $preco < 0
    ) {
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Preencha os dados corretamente.'
        ]);
        exit;
    }

    $sql = "UPDATE produtos
            SET nome = ?, categoria = ?, quantidade = ?, preco = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Erro ao preparar a atualização.');
    }

    $stmt->bind_param(
        "ssidi",
        $nome,
        $categoria,
        $quantidade,
        $preco,
        $id
    );

    if (!$stmt->execute()) {
        throw new Exception('Erro ao atualizar produto.');
    }

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Produto atualizado com sucesso!'
    ]);

    $stmt->close();
    $conn->close();

} catch (Throwable $erro) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao atualizar produto.'
    ]);
}
?>
