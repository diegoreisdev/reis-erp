$(function () {
    $(document).on("click", "[data-editar-cupom]", function () {
        const id = $(this).data("editar-cupom");
        editarCupom(id);
    });

    
    $('#cupomModal').on('hidden.bs.modal', function() {
        $('#cupomForm')[0].reset();
        $('#cupom_id').val('');
        $('#modal-cupom-title').text('Novo Cupom de Desconto');
        $('#valor-prefix').text('R$');
        $('#valor-help').text('Selecione o tipo de desconto primeiro');
        $('#cupom-preview').hide();

        // Redefinir datas padrão
        const hoje = new Date().toISOString().split('T')[0];
        const proximoAno = new Date();
        proximoAno.setFullYear(proximoAno.getFullYear() + 1);
        const dataFim = proximoAno.toISOString().split('T')[0];

        $('#data_inicio').val(hoje);
        $('#data_fim').val(dataFim);
        $('#ativo').prop('checked', true);
    });
});

const alterarTipo = () => {
    const tipo = $('#tipo').val();
    const $prefix = $('#valor-prefix');
    const $help = $('#valor-help');
    const $valorInput = $('#valor');

    if (tipo === 'percentual') {
        $prefix.text('%');
        $help.text('Digite a porcentagem de desconto (ex: 10 para 10%)');
        $valorInput.attr('max', '100').attr('placeholder', '10');
    } else if (tipo === 'fixo') {
        $prefix.text('R$');
        $help.text('Digite o valor fixo de desconto em reais');
        $valorInput.removeAttr('max').attr('placeholder', '15,00');
    } else {
        $prefix.text('R$');
        $help.text('Selecione o tipo de desconto primeiro');
        $valorInput.removeAttr('max');
    }

    atualizarPreview();
}

const atualizarPreview = () => {
    const codigo = $('#codigo').val().toUpperCase() || 'CODIGO';
    const tipo = $('#tipo').val();
    const valor = parseFloat($('#valor').val()) || 0;
    const valorMinimo = parseFloat($('#valor_minimo').val()) || 0;

    const $preview = $('#cupom-preview');

    if (codigo && tipo && valor > 0) {
        $preview.show();

        $('#preview-codigo').text(codigo);

        const $tipoBadge = $('#preview-tipo');
        const $previewDesconto = $('#preview-desconto');

        if (tipo === 'percentual') {
            $tipoBadge.removeClass().addClass('badge bg-info').text('Percentual');
            $previewDesconto.text(`${valor}% OFF`);
        } else {
            $tipoBadge.removeClass().addClass('badge bg-warning').text('Valor Fixo');
            $previewDesconto.text(`R$ ${valor.toFixed(2).replace('.', ',')} OFF`);
        }

        $('#preview-minimo').text(`Mínimo: R$ ${valorMinimo.toFixed(2).replace('.', ',')}`);
    } else {
        $preview.hide();
    }
}

const editarCupom = (id) => {
    $.get(`${baseUrl}cupons/get_cupom/${id}`, (data) => {
        $('#modal-cupom-title').text('Editar Cupom');
        $('#cupom_id').val(data.cupom.id);
        $('#codigo').val(data.cupom.codigo);    
        $('#tipo').val(data.cupom.tipo);
        $('#valor').val(data.cupom.valor);
        $('#valor_minimo').val(data.cupom.valor_minimo);
        $('#data_inicio').val(data.cupom.data_inicio);
        $('#data_fim').val(data.cupom.data_fim);
        $('#ativo').prop('checked', data.cupom.ativo == '1');

        alterarTipo();
        atualizarPreview();
        $("#cupomModal").modal("show");
    }).fail(() => mostrarAlerta("Erro ao carregar cupom", "danger"));
}