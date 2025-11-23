/* ==========================================================================
   CLICK & PAW - LÓGICA JAVASCRIPT
   Sistema de Adopción de Mascotas
   ========================================================================== */

/* ==========================================================================
   NAVEGACIÓN MÓVIL
   ========================================================================== */
function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    if (navMenu) {
        navMenu.classList.toggle('active');
    }
}

/* ==========================================================================
   FILTROS DE GALERÍA
   ========================================================================== */
// Función para remover filtros individuales
function removeFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Filtrado en tiempo real para versión HTML estática
function initializeFilters() {
    const form = document.getElementById('filterForm');
    const petsGrid = document.getElementById('petsGrid');
    
    if (!form || !petsGrid) return;
    
    const petCards = Array.from(petsGrid.querySelectorAll('.pet-card'));

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        filterPets();
    });

    // También filtrar al cambiar selects
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', filterPets);
    });

    function filterPets() {
        const especieSelect = document.getElementById('especie');
        const edadSelect = document.getElementById('edad');
        const tamanoSelect = document.getElementById('tamano');
        const generoSelect = document.getElementById('genero');
        const buscarInput = document.querySelector('input[name="buscar"]');
        
        const especie = especieSelect ? especieSelect.value.toLowerCase() : '';
        const edad = edadSelect ? edadSelect.value.toLowerCase() : '';
        const tamano = tamanoSelect ? tamanoSelect.value.toLowerCase() : '';
        const genero = generoSelect ? generoSelect.value.toLowerCase() : '';
        const buscar = buscarInput ? buscarInput.value.toLowerCase() : '';

        let visibleCount = 0;

        petCards.forEach(card => {
            const cardEspecie = card.dataset.especie ? card.dataset.especie.toLowerCase() : '';
            const cardEdad = card.dataset.edad ? card.dataset.edad.toLowerCase() : '';
            const cardTamano = card.dataset.tamano ? card.dataset.tamano.toLowerCase() : '';
            const cardGenero = card.dataset.genero ? card.dataset.genero.toLowerCase() : '';
            const cardNombre = card.dataset.nombre ? card.dataset.nombre.toLowerCase() : '';

            const matchEspecie = !especie || cardEspecie === especie;
            const matchEdad = !edad || cardEdad === edad;
            const matchTamano = !tamano || cardTamano === tamano;
            const matchGenero = !genero || cardGenero === genero;
            const matchNombre = !buscar || cardNombre.includes(buscar);

            if (matchEspecie && matchEdad && matchTamano && matchGenero && matchNombre) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Actualizar contador de resultados
        const resultsCount = document.querySelector('.results-count');
        if (resultsCount) {
            resultsCount.textContent = `${visibleCount} mascota${visibleCount !== 1 ? 's' : ''} disponible${visibleCount !== 1 ? 's' : ''}`;
        }
    }

    // Función para limpiar filtros
    window.clearFilters = function() {
        if (document.getElementById('especie')) document.getElementById('especie').value = '';
        if (document.getElementById('edad')) document.getElementById('edad').value = '';
        if (document.getElementById('tamano')) document.getElementById('tamano').value = '';
        if (document.getElementById('genero')) document.getElementById('genero').value = '';
        const buscarInput = document.querySelector('input[name="buscar"]');
        if (buscarInput) buscarInput.value = '';
        filterPets();
    };
}

/* ==========================================================================
   VALIDACIÓN DE FORMULARIO DE ADOPCIÓN
   ========================================================================== */
function initializeAdoptionForm() {
    const form = document.getElementById('adoptionForm');
    if (!form) return;

    // Character Counter para el campo de motivación
    const motivoTextarea = document.getElementById('motivo_adopcion');
    const charCounter = document.getElementById('charCounter');

    if (motivoTextarea && charCounter) {
        motivoTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length} / 500 caracteres mínimo`;
            
            if (length >= 500) {
                charCounter.classList.remove('warning');
                charCounter.style.color = 'var(--color-fern-green)';
            } else {
                charCounter.classList.add('warning');
            }
        });
    }

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Reset errores
        document.querySelectorAll('.form-group').forEach(group => {
            group.classList.remove('error');
        });
        document.querySelectorAll('.error-message').forEach(msg => {
            msg.classList.remove('show');
        });

        // Validar Nombre
        const nombre = document.getElementById('nombre_completo');
        if (nombre && nombre.value.trim() === '') {
            showError('nombre_completo', 'error-nombre');
            isValid = false;
        }

        // Validar Email
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email.value)) {
            showError('email', 'error-email');
            isValid = false;
        }

        // Validar Teléfono
        const telefono = document.getElementById('telefono');
        if (telefono && telefono.value.trim() === '') {
            showError('telefono', 'error-telefono');
            isValid = false;
        }

        // Validar Dirección
        const direccion = document.getElementById('direccion');
        if (direccion && direccion.value.trim() === '') {
            showError('direccion', 'error-direccion');
            isValid = false;
        }

        // Validar Descripción Hogar
        const hogar = document.getElementById('descripcion_hogar');
        if (hogar && hogar.value.trim() === '') {
            showError('descripcion_hogar', 'error-hogar');
            isValid = false;
        }

        // Validar Motivo (mínimo 500 caracteres)
        const motivo = document.getElementById('motivo_adopcion');
        if (motivo && motivo.value.trim().length < 500) {
            showError('motivo_adopcion', 'error-motivo');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            // Scroll al primer error
            const firstError = document.querySelector('.form-group.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    function showError(fieldId, errorId) {
        const field = document.getElementById(fieldId);
        const error = document.getElementById(errorId);
        
        if (field && error) {
            field.closest('.form-group').classList.add('error');
            error.classList.add('show');
        }
    }
}

/* ==========================================================================
   MODAL DE CONFIRMACIÓN
   ========================================================================== */
function initializeModal() {
    const modal = document.getElementById('confirmationModal');
    
    if (modal) {
        // Cerrar modal al hacer clic fuera
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });
    }
}

function showConfirmationModal() {
    const modal = document.getElementById('confirmationModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/* ==========================================================================
   INICIALIZACIÓN AL CARGAR LA PÁGINA
   ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar filtros si existe el formulario
    initializeFilters();
    
    // Inicializar formulario de adopción si existe
    initializeAdoptionForm();
    
    // Inicializar modal si existe
    initializeModal();
    
    // Agregar smooth scroll a todos los enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});