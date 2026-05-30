// ========== MANEJO DE MENÚ HAMBURGUESA ==========
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Cerrar menú al hacer click en un enlace
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                menuToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
});

// ========== BANNER DE COOKIES ==========
document.addEventListener('DOMContentLoaded', function() {
    const cookieBanner = document.getElementById('cookieBanner');

    // Mostrar banner si no hay cookies aceptadas
    if (!localStorage.getItem('cookiesAceptadas')) {
        if (cookieBanner) {
            cookieBanner.classList.add('show');
        }
    }
});

function aceptarCookies() {
    localStorage.setItem('cookiesAceptadas', 'true');
    const cookieBanner = document.getElementById('cookieBanner');
    if (cookieBanner) {
        cookieBanner.classList.remove('show');
    }
    console.log('Cookies aceptadas');
}

function rechazarCookies() {
    localStorage.setItem('cookiesAceptadas', 'false');
    const cookieBanner = document.getElementById('cookieBanner');
    if (cookieBanner) {
        cookieBanner.classList.remove('show');
    }
}

// ========== ANIMACIONES AL SCROLL ==========
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Aplicar observador a elementos
document.querySelectorAll('.review-card, .price-card, .gallery-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// ========== VALIDACIÓN DE FORMULARIOS ==========
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        // Las validaciones HTML5 se encargan de los campos required
        console.log('Formulario enviado correctamente');
    });
});

// ========== EFECTO SCROLL EN NAV ==========
let lastScrollTop = 0;
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', function() {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > 50) {
        if (navbar) {
            navbar.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.15)';
        }
    } else {
        if (navbar) {
            navbar.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
        }
    }

    lastScrollTop = scrollTop;
});
