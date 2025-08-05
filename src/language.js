// Language switching functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle language toggle with AJAX
    const languageForms = document.querySelectorAll('form[data-language-toggle]');
    
    languageForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            formData.append('ajax', '1');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.language) {
                    // Reload the page to update all content
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to normal form submission
                form.submit();
            });
        });
    });
    
    // Add language indicator to the page
    const currentLang = document.querySelector('[data-current-language]');
    if (currentLang) {
        const lang = currentLang.getAttribute('data-current-language');
        const langNames = {
            'en': 'English',
            'hi': 'हिंदी'
        };
        currentLang.textContent = langNames[lang] || lang;
    }
});

// Function to update page content dynamically (for future use)
function updatePageContent(translations) {
    // This function can be used to update page content without reload
    // For now, we'll use page reload for simplicity
    console.log('Language changed to:', translations);
} 