// resources/js/app.js
import './bootstrap'; // Keep your existing bootstrap imports

import Alpine from 'alpinejs'; // Keep Alpine if you're using it
window.Alpine = Alpine;
Alpine.start();

// Import Swiper and its CSS (important!)
import Swiper from 'swiper/bundle';
// No need to import 'swiper/css/bundle' here if it's already in resources/css/app.css
// But it's good practice to ensure it's loaded if not already.

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper only if the container exists
    if (document.querySelector('.swiper-container')) {
        new Swiper('.swiper-container', {
            // Optional parameters for your banner carousel
            loop: true, // Makes the carousel loop infinitely
            autoplay: {
                delay: 5000, // 5 seconds delay
                disableOnInteraction: false, // Continue autoplay after user interaction
            },
            pagination: {
                el: '.swiper-pagination', // Your pagination dots container
                clickable: true, // Makes dots clickable
            },
            // Add navigation arrows if you want them
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
        });
    }

    // Your existing Category Filter JavaScript (from home.blade.php)
    const tabs = document.querySelectorAll('.category-tab');
    const cards = document.querySelectorAll('.game-card');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Filter games
            const category = this.dataset.category;
            cards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});