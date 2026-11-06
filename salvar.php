
<?php
include 'conexao.php';

// Receber os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');

$quantidade = filter_input(
    INPUT_POST,
    'quantidade',
    FILTER_VALIDATE_INT
);

$preco = filter_input(
    INPUT_POST,
    'preco',
    FILTER_VALIDATE_FLOAT
);

// Verificar se os dados são válidos
if (
    $nome === '' ||
    $categoria === '' ||
    $quantidade === false ||
    $quantidade === null ||
    $quantidade < 0 ||
    $preco === false ||
    $preco === null ||
    $preco < 0
) {
    exit("Preencha os campos corretamente.");
}

// Inserir produto no banco de dados
$sql = "INSERT INTO produtos
        (nome, categoria, quantidade, preco)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssid",
    $nome,
    $categoria,
    $quantidade,
    $preco
);

// Executar o cadastro
if ($stmt->execute()) {
    echo "<script>
        alert('Produto cadastrado com sucesso no banco de dados!');
        window.location.href = 'cadastro.html';
    </script>";
} else {
    echo "Erro ao cadastrar produto.";
}

$stmt->close();
$conn->close();
?>
