import '../css/app.css';
import axios from 'axios';
import Alpine from 'alpinejs';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Alpine = Alpine;
Alpine.start();

// IntersectionObserver for animate on scroll
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = parseInt(entry.target.getAttribute('data-delay') || '0', 10);
                if (delay > 0) {
                    setTimeout(() => {
                        entry.target.classList.add('active');
                    }, delay);
                } else {
                    entry.target.classList.add('active');
                }
                observer.unobserve(entry.target); // only reveal once
            }
        });
    }, { threshold: 0.05 });

    document.querySelectorAll('.reveal, .reveal-scale').forEach(el => observer.observe(el));
});

