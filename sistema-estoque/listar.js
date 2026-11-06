
const listaProdutos = document.getElementById("listaProdutos");

async function carregarProdutos() {
    try {
        const resposta = await fetch("buscar_produtos.php");

        if (!resposta.ok) {
            throw new Error("Erro ao buscar produtos.");
        }

        const produtos = await resposta.json();
        console.log("Produtos recebidos do MySQL:", produtos);
        listaProdutos.innerHTML = "";

        document.getElementById("totalProdutos").textContent =
            produtos.length;

        const unidades = produtos.reduce((total, produto) => {
            return total + Number(produto.quantidade);
        }, 0);

        document.getElementById("totalUnidades").textContent =
            unidades;

        if (produtos.length === 0) {
            listaProdutos.innerHTML =
                '<tr><td colspan="5">Nenhum produto cadastrado.</td></tr>';
            return;
        }

        produtos.forEach((produto) => {
            const linha = document.createElement("tr");

            const nome = linha.insertCell();
            nome.textContent = produto.nome;

            const categoria = linha.insertCell();
            categoria.textContent = produto.categoria;

            const quantidade = linha.insertCell();
            quantidade.textContent = produto.quantidade;

            const preco = linha.insertCell();
            preco.textContent = "R$ " +
                Number(produto.preco).toFixed(2).replace(".", ",");

            const acoes = linha.insertCell();

            const editar = document.createElement("button");
            editar.className = "botao-editar";
            editar.textContent = "Editar";
            editar.addEventListener("click", () => editarProduto(produto));

            const excluir = document.createElement("button");
            excluir.className = "botao-excluir";
            excluir.textContent = "Excluir";
            excluir.addEventListener("click", () => excluirProduto(produto.id));

            acoes.append(editar, excluir);
            listaProdutos.appendChild(linha);
        });

    } catch (erro) {
        console.error(erro);
        listaProdutos.innerHTML =
            '<tr><td colspan="5">Erro ao carregar produtos.</td></tr>';
    }
}

async function excluirProduto(id) {
    if (!confirm("Tem certeza que deseja excluir este produto?")) {
        return;
    }

    try {
        const dados = new URLSearchParams();
        dados.append("id", id);

        const resposta = await fetch("excluir_produto.php", {
            method: "POST",
            body: dados
        });

        const resultado = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {
            throw new Error(resultado.mensagem || "Erro ao excluir.");
        }

        await carregarProdutos();

    } catch (erro) {
        alert(erro.message);
    }
}

async function editarProduto(produto) {
    const nome = prompt("Nome do produto:", produto.nome);
    if (nome === null) return;

    const categoria = prompt("Categoria:", produto.categoria);
    if (categoria === null) return;

    const quantidade = prompt("Quantidade:", produto.quantidade);
    if (quantidade === null) return;

    const preco = prompt("Preço:", produto.preco);
    if (preco === null) return;

    if (
        !nome.trim() ||
        !categoria.trim() ||
        quantidade.trim() === "" ||
        preco.trim() === "" ||
        !Number.isInteger(Number(quantidade)) ||
        Number(quantidade) < 0 ||
        !Number.isFinite(Number(preco.replace(",", "."))) ||
        Number(preco.replace(",", ".")) < 0
    ) {
        alert("Preencha os dados corretamente.");
        return;
    }

    try {
        const dados = new URLSearchParams();
        dados.append("id", produto.id);
        dados.append("nome", nome.trim());
        dados.append("categoria", categoria.trim());
        dados.append("quantidade", quantidade);
        dados.append("preco", preco.replace(",", "."));

        const resposta = await fetch("editar_produto.php", {
            method: "POST",
            body: dados
        });

        const resultado = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {
            throw new Error(resultado.mensagem || "Erro ao editar.");
        }

        alert("Produto atualizado com sucesso!");
        await carregarProdutos();

    } catch (erro) {
        alert(erro.message);
    }
}

carregarProdutos();
