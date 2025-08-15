$(function () {
	$(document).on("click", "#btn-novo-produto", function () {
		$("#produtoForm")[0].reset();
		$("#produto_id").val("");
		$("#modal-title-text").text("Novo Produto");
		$("#btn-title-text").html('<i class="fas fa-save"></i> Salvar Produto');

		// Limpa as variações antes de adicionar a primeira
		$("#variacoes-container").empty();
		adicionarVariacao();

		$("#produtoModal").modal("show");
	});

	$(document).on("click", "[data-comprar-produto]", function () {
		const id = $(this).data("comprar-produto");
		comprarProduto(id);
	});

	$(document).on("click", "[data-salvar-produto]", function (e) {
		e.preventDefault();
		salvarProduto();
	});

	$(document).on("click", "[data-editar-produto]", function () {
		const id = $(this).data("editar-produto");
		editarProduto(id);
	});

	$(document).on("click", "[data-excluir-produto]", function () {
		const id = $(this).data("excluir-produto");
		excluirProduto(id);
	});

	$(document).on("click", "[data-add-variacao]", function () {
		adicionarVariacao();
	});

	$(document).on("click", "[data-remover-variacao]", function () {
		removerVariacao(this);
	});

	$("#compra_quantidade").on("change input", calcularTotalItem);
});

const comprarProduto = (id) => {
	$.get(`${baseUrl}produtos/get_produto/${id}`, (data) => {
		$("#compra_produto_id").val(data.produto.id);
		$("#compra_produto_nome").val(data.produto.nome);
		$("#compra_preco").val(
			`R$ ${parseFloat(data.produto.preco).toFixed(2).replace(".", ",")}`
		);
		$("#compra_quantidade").val(1);

		const $select = $("#compra_variacao");

		$select.html('<option value="">Selecione uma variação</option>');

		if (data.estoque && data.estoque.length > 0) {
			$.each(data.estoque, function (index, item) {
				if (item.quantidade > 0) {
					$select.append(
						`<option value="${item.variacao}" data-max-qtd="${item.quantidade}">${item.variacao} (${item.quantidade} disponível)</option>`
					);
				}
			});
		}

		calcularTotalItem();
		$("#compraModal").modal("show");
	}).fail(() => mostrarAlerta("Erro ao carregar produto", "danger"));
};

const calcularTotalItem = () => {
	const preco =
		parseFloat($("#compra_preco").val().replace("R$ ", "").replace(",", ".")) ||
		0;
	const quantidade = parseInt($("#compra_quantidade").val()) || 0;
	const total = preco * quantidade;

	$("#total_item").text(`R$ ${total.toFixed(2).replace(".", ",")}`);
};

const salvarProduto = () => {
	let variacaoValida = false;
	$("#variacoes-container .variacao-row").each(function () {
		const variacao = $(this).find('input[name="variacoes[]"]').val();
		const quantidade = $(this).find('input[name="quantidades[]"]').val();
		if (variacao && quantidade && parseInt(quantidade) > 0) {
			variacaoValida = true;
			return false;
		}
	});
	if (!variacaoValida) {
		mostrarAlerta("Adicione pelo menos uma variação e quantidade maior que zero.", "warning");
		return;
	}

	const formData = $("#produtoForm").serialize();
			$.ajax({
			url     : `${baseUrl}produtos/salvar`,
			type    : 'POST',
			data    : formData,
			dataType: 'json',
		})		
		.done(function (data) {			
			if (data.sucesso) {
				mostrarAlerta("Produto salvo com sucesso", "success");
				$("#produtoModal").modal("hide");
				setTimeout(() => {
					location.reload();
				}, 1000);
			} else {
				mostrarAlerta(data.erro || "Erro ao salvar produto", "danger");
				$btn.html(originalHtml).prop('disabled', false);
			}
		})
		.fail(function () {
			mostrarAlerta('Erro ao salvar produto', "danger");
		});
};

const adicionarVariacao = () => {
	const $container = $("#variacoes-container");
	const $div = $(`
            <div class="row variacao-row mb-2">
                <div class="col-md-5">
                    <input type="hidden" name="estoque_ids[]" value="">
                    <input type="text" class="form-control" name="variacoes[]" placeholder="Ex: P, M, G, Azul, 38">
                </div>
                <div class="col-md-5">
                    <input type="number" class="form-control" name="quantidades[]" placeholder="Quantidade" min="0">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-add-variacao>
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-remover-variacao>
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        `);
	$container.append($div);
};

const removerVariacao = (btn) => {
	const $rows = $(".variacao-row");
	if ($rows.length > 1) {
		$(btn).closest(".variacao-row").remove();
	} else {
		mostrarAlerta("Mantenha pelo menos uma variação", "warning");
	}
};

const editarProduto = (id) => {
	$("#modal-title-text").text("Editar Produto");
	$("#btn-title-text").html('<i class="fas fa-save"></i> Editar Produto');

	$.get(`${baseUrl}produtos/get_produto/${id}`, (data) => {
		$("#produto_id").val(data.produto.id);
		$("#nome").val(data.produto.nome);
		$("#preco").val(data.produto.preco);
		$("#descricao").val(data.produto.descricao || "");

		const $container = $("#variacoes-container").empty();
		if (data.estoque.length) {
			data.estoque.forEach((item) => {
				const $div = $(`
                        <div class="row variacao-row mb-2">
                            <div class="col-md-5">
                                <input type="hidden" name="estoque_ids[]" value="${item.id}">
                                <input type="text" class="form-control" name="variacoes[]" value="${item.variacao}">
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="quantidades[]" value="${item.quantidade}" min="0">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary"  data-add-variacao>
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-remover-variacao>
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    `);
				$container.append($div);
			});
		} else {
			adicionarVariacao();
		}

		$("#produtoModal").modal("show");
	}).fail(() => mostrarAlerta("Erro ao carregar produto", "danger"));
};

const excluirProduto = (id) => {
	// Confirmação antes de excluir
	if (confirm("Tem certeza que deseja excluir este produto?\n\n⚠️ ATENÇÃO: Esta ação irá excluir:\n• O produto\n• Todo o estoque relacionado\n• Todas as variações\n\nEsta ação não pode ser desfeita!")) {
		// Mostra loading no botão
		const $btn         = $(`[data-excluir-produto="${id}"]`);
		const originalHtml = $btn.html();
		$btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
		
		$.ajax({
			url     : `${baseUrl}produtos/excluir/${id}`,
			type    : 'POST',
			dataType: 'json',
		})		
		.done(function (data) {
			if (data.sucesso) {
				mostrarAlerta("Produto excluído com sucesso", "success");
				setTimeout(() => {
					location.reload();
				}, 1000);
			} else {
				mostrarAlerta(data.erro || "Erro ao excluir produto", "danger");
				$btn.html(originalHtml).prop('disabled', false);
			}
		})
		.fail(function (error) {
			mostrarAlerta('Erro ao excluir produto', "danger");
			$btn.html(originalHtml).prop('disabled', false);
		});
	}
};
