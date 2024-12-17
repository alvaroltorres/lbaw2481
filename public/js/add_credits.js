document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addCreditsForm');
    const payButton = document.getElementById('payButton');
    const modal = document.getElementById('paymentModal');
    const modalClose = document.getElementById('paymentModalClose');
    const paymentOptions = document.querySelectorAll('.payment-option');
    const processingMessage = document.getElementById('processingMessage');

    payButton.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'block';
    });

    modalClose.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    paymentOptions.forEach(option => {
        option.addEventListener('click', () => {
            processingMessage.style.display = 'block';

            // Simula chamada AJAX a uma "API de pagamento" fictícia
            setTimeout(() => {
                processingMessage.textContent = 'Pagamento confirmado! Créditos adicionados.';
                // Após mais 1 segundo, envia o formulário
                setTimeout(() => {
                    form.submit();
                }, 1000);
            }, 2000);
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
