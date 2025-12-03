<!-- Modal de Valoración con Backdrop -->
<div id="modal-valoracion" class="fixed inset-0 hidden items-center justify-center z-50" style="display: none;">
    <!-- Backdrop con blur -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="cerrarModalValoracion()"></div>
    
    <!-- Modal Container -->
    <div class="relative bg-universo-card-bg border-2 border-universo-border rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all" onclick="event.stopPropagation()">
        <!-- Botón cerrar (X) -->
        <button 
            onclick="cerrarModalValoracion()" 
            class="absolute top-4 right-4 text-universo-text-muted hover:text-universo-text transition-colors"
            title="Cerrar"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-universo-text mb-2">Valorar Proyecto</h3>
            <p class="text-universo-text-muted" id="modal-proyecto-nombre"></p>
        </div>
        
        <!-- Formulario -->
        <form id="form-valoracion" method="POST" action="">
            @csrf
            <input type="hidden" name="proyecto_id" id="valoracion-proyecto-id">
            
            <!-- Estrellas para valorar -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-universo-text mb-3 text-center">
                    Puntuación <span class="text-red-400">*</span>
                </label>
                <div class="flex gap-3 justify-center" id="estrellas-valoracion">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="seleccionarEstrella({{ $i }})" class="estrella-btn transform transition-all hover:scale-125" data-valor="{{ $i }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-text-muted hover:text-yellow-400 transition-colors cursor-pointer">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="puntuacion" id="puntuacion-input" required>
                <p id="error-puntuacion" class="text-red-400 text-sm mt-2 text-center hidden">Por favor selecciona una puntuación</p>
            </div>
            
            <!-- Comentario opcional -->
            <div class="mb-6">
                <label for="comentario" class="block text-sm font-medium text-universo-text mb-2">
                    Comentario <span class="text-universo-text-muted">(opcional)</span>
                </label>
                <textarea 
                    name="comentario" 
                    id="comentario" 
                    rows="4" 
                    class="input-field w-full resize-none" 
                    placeholder="Comparte tu opinión sobre este proyecto..."
                    maxlength="500"
                ></textarea>
                <p class="text-xs text-universo-text-muted mt-1">Máximo 500 caracteres</p>
            </div>
            
            <!-- Botones -->
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="cerrarModalValoracion()" class="btn-secondary">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary" id="btn-enviar-valoracion">
                    Enviar Valoración
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Estilos adicionales para el modal -->
<style>
/* Animación de entrada del modal */
#modal-valoracion {
    animation: fadeIn 0.2s ease-out;
}

#modal-valoracion > div:last-child {
    animation: slideUp 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Backdrop blur mejorado */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
</style>

@push('scripts')
<script src="{{ asset('js/valoracion.js') }}"></script>
@endpush