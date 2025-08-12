$(function () {
	  // Event listener para buscar CEP
	$(document).on("click", "[data-buscar-cep]", function (e) {
		e.preventDefault();  
		buscarCep(e);
	});

	  // Máscara para CEP
	$(document).on("input", "#checkout_cep", function () {
		let value = $(this).val().replace(/\D/g, "");
		if (value.length >= 5) {
			value = value.replace(/(\d{5})(\d)/, "$1-$2");
		}
		$(this).val(value);
	});

	  // Máscara para telefone
	$(document).on("input", "#telefone", function () {
		let value = $(this).val().replace(/\D/g, "");
		if (value.length <= 10) {
			value = value.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
		} else {
			value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
		}
		$(this).val(value);
	});

	  // Calcular frete quando o modal abrir
	$("#checkoutModal").on("shown.bs.modal", function () {
		calcularFrete();
	});
});

  // Validação do formulário
$("#checkoutForm").on("submit", function (e) {
    e.preventDefault();
	  // Buscar os valores
	const nome     = $("#nome_cliente").val();
	const telefone = $("#telefone").val();
	const cep      = $("#checkout_cep").val();
	const endereco = $("#endereco_completo").val();
	const email    = $('input[name="email"]').val();

	  // Remover classes de erro anteriores
	$(".is-invalid").removeClass("is-invalid");

	  // Validação do nome
	if (!nome || nome.trim().length < 5) {
		
		mostrarAlerta("O campo nome precisa de pelo menos 5 caracteres", "warning");
		$("#nome_cliente").addClass("is-invalid").focus();
		return false;
	}

	  // Validação do email
	if (!email || !emailValido(email)) {
		mostrarAlerta("Por favor, digite um email válido", "warning");
		$('input[name="email"]').addClass("is-invalid").focus();
		return false;
	}

	  // Validação do telefone
	if (telefone && telefone.trim().length > 0) {
		const telefoneNumeros = telefone.replace(/\D/g, "");
		if (telefoneNumeros.length < 10) {
			mostrarAlerta("Por favor, preencha o telefone corretamente", "warning");
			$("#telefone").addClass("is-invalid").focus();
			return false;
		}
	}

	  // Validação do CEP
	if (!cep || cep.replace(/\D/g, "").length !== 8) {
		mostrarAlerta("Por favor, digite um CEP válido com 8 dígitos", "warning");
		$("#checkout_cep").addClass("is-invalid").focus();
		return false;
	}

	  // Validação do endereço
	if (!endereco || endereco.trim().length < 10) {
		mostrarAlerta("Por favor, preencha o endereço completo", "warning");
		$("#endereco_completo").addClass("is-invalid").focus();
		return false;
	}
});

  // Função para validar email
const emailValido = (email) => {
	const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	return emailRegex.test(email);
};

  // Função buscarCep 
const buscarCep = (e) => {
	const cepInput     = $("#checkout_cep");
	const cep          = cepInput.val().replace(/\D/g, "");
	const $btn         = $(e.target);
	const originalText = $btn.html();

	  // Validações
	if (!cep) {
		mostrarAlerta("Digite um CEP", "warning");
		cepInput.addClass("is-invalid").focus();
		return;
	}

	if (cep.length !== 8) {
		mostrarAlerta("CEP deve ter exatamente 8 dígitos", "warning");
		cepInput.addClass("is-invalid").focus();
		return;
	}

	  // Remover classe de erro
	cepInput.removeClass("is-invalid");

	  // Mostrar loading
    $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

	$.ajax({
		url   : `${baseUrl}produtos/verificar_cep`,
		method: "POST",
		data  : {
			cep: cep,
		},
		dataType: "json",
		timeout : 10000,
	})
		.done(function (data) {
			if (data && !data.erro) {
				const logradouro   = data.logradouro ? data.logradouro + ", " : "";
				const bairro       = data.bairro ? data.bairro + ", " : "";
				const localidade   = data.localidade || "";
				const uf           = data.uf || "";
				const cepFormatado = data.cep || "";

				const endereco = `${logradouro}${bairro}${localidade} - ${uf}, CEP: ${cepFormatado}`;

				$("#endereco_completo").val(endereco).removeClass("is-invalid");
				mostrarAlerta("Endereço encontrado!", "success");

				calcularFrete();

				  // Focar no campo de endereço para o usuário completar se necessário
				setTimeout(() => {
					$("#endereco_completo").focus();
				}, 500);
			} else {
				mostrarAlerta(data?.mensagem || "CEP não encontrado", "warning");
				cepInput.addClass("is-invalid").focus();
			}
		})
		.fail(function (xhr, status) {
			let mensagem = "Erro ao buscar CEP. ";
			if (status === "timeout") {
				mensagem += "Tempo limite excedido.";
			} else if (xhr.status === 0) {
				mensagem += "Verifique sua conexão.";
			} else {
				mensagem += "Tente novamente.";
			}

			mostrarAlerta(mensagem, "danger");
			cepInput.addClass("is-invalid").focus();
		})
		.always(function () {
            $btn.html(originalText).prop('disabled', false);
		});
};

const calcularFrete = () => {
	const subtotalText = formatarPadraoInternacional($("#checkout_subtotal"));
	const subtotal = parseFloat(subtotalText) || 0;
	let frete      = 20.0;

	if (subtotal >= 200.0) {
		frete = 0.0;
	} else if (subtotal >= 52.0 && subtotal <= 166.59) {
		frete = 15.0;
	}

	$("#checkout_frete").text(formatarPadraoNacional(frete));

	calcularTotal();
};

const formatarPadraoInternacional = (valor) => {
    return valor.text().replace(/\./g, "").replace(",", ".");
}

const formatarPadraoNacional = (valor) => {
	return valor.toFixed(2).replace(".", ",");
}

const calcularTotal = () => {
	const subtotalText = formatarPadraoInternacional($("#checkout_subtotal"))        
	const descontoText = formatarPadraoInternacional($("#checkout_desconto"));
	const freteText    = formatarPadraoInternacional($("#checkout_frete"))

	const subtotal = parseFloat(subtotalText) || 0;
	const desconto = parseFloat(descontoText) || 0;
	const frete    = parseFloat(freteText) || 0;

	const total = subtotal - desconto + frete;

	$("#checkout_total").text(formatarPadraoNacional(total));
};
