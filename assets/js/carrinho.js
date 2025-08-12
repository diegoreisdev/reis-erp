$(function () {
	$(document).on("click", "[data-add-carrinho]", function () {
		adicionarAoCarrinho();
	});
	$(document).on("click", "[data-limpar-carrinho]", function () {
		limparCarrinho();
	});
	$(document).on("click", "[data-remover-item]", function () {
		const id = $(this).data("remover-item");
		removerItem(id);
	});
});

const adicionarAoCarrinho = () => {
	const produtoId  = $("#compra_produto_id").val();
	const variacao   = $("#compra_variacao").val();
	const quantidade = parseInt($("#compra_quantidade").val());

	if (!variacao) {
		mostrarAlerta("Selecione uma variação", "warning");
		return;
	}

	  // Verificar quantidade máxima
	const maxQtd = parseInt(
		$("#compra_variacao option:selected").data("max-qtd")
	);

	if (quantidade > maxQtd) {
		mostrarAlerta(`Quantidade máxima disponível: ${maxQtd}`, "warning");
		return;
	}

	$.ajax({
		url   : `${baseUrl}produtos/adicionar_carrinho`,
		method: "POST",
		data  : {
			produto_id: produtoId,
			variacao  : variacao,
			quantidade: quantidade,
		},
		dataType: "json",
	})
		.done(function (data) {
			if (data.success) {
				mostrarAlerta("Produto adicionado ao carrinho!", "success");
				setTimeout(() => location.reload(), 1000);
			} else {
				mostrarAlerta(data.message, "danger");
			}
		})
		.fail(function (error) {
			console.error("Erro:", error);
			mostrarAlerta("Erro ao adicionar produto", "danger");
		});
};

const limparCarrinho = () => {
	if (!confirm("Deseja limpar todo o carrinho?")) return;

	$.ajax({
		url     : `${baseUrl}produtos/remover_carrinho`,
		method  : "POST",
		data    : {},
		dataType: "json",
	})
		.done(function (data) {
			if (data.success) {
				mostrarAlerta("Carrinho limpo com sucesso", "success");
				setTimeout(() => location.reload(), 1000);
			}
		})
		.fail(function (error) {
			console.error("Erro:", error);
			mostrarAlerta("Erro ao remover item", "danger");
		});
};

const removerItem = (itemKey) => {
	if (!confirm("Deseja remover este item do carrinho?")) return;

	$.ajax({
		url   : `${baseUrl}produtos/remover_item_carrinho`,
		method: "POST",
		data  : {
			item_key: itemKey,
		},
		dataType: "json",
	})
		.done(function (data) {
			if (data.success) {
				mostrarAlerta("Item removido do carrinho", "info");
				setTimeout(() => location.reload(), 1000);
			}
		})
		.fail(function (error) {
			console.error("Erro:", error);
			mostrarAlerta("Erro ao remover item", "danger");
		});
};
