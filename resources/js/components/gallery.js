export default function galleryComponent(images) {
    return {
        imgs: images,
        cur: 0,

        open(i) {
            document.getElementById('galleryModal').classList.remove('hidden');
            document.getElementById('galleryModal').classList.add('flex');
            this.goTo(i);
        },

        close() {
            document.getElementById('galleryModal').classList.add('hidden');
            document.getElementById('galleryModal').classList.remove('flex');
        },

        go(d) {
            this.goTo((this.cur + d + this.imgs.length) % this.imgs.length);
        },

        goTo(i) {
            this.cur = i;
            document.getElementById('galleryMainImg').src = '/storage/' + this.imgs[i];
            const counter = document.getElementById('galleryCounter');
            if (counter) counter.textContent = (i + 1) + ' / ' + this.imgs.length;
            document.querySelectorAll('[data-thumb]').forEach((el, idx) => {
                el.style.borderColor = idx === i ? '#fff' : 'transparent';
                el.style.opacity     = idx === i ? '1' : '0.5';
            });
            document.querySelectorAll('[data-thumb]')[i]
                ?.scrollIntoView({ inline: 'nearest', behavior: 'smooth' });
        },

        initKeyboard() {
            document.addEventListener('keydown', (e) => {
                const modal = document.getElementById('galleryModal');
                if (!modal || modal.classList.contains('hidden')) return;
                if (e.key === 'ArrowLeft')  this.go(-1);
                if (e.key === 'ArrowRight') this.go(1);
                if (e.key === 'Escape')     this.close();
            });
        }
    };
}

const _gallery = galleryComponent(window.galleryImages || []);

window.openGallery = (i) => _gallery.open(i);
window.closeGallery = () => _gallery.close();
window.galleryGo = (d) => _gallery.go(d);
window.galleryGoTo = (i) => _gallery.goTo(i);

_gallery.initKeyboard();
