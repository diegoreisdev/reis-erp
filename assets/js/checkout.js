        $('#checkoutForm').on('submit', function(e) {
            const nome = $('#nome_cliente').val();
            const telefone = $('#telefone').val();
            const cep = $('#checkout_cep').val();
            const endereco = $('#endereco_completo').val();

            if (nome.trim().length < 5) {
                e.preventDefault();
                mostrarAlerta('O campo nome precisa de pelo menos 5 caracteres', 'warning');
                return false;
            }
            if (telefone && telefone.trim().length < 10) {
                e.preventDefault();
                mostrarAlerta('Por favor, preencher o telefone corretamente', 'warning');
                return false;
            }

            if (cep.replace(/\D/g, '').length !== 8) {
                e.preventDefault();
                mostrarAlerta('Por favor, digite um CEP válido com 8 dígitos', 'warning');
                return false;
            }

            if (endereco.trim().length < 10) {
                e.preventDefault();
                mostrarAlerta('Por favor, preencha o endereço completo', 'warning');
                return false;
            }
        });