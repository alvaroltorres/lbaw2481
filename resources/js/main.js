document.addEventListener('DOMContentLoaded', () => {
    // Smooth Scroll para Links Internos
    const links = document.querySelectorAll('a[href^="#"]');
    for (const link of links) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Toggle do Modo Escuro
    const themeToggleBtn = document.getElementById('theme-toggle');

    const toggleTheme = () => {
        document.body.classList.toggle('dark-mode');
        // Salvar preferência no localStorage
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    };

    // Carregar preferência de tema do localStorage
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }

    themeToggleBtn?.addEventListener('click', toggleTheme);

    // Temporizadores de Leilão
    if (typeof activeAuctions !== 'undefined') {
        activeAuctions.forEach(auction => {
            const timerElement = document.getElementById(`timer-${auction.id}`);
            const endTime = new Date(auction.end_time);

            const updateTimer = () => {
                const now = new Date();
                const distance = endTime - now;

                if (distance < 0) {
                    timerElement.innerHTML = "Ended";
                    clearInterval(timerInterval);
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.innerHTML = `${hours.toString().padStart(2, '0')}:${minutes
                    .toString()
                    .padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            };

            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        });
    }
});
