document.querySelectorAll('.accordion-item button').forEach(button => {
    button.addEventListener('click', () => {
        const paragraph = button.nextElementSibling; // Targets the <p>
        const arrowImg = button.querySelector('img');
        
        // Toggle paragraph visibility
        paragraph.classList.toggle('hidden');
        // Rotate arrow
        arrowImg.classList.toggle('rotate-180');
    });
});