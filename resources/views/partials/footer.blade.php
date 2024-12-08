<!-- resources/views/partials/footer.blade.php -->

<footer class="footer" role="contentinfo">
    <div class="container footer__container">
        <p>© 2024 BidZenith. {{ __('All Rights Reserved.') }}</p>
        <ul class="social-links" aria-label="{{ __('Social Media') }}">
            <li><a href="#" aria-label="{{ __('Facebook') }}"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
            <li><a href="#" aria-label="{{ __('Twitter') }}"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
            <li><a href="#" aria-label="{{ __('Instagram') }}"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
        </ul>
        <ul class="footer-links">
            <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
            <li><a href="{{ route('contact') }}">{{ __('Contact Us') }}</a></li>
            <li><a href="{{ route('features') }}">{{ __('Features') }}</a></li>
        </ul>
    </div>
</footer>


<!-- Scripts -->
<script>
    // Função para alternar o idioma (se ainda estiver utilizando esta abordagem)
    function changeLanguage(locale) {
        console.log("Changing language to:", locale); // Log the selected locale
        window.location.href = `/lang/${locale}`;
    }

    // Contador regressivo para leilões
    document.querySelectorAll('.auction-timer').forEach(function(timer) {
        const endTime = new Date(timer.getAttribute('data-end-time'));
        const interval = setInterval(function() {
            const now = new Date();
            const diff = endTime - now;
            if (diff <= 0) {
                timer.textContent = '{{ __('Auction ended') }}';
                clearInterval(interval);
                return;
            }
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((diff / (1000 * 60)) % 60);
            const seconds = Math.floor((diff / 1000) % 60);
            timer.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    });
</script>

<script src="{{ asset('js/auctions.js') }}"></script>

</body>
</html>
