// resources/js/custom.js

import Swiper, { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Swiper for Testimonials
    const testimonialSwiper = new Swiper('.swiper-container', {
        modules: [Navigation, Pagination, Autoplay],
        loop: true,
        spaceBetween: 30, // Added space between slides
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });


    // Smooth Scroll para Links Internos
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Alternar Modo Escuro
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    const toggleIcons = () => {
        if (document.documentElement.classList.contains('dark')) {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        } else {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        }
    };

    // Inicializar Ícones no Carregamento da Página
    toggleIcons();

    // Adicionar Evento de Clique
    themeToggleBtn?.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        toggleIcons();
        // Salvar preferência no localStorage
        if (document.documentElement.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });

    // Carregar preferência de tema do localStorage
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
        toggleIcons();
    } else {
        document.documentElement.classList.remove('dark');
        toggleIcons();
    }

    // Contadores de Tempo para Leilões Ativos
    if (typeof auctions !== 'undefined') {
        auctions.forEach(auction => {
            const timerElement = document.getElementById(`timer-${auction.id}`);
            const endTime = new Date(auction.end_time);

            const updateTimer = () => {
                const now = new Date();
                const distance = endTime - now;

                if (distance < 0) {
                    timerElement.innerHTML = "Encerrado";
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
