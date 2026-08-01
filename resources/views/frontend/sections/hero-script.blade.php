<script>
document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const next = document.querySelector('.hero-next');
    const prev = document.querySelector('.hero-prev');

    if (!slides.length) return;

    let current = 0;

    function showSlide(index) {
        slides[current].classList.remove('opacity-100', 'z-10');
        slides[current].classList.add('opacity-0', 'z-0');

        dots[current]?.classList.remove('bg-red-600');
        dots[current]?.classList.add('bg-white/80');

        current = (index + slides.length) % slides.length;

        slides[current].classList.remove('opacity-0', 'z-0');
        slides[current].classList.add('opacity-100', 'z-10');

        dots[current]?.classList.remove('bg-white/80');
        dots[current]?.classList.add('bg-red-600');
    }

    next?.addEventListener('click', () => showSlide(current + 1));
    prev?.addEventListener('click', () => showSlide(current - 1));

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    setInterval(() => showSlide(current + 1), 2000);
});
</script>