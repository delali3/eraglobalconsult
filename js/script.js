// Docera Travel - Main JavaScript

// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (navToggle && mobileMenu) {
        navToggle.addEventListener('click', function() {
            navToggle.classList.toggle('active');
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileMenu && !mobileMenu.contains(event.target) && !navToggle.contains(event.target)) {
            navToggle.classList.remove('active');
            mobileMenu.classList.add('hidden');
        }
    });
});

// Navbar Scroll Effect
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Testimonial Carousel
let currentTestimonial = 0;
const testimonialTrack = document.getElementById('testimonialTrack');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

if (testimonialTrack && prevBtn && nextBtn) {
    const testimonials = testimonialTrack.children;
    const totalTestimonials = testimonials.length;

    function showTestimonial(index) {
        currentTestimonial = (index + totalTestimonials) % totalTestimonials;
        const offset = -currentTestimonial * 100;
        testimonialTrack.style.transform = `translateX(${offset}%)`;
    }

    prevBtn.addEventListener('click', function() {
        showTestimonial(currentTestimonial - 1);
    });

    nextBtn.addEventListener('click', function() {
        showTestimonial(currentTestimonial + 1);
    });

    // Auto-play testimonials
    setInterval(function() {
        showTestimonial(currentTestimonial + 1);
    }, 5000);
}

// Booking Form Submission
const bookingForm = document.getElementById('bookingForm');
const successMessage = document.getElementById('successMessage');

if (bookingForm) {
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<span class="loading inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span> Sending...';
        submitBtn.disabled = true;

        // Get form data
        const formData = new FormData(bookingForm);
        const data = Object.fromEntries(formData.entries());

        // Submit form via AJAX
        fetch('book-handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            if (result.success) {
                // Show success message
                if (successMessage) {
                    successMessage.querySelector('p').textContent = result.message;
                    successMessage.classList.remove('hidden');
                    successMessage.classList.add('success-message');

                    // Reset form
                    bookingForm.reset();

                    // Scroll to success message
                    successMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                    // Hide message after 10 seconds
                    setTimeout(function() {
                        successMessage.classList.add('hidden');
                    }, 10000);
                }
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            console.error('Booking error:', error);
            alert('An error occurred while submitting your booking. Please try again or contact us directly.');
        });
    });
}

// Contact Form Submission
const contactForm = document.getElementById('contactForm');
const contactSuccessMessage = document.getElementById('contactSuccessMessage');

if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<span class="loading inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span> Sending...';
        submitBtn.disabled = true;

        // Get form data
        const formData = new FormData(contactForm);
        const data = Object.fromEntries(formData.entries());

        // Submit form via AJAX
        fetch('contact-handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            if (result.success) {
                // Show success message
                if (contactSuccessMessage) {
                    contactSuccessMessage.querySelector('p').textContent = result.message;
                    contactSuccessMessage.classList.remove('hidden');
                    contactSuccessMessage.classList.add('success-message');

                    // Reset form
                    contactForm.reset();

                    // Scroll to success message
                    contactSuccessMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                    // Hide message after 10 seconds
                    setTimeout(function() {
                        contactSuccessMessage.classList.add('hidden');
                    }, 10000);
                }
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            console.error('Contact error:', error);
            alert('An error occurred while sending your message. Please try again or email us directly.');
        });
    });
}

// Newsletter Form Submission
const newsletterForms = document.querySelectorAll('#newsletterForm');

newsletterForms.forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const emailInput = this.querySelector('input[type="email"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<span class="loading inline-block w-4 h-4 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>';
        submitBtn.disabled = true;

        const email = emailInput.value;

        // Submit via AJAX
        fetch('newsletter-handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(result => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            if (result.success) {
                alert(result.message);
                // Reset form
                form.reset();
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            console.error('Newsletter error:', error);
            alert('An error occurred. Please try again later.');
        });
    });
});

// Smooth Scroll for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');

        // Skip empty anchors
        if (href === '#') return;

        e.preventDefault();

        const target = document.querySelector(href);
        if (target) {
            const navbarHeight = document.getElementById('navbar').offsetHeight;
            const targetPosition = target.offsetTop - navbarHeight;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Scroll Reveal Animation
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
        }
    });
}, observerOptions);

// Observe all scroll-reveal elements
document.querySelectorAll('.scroll-reveal').forEach(function(element) {
    observer.observe(element);
});

// Form Validation Enhancement
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Add real-time email validation to all email inputs
document.querySelectorAll('input[type="email"]').forEach(function(input) {
    input.addEventListener('blur', function() {
        if (this.value && !validateEmail(this.value)) {
            this.classList.add('border-red-500');

            // Add error message if not exists
            let errorMsg = this.nextElementSibling;
            if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'error-message text-red-500 text-sm mt-1';
                errorMsg.textContent = 'Please enter a valid email address';
                this.parentNode.insertBefore(errorMsg, this.nextSibling);
            }
        } else {
            this.classList.remove('border-red-500');

            // Remove error message if exists
            const errorMsg = this.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        }
    });
});

// Set minimum date for booking date input
const preferredDateInput = document.getElementById('preferredDate');
if (preferredDateInput) {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const year = tomorrow.getFullYear();
    const month = String(tomorrow.getMonth() + 1).padStart(2, '0');
    const day = String(tomorrow.getDate()).padStart(2, '0');

    preferredDateInput.min = `${year}-${month}-${day}`;
}

// Lazy load images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img.lazy').forEach(function(img) {
        imageObserver.observe(img);
    });
}

// Console welcome message
console.log('%c Welcome to Docera Travel ', 'background: #002B5B; color: #D4AF37; font-size: 16px; padding: 10px;');
console.log('%c Empowering ideas. Elevating excellence. ', 'background: #D4AF37; color: #002B5B; font-size: 14px; padding: 8px;');
