<!-- resources/views/home.blade.php -->

<x-app-layout>
    <!-- Header with Search and Dark Mode Toggle -->

    <!-- Banner Section -->
    <section class="hero relative flex items-center justify-center h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/hero-bg.jpg') }}');">
        <div class="absolute inset-0 bg-primary opacity-60 dark:bg-gray-800 dark:opacity-70 transition-opacity duration-500"></div>
        <div class="relative z-10 text-center p-4">
            <h1 class="text-5xl font-bold mb-4 animate-fadeInDown">Discover the Most Sought-After Auctions</h1>
            <p class="text-xl mb-8 animate-fadeInUp">Join exclusive auctions with an interactive and secure experience.</p>
            <a href="{{ route('auction.index') }}" class="btn btn-primary animate-pulse">Explore Auctions</a>
        </div>
    </section>

    <!-- Floating Help Button -->
    <a href="#" class="fixed bottom-8 right-8 bg-primary text-white p-4 rounded-full shadow-lg hover:bg-primary-dark transition-colors duration-300">
        <i class="fas fa-question"></i>
    </a>

    <!-- Brief Description with Varied Items -->
    <section class="about py-20 bg-lightbg dark:bg-darkbg transition-colors duration-300">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-8">Experience the Future of Online Auctions</h2>
            <p class="text-lg mb-12">BidZenith offers a vast variety of items and an easy-to-use platform. Enjoy innovative features and an engaging user experience.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-item bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg transition-transform transform hover:-translate-y-2">
                    <i class="fas fa-bolt text-primary text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium mb-2">Real-Time Bidding</h3>
                    <p>Engage in auctions with live updates and real-time bidding capabilities.</p>
                </div>
                <!-- Feature 2 -->
                <div class="feature-item bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg transition-transform transform hover:-translate-y-2">
                    <i class="fas fa-gem text-primary text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium mb-2">Exclusive Items</h3>
                    <p>Access a curated selection of rare and exclusive items.</p>
                </div>
                <!-- Feature 3 -->
                <div class="feature-item bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg transition-transform transform hover:-translate-y-2">
                    <i class="fas fa-mobile-alt text-primary text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium mb-2">Mobile Friendly</h3>
                    <p>Enjoy a seamless experience across all your devices.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Active Auctions -->
    <section class="active-auctions py-20 bg-white dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold text-center mb-12">Active Auctions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($activeAuctions as $auction)
                    <div class="auction-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform transform hover:-translate-y-2">
                        <img src="{{ asset($auction->image) }}" alt="{{ $auction->title }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">{{ $auction->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($auction->description, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-primary">${{ number_format($auction->current_bid, 2) }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 auction-timer" id="timer-{{ $auction->id }}">00:00:00</span>
                            </div>
                            <a href="{{ route('auction.show', $auction) }}" class="btn btn-secondary mt-4 block text-center">Participate</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Upcoming Auctions -->
    <section class="upcoming-auctions py-20 bg-lightbg dark:bg-darkbg transition-colors duration-300">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold text-center mb-12">Upcoming Auctions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($upcomingAuctions as $auction)
                    <div class="auction-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform transform hover:-translate-y-2">
                        <img src="{{ asset($auction->image) }}" alt="{{ $auction->title }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">{{ $auction->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($auction->description, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Starts in:</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 upcoming-timer" id="upcoming-timer-{{ $auction->id }}">00:00:00</span>
                            </div>
                            <a href="{{ route('auction.show', $auction) }}" class="btn btn-secondary mt-4 block text-center">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Testimonials -->
    <section class="testimonials py-20 bg-white dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold text-center mb-12">What Our Users Say</h2>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <!-- Testimonial 1 -->
                    <div class="swiper-slide">
                        <div class="testimonial-item bg-gray-100 dark:bg-gray-700 p-8 rounded-lg shadow-lg text-center transition-transform transform hover:scale-105">
                            <p class="mb-4">"BidZenith has transformed my online auction experience. Intuitive and secure platform!"</p>
                            <h4 class="font-semibold">- Alex Johnson</h4>
                        </div>
                    </div>
                    <!-- Testimonial 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-item bg-gray-100 dark:bg-gray-700 p-8 rounded-lg shadow-lg text-center transition-transform transform hover:scale-105">
                            <p class="mb-4">"I love the real-time chat! It makes bidding much more interactive and exciting."</p>
                            <h4 class="font-semibold">- Sophia Lee</h4>
                        </div>
                    </div>
                    <!-- Testimonial 3 -->
                    <div class="swiper-slide">
                        <div class="testimonial-item bg-gray-100 dark:bg-gray-700 p-8 rounded-lg shadow-lg text-center transition-transform transform hover:scale-105">
                            <p class="mb-4">"The security measures give me total confidence to make my transactions."</p>
                            <h4 class="font-semibold">- Michael Smith</h4>
                        </div>
                    </div>
                </div>
                <!-- Swiper Navigation Buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- Swiper Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-600 dark:text-gray-400 mb-4 md:mb-0">© 2024 BidZenith. All Rights Reserved.</p>
            <ul class="social-links flex space-x-4">
                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary transition-colors duration-300"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary transition-colors duration-300"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary transition-colors duration-300"><i class="fab fa-instagram"></i></a></li>
                <!-- Add more social media links as needed -->
            </ul>
        </div>
    </footer>

    <!-- Pass Data to JavaScript -->
    <script>
        const activeAuctions = @json($activeAuctions);
        const upcomingAuctions = @json($upcomingAuctions);
    </script>
</x-app-layout>
