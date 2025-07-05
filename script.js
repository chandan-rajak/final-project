// Show welcome alert when the page loads
window.onload = function () {
    alert("Welcome to the Mess Management System!");
};

// Add interactivity to all food cards
const cards = document.querySelectorAll('.food-card');

cards.forEach(card => {
    // Highlight on hover
    card.addEventListener('mouseover', () => {
        card.style.boxShadow = '0 0 15px rgba(0,123,255,0.5)';
        card.style.cursor = 'pointer';
    });

    // Reset shadow on mouse out
    card.addEventListener('mouseout', () => {
        card.style.boxShadow = '0 0 8px rgba(0,0,0,0.1)';
    });

    // Show dish name on click
    card.addEventListener('click', () => {
        const dish = card.getAttribute('data-name');
        alert("You clicked on: " + dish);
    });
});
