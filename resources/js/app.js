import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination, Thumbs } from 'swiper/modules';
import './bootstrap';

import bookingForm from './components/booking';
import chatComponent from './components/chat';
import galleryComponent from './components/gallery';

// Swiper
window.Swiper      = Swiper;
window.Navigation  = Navigation;
window.Pagination  = Pagination;
window.Thumbs      = Thumbs;

// Flatpickr
window.flatpickr   = flatpickr;

// Alpine
window.bookingForm      = bookingForm;
window.chatComponent    = chatComponent;
window.galleryComponent = galleryComponent;



// Source - https://stackoverflow.com/a/72253945
// import Swiper, { Autoplay, Navigation, Pagination } from "swiper";
// Swiper.use([Autoplay, Navigation, Pagination]);
