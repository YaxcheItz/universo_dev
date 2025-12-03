// public/js/valoracion.js

let puntuacionSeleccionada = 0;

function abrirModalValoracion(proyectoId, proyectoNombre, valoracionActual = 0) {
    const modal = document.getElementById('modal-valoracion');
    
    // Mostrar modal
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    // Configurar datos
    document.getElementById('modal-proyecto-nombre').textContent = proyectoNombre;
    document.getElementById('valoracion-proyecto-id').value = proyectoId;
    document.getElementById('form-valoracion').action = `/proyectos/${proyectoId}/valorar`;
    document.getElementById('error-puntuacion').classList.add('hidden');
    
    // Si ya tiene valoración, mostrarla
    if (valoracionActual > 0) {
        seleccionarEstrella(valoracionActual);
    } else {
        puntuacionSeleccionada = 0;
        document.getElementById('puntuacion-input').value = '';
        actualizarEstrellas();
    }
}

function cerrarModalValoracion() {
    const modal = document.getElementById('modal-valoracion');
    
    // Ocultar modal
    modal.style.display = 'none';
    modal.classList.add('hidden');
    
    // Restaurar scroll del body
    document.body.style.overflow = '';
    
    // Limpiar formulario
    puntuacionSeleccionada = 0;
    document.getElementById('comentario').value = '';
    document.getElementById('puntuacion-input').value = '';
    document.getElementById('error-puntuacion').classList.add('hidden');
    actualizarEstrellas();
}

function seleccionarEstrella(valor) {
    puntuacionSeleccionada = valor;
    document.getElementById('puntuacion-input').value = valor;
    document.getElementById('error-puntuacion').classList.add('hidden');
    actualizarEstrellas();
}

function actualizarEstrellas() {
    const botones = document.querySelectorAll('.estrella-btn');
    botones.forEach((boton, index) => {
        const svg = boton.querySelector('svg');
        if (index < puntuacionSeleccionada) {
            svg.setAttribute('fill', 'currentColor');
            svg.classList.remove('text-universo-text-muted');
            svg.classList.add('text-yellow-400');
        } else {
            svg.setAttribute('fill', 'none');
            svg.classList.add('text-universo-text-muted');
            svg.classList.remove('text-yellow-400');
        }
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Validar antes de enviar
    const formValoracion = document.getElementById('form-valoracion');
    if (formValoracion) {
        formValoracion.addEventListener('submit', function(e) {
            const puntuacion = document.getElementById('puntuacion-input').value;
            if (!puntuacion || puntuacion < 1 || puntuacion > 5) {
                e.preventDefault();
                document.getElementById('error-puntuacion').classList.remove('hidden');
                return false;
            }
        });
    }

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modal-valoracion');
            if (modal && modal.style.display === 'flex') {
                cerrarModalValoracion();
            }
        }
    });
});