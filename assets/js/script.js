const mostrarAlerta = (mensagem, tipo = "info") => {
	const alertTypes = {
		success: "alert-success",
		danger: "alert-danger",
		warning: "alert-warning",
		info: "alert-info",
	};

	const icons = {
		success: "fa-check-circle",
		danger: "fa-exclamation-circle",
		warning: "fa-exclamation-triangle",
		info: "fa-info-circle",
	};

	const $alert = $(`
                <div class="alert ${alertTypes[tipo]} alert-dismissible fade show position-fixed" 
                    style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="fas ${icons[tipo]}"></i> ${mensagem}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);

	$("body").append($alert);

	// Aparecer suavemente
	$alert.fadeIn(500);

	// Sumir suavemente após 5 segundos
	setTimeout(() => {
		$alert.fadeOut(500, function () {
			$(this).remove();
		});
	}, 5000);
};
