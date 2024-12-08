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
