async function atualizarResumo() {
    try {
        const resposta = await fetch("buscar_produtos.php");
        if (!resposta.ok) throw new Error("Erro ao consultar produtos");
        const produtos = await resposta.json();
        document.getElementById("totalProdutos").textContent = produtos.length;
        document.getElementById("totalUnidades").textContent = produtos.reduce(
            (soma, produto) => soma + Number(produto.quantidade), 0
        );
    } catch (erro) {
        console.error("Erro ao atualizar o resumo:", erro);
    }
}
atualizarResumo();
