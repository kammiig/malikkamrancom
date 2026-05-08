const navToggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');
const header = document.querySelector('[data-header]');

if (navToggle && nav) {
    navToggle.addEventListener('click', () => {
        nav.classList.toggle('is-open');
    });

    nav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => nav.classList.remove('is-open'));
    });
}

if (header) {
    const syncHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 10);
    syncHeader();
    window.addEventListener('scroll', syncHeader, { passive: true });
}

const revealItems = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window && revealItems.length) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.14 });

    revealItems.forEach((item) => observer.observe(item));
} else {
    revealItems.forEach((item) => item.classList.add('is-visible'));
}

document.querySelectorAll('[data-confirm]').forEach((element) => {
    element.addEventListener('submit', (event) => {
        const message = element.getAttribute('data-confirm') || 'Are you sure?';
        if (!window.confirm(message)) {
            event.preventDefault();
        }
    });
});

document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('[data-carousel-track]');
    const section = carousel.closest('section');
    const prev = section?.querySelector('[data-carousel-prev]');
    const next = section?.querySelector('[data-carousel-next]');

    if (!track || !prev || !next) {
        return;
    }

    const scrollBySlide = (direction) => {
        const slide = track.querySelector('.testimonial-card');
        const amount = slide ? slide.getBoundingClientRect().width + 20 : track.clientWidth;
        track.scrollBy({ left: direction * amount, behavior: 'smooth' });
    };

    prev.addEventListener('click', () => scrollBySlide(-1));
    next.addEventListener('click', () => scrollBySlide(1));
});
