// resources/js/custom.js

import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Swiper for Testimonials
    const testimonialSwiper = new Swiper('.swiper-container', {
        modules: [Navigation, Pagination, Autoplay],
        loop: true,
        spaceBetween: 30,
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

    // Smooth Scroll for Internal Links
    // ... (existing code)

    // Dark Mode Toggle
    // ... (existing code)

    // Countdown Timers for Active Auctions
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

                const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((distance / (1000 * 60)) % 60);
                const seconds = Math.floor((distance / 1000) % 60);

                timerElement.innerHTML = `${hours.toString().padStart(2, '0')}:${minutes
                    .toString()
                    .padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            };

            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        });
    }

    // Countdown Timers for Upcoming Auctions
    if (typeof upcomingAuctions !== 'undefined') {
        upcomingAuctions.forEach(auction => {
            const timerElement = document.getElementById(`upcoming-timer-${auction.id}`);
            const startTime = new Date(auction.start_time);

            const updateTimer = () => {
                const now = new Date();
                const distance = startTime - now;

                if (distance < 0) {
                    timerElement.innerHTML = "Started";
                    clearInterval(timerInterval);
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((distance / (1000 * 60)) % 60);
                const seconds = Math.floor((distance / 1000) % 60);

                timerElement.innerHTML = `${days}d ${hours.toString().padStart(2, '0')}h:${minutes
                    .toString()
                    .padStart(2, '0')}m:${seconds.toString().padStart(2, '0')}s`;
            };

            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        });
    }
});
