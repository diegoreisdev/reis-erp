$(function () {
	$(document).on("click", "[data-add-carrinho]", function () {
		adicionarAoCarrinho();
	});
});

const adicionarAoCarrinho = () => {
	const produtoId = $("#compra_produto_id").val();
	const variacao = $("#compra_variacao").val();
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
		url: `${baseUrl}produtos/adicionar_carrinho`,
		method: "POST",
		data: {
			produto_id: produtoId,
			variacao: variacao,
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
		.fail(function (xhr, status, error) {
			console.error("Erro:", error);
			mostrarAlerta("Erro ao adicionar produto", "danger");
		});
};
