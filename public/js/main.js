// resources/js/main.js

document.addEventListener('DOMContentLoaded', () => {

    const deleteButton = document.getElementById("deleteButton");
    const hiddenPasswordInput = document.getElementById("hiddenPasswordInput");

    // Show password input when the delete button is clicked
    deleteButton.addEventListener("click", function() {
        hiddenPasswordInput.style.display = "block"; // Show the password form
    });


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

    // Mudar Idioma
    window.changeLanguage = function(locale) {
        window.location.href = `/lang/${locale}`;
    };
});
document.addEventListener('DOMContentLoaded', () => {
    // Carrossel automático de auctions
    const auctionGrids = document.querySelectorAll('.auction-grid');
    auctionGrids.forEach(grid => {
        let scrollPosition = 0;
        let direction = 1; // 1 para direita, -1 para esquerda

        function autoScroll() {
            if (grid.scrollWidth > grid.clientWidth) {
                scrollPosition += direction * 2;
                if (scrollPosition >= (grid.scrollWidth - grid.clientWidth - 10)) {
                    direction = -1;
                } else if (scrollPosition <= 0) {
                    direction = 1;
                }
                grid.scrollLeft = scrollPosition;
            }
        }

        setInterval(autoScroll, 50);

        // Controles manuais
        grid.addEventListener('click', (e) => {
            const rect = grid.getBoundingClientRect();
            const x = e.clientX - rect.left;
            if (x < 50) {
                // Esquerda
                grid.scrollLeft -= 200;
                scrollPosition = grid.scrollLeft;
            } else if (x > rect.width - 50) {
                // Direita
                grid.scrollLeft += 200;
                scrollPosition = grid.scrollLeft;
            }
        });
    });

    // Modals de ajuda
    const helpButtons = document.querySelectorAll('.help-button');
    const closeButtons = document.querySelectorAll('.close-button');
    const modals = document.querySelectorAll('.modal');

    helpButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            modal.style.display = 'block';
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
        });
    });

    window.addEventListener('click', (event) => {
        modals.forEach(modal => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    });
});

