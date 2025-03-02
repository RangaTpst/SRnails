document.addEventListener("DOMContentLoaded", () => {
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const prevButton = document.querySelector('.carousel-button.prev');
    const nextButton = document.querySelector('.carousel-button.next');

    let currentIndex = 0;
    let autoSlideInterval;

    function updateSlidePosition() {
        const slideWidth = track.clientWidth;
        track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    }

    function goToNextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlidePosition();
    }

    function goToPrevSlide() {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlidePosition();
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(goToNextSlide, 5000); // Change toutes les 5 secondes
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    nextButton.addEventListener('click', () => {
        goToNextSlide();
        resetAutoSlide();
    });

    prevButton.addEventListener('click', () => {
        goToPrevSlide();
        resetAutoSlide();
    });

    window.addEventListener('resize', updateSlidePosition);

    updateSlidePosition();
    startAutoSlide(); // ✅ Démarrage automatique du carrousel
});
