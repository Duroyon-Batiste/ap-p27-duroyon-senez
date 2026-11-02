
document.addEventListener('DOMContentLoaded', () => {

    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.carousel-dots button');
    const previous = document.querySelector('.carousel-arrow.previous');
    const next = document.querySelector('.carousel-arrow.next');

    if (slides.length === 0) {
        return;
    }

    let currentIndex = 0;
    let interval;

    function showSlide(index) {
        currentIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === currentIndex);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }

    function restartInterval() {
        clearInterval(interval);

        interval = setInterval(() => {
            showSlide(currentIndex + 1);
        }, 5000);
    }

    previous.addEventListener('click', () => {
        showSlide(currentIndex - 1);
        restartInterval();
    });

    next.addEventListener('click', () => {
        showSlide(currentIndex + 1);
        restartInterval();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            restartInterval();
        });
    });

    showSlide(0);
    restartInterval();
});
