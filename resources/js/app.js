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


import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination, Thumbs } from 'swiper/modules';

window.Swiper = Swiper;
window.Navigation = Navigation;
window.Pagination = Pagination;
window.Thumbs = Thumbs;
window.flatpickr = flatpickr;
