// Theme management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth transitions to body
    document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
    
    // Add transitions to all elements that might change with theme
    const themeElements = document.querySelectorAll('header, div, section, article, aside, nav');
    themeElements.forEach(element => {
        element.style.transition = 'background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease';
    });
    
    // Handle theme toggle form submission
    const themeForm = document.querySelector('form[action="theme_manager.php"]');
    if (themeForm) {
        themeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('ajax', '1');
            
            fetch('theme_manager.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.theme) {
                    // Reload the page to apply the new theme
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to normal form submission
                this.submit();
            });
        });
    }
    
    // Add theme indicator to page
    const currentTheme = document.body.classList.contains('bg-gray-950') ? 'dark' : 'light';
    const themeIndicator = document.createElement('div');
    themeIndicator.className = 'fixed bottom-4 right-4 z-50';
    themeIndicator.innerHTML = `
        <div class="bg-gray-800 text-white px-3 py-2 rounded-lg shadow-lg text-sm">
            <i class="fas fa-${currentTheme === 'dark' ? 'moon' : 'sun'} mr-2"></i>
            ${currentTheme === 'dark' ? 'Dark' : 'Light'} Mode
        </div>
    `;
    document.body.appendChild(themeIndicator);
    
    // Remove indicator after 3 seconds
    setTimeout(() => {
        if (themeIndicator.parentNode) {
            themeIndicator.parentNode.removeChild(themeIndicator);
        }
    }, 3000);
});

// Function to handle image preview in profile
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewImg = document.getElementById('profile-preview');
            if (previewImg) {
                previewImg.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Function to handle image upload
function handleImageUpload(input) {
    if (input.files && input.files[0]) {
        const form = input.closest('form');
        if (form) {
            form.submit();
        }
    }
} 