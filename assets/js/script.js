function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

const track = document.querySelector(".carousel-track")
const slides = document.querySelectorAll(".carousel-slide")
const prevBtn = document.querySelector(".prev-btn")
const nextBtn = document.querySelector(".next-btn")
const indicators = document.querySelectorAll(".indicator")

let currentSlide = 0
const totalSlides = slides.length
let autoScrollInterval

function updateCarousel() {
  track.style.transform = `translateX(-${currentSlide * 100}%)`

  indicators.forEach((indicator, index) => {
    indicator.classList.toggle("active", index === currentSlide)
  })
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % totalSlides
  updateCarousel()
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + totalSlides) % totalSlides
  updateCarousel()
}

function goToSlide(index) {
  currentSlide = index
  updateCarousel()
}

function startAutoScroll() {
  autoScrollInterval = setInterval(nextSlide, 4000)
}

function stopAutoScroll() {
  clearInterval(autoScrollInterval)
}

function resetAutoScroll() {
  stopAutoScroll()
  startAutoScroll()
}

prevBtn.addEventListener("click", () => {
  prevSlide()
  resetAutoScroll()
})

nextBtn.addEventListener("click", () => {
  nextSlide()
  resetAutoScroll()
})

indicators.forEach((indicator, index) => {
  indicator.addEventListener("click", () => {
    goToSlide(index)
    resetAutoScroll()
  })
})

const carousel = document.querySelector(".carousel")
carousel.addEventListener("mouseenter", stopAutoScroll)
carousel.addEventListener("mouseleave", startAutoScroll)

startAutoScroll()
document.addEventListener('DOMContentLoaded', function() {
    
    const socialIcons = document.querySelectorAll('.social-icon');
    
    const defaultUrls = {
        facebook: 'https://www.facebook.com/NationalUniversityPhilippines',
        instagram: 'https://www.instagram.com/NationalUPH',
        twitter: 'https://x.com/NationalUPhil',
        youtube: 'https://www.youtube.com/c/NationalUniversityPhilippines',
        linkedin: 'https://www.linkedin.com/company/nationaluph'
    };
    
    function getSocialUrl(platform) {
        const saved = localStorage.getItem(`nu_social_${platform}`);
        return saved || defaultUrls[platform];
    }
    
    function saveSocialUrl(platform, url) {
        localStorage.setItem(`nu_social_${platform}`, url);
    }
    
    socialIcons.forEach(icon => {
        const platform = icon.dataset.platform;
        const currentUrl = getSocialUrl(platform);
        
        icon.href = currentUrl;
                icon.addEventListener('click', function(e) {
            e.preventDefault();
            
            const newUrl = prompt(`Enter ${platform.charAt(0).toUpperCase() + platform.slice(1)} URL for National University:`, currentUrl);
            
            if (newUrl && newUrl.trim() !== '') {
                try {
                    new URL(newUrl);
                    saveSocialUrl(platform, newUrl);
                    icon.href = newUrl;
                    
                    window.open(newUrl, '_blank');
                } catch (error) {
                    alert('Please enter a valid URL (e.g., https://www.facebook.com/yourpage)');
                }
            } else if (newUrl !== null) {
                window.open(currentUrl, '_blank');
            }
        });
        
        icon.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            const newUrl = prompt(`Edit ${platform.charAt(0).toUpperCase() + platform.slice(1)} URL:`, currentUrl);
            if (newUrl && newUrl.trim() !== '') {
                try {
                    new URL(newUrl);
                    saveSocialUrl(platform, newUrl);
                    icon.href = newUrl;
                } catch (error) {
                    alert('Please enter a valid URL');
                }
            }
        });
    });
    
    // Email domain validation for registration (auto role detection)
    const registerEmail = document.getElementById('register-email');
    const emailHint = document.getElementById('email-domain-hint');
    
    if (registerEmail && emailHint) {
        
        function validateEmailDomain() {
            const email = registerEmail.value.toLowerCase().trim();
            
            emailHint.style.display = 'none';
            
            // Check for student email domain
            if (email.endsWith('@students.nu-laguna.edu.ph')) {
                emailHint.innerHTML = '<i class="fas fa-check-circle"></i> Student account - You will be registered as a Student';
                emailHint.style.color = '#27ae60';
                emailHint.style.fontWeight = '500';
                emailHint.style.display = 'block';
            }
            // Check for admin email domain
            else if (email.endsWith('@nu-laguna.edu.ph')) {
                emailHint.innerHTML = '<i class="fas fa-check-circle"></i> Admin account - You will be registered as an Admin';
                emailHint.style.color = '#4a5bb8';
                emailHint.style.fontWeight = '500';
                emailHint.style.display = 'block';
            }
            // Invalid domain
            else if (email.includes('@') && email.length > 3) {
                emailHint.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Please use your official NU Laguna email address';
                emailHint.style.color = '#e74c3c';
                emailHint.style.fontWeight = '500';
                emailHint.style.display = 'block';
            }
        }
        
        // Trigger validation on email input
        registerEmail.addEventListener('input', validateEmailDomain);
        registerEmail.addEventListener('blur', validateEmailDomain);
        registerEmail.addEventListener('change', validateEmailDomain);
        
        // Trigger validation on page load if email already has value
        if (registerEmail.value) {
            validateEmailDomain();
        }
        
        // Validate form submission
        const registerForm = registerEmail.closest('form');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const email = registerEmail.value.toLowerCase().trim();
                
                if (!email.endsWith('@students.nu-laguna.edu.ph') && !email.endsWith('@nu-laguna.edu.ph')) {
                    e.preventDefault();
                    alert('Please use an official NU Laguna email address!\n\nUse @students.nu-laguna.edu.ph for students\nUse @nu-laguna.edu.ph for admins');
                    return false;
                }
            });
        }
    }
});