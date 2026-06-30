document.addEventListener('DOMContentLoaded', function() {
    // Máscara para CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        IMask(cpfInput, {
            mask: '000.000.000-00'
        });
    }

    // Máscara para Telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        IMask(phoneInput, {
            mask: [
                { mask: '(00) 0000-0000' },
                { mask: '(00) 00000-0000' }
            ]
        });
    }
});