import './bootstrap';
// Source - https://stackoverflow.com/a/72253945
// import Swiper, { Autoplay, Navigation, Pagination } from "swiper";
// Swiper.use([Autoplay, Navigation, Pagination]);

function toggleTheme() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const next = isDark ? 'light' : 'dark';

    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
}
