<!-- Modal de Valoración -->
<div id="modal-valoracion" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50" onclick="cerrarModalValoracion()">
    <div class="bg-universo-card-bg border border-universo-border rounded-lg p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <h3 class="text-xl font-bold text-universo-text mb-4">Valorar Proyecto</h3>
        <p class="text-universo-text-muted mb-4" id="modal-proyecto-nombre"></p>
        
        <form id="form-valoracion" method="POST" action="">
            @csrf
            <input type="hidden" name="proyecto_id" id="valoracion-proyecto-id">
            
            <!-- Estrellas para valorar -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-universo-text mb-2">
                    Puntuación <span class="text-red-400">*</span>
                </label>
                <div class="flex gap-2 justify-center" id="estrellas-valoracion">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="seleccionarEstrella({{ $i }})" class="estrella-btn" data-valor="{{ $i }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-universo-text-muted hover:text-yellow-400 transition-colors cursor-pointer">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="puntuacion" id="puntuacion-input" required>
                <p id="error-puntuacion" class="text-red-400 text-sm mt-1 hidden">Por favor selecciona una puntuación</p>
            </div>
            
            <!-- Comentario opcional -->
            <div class="mb-4">
                <label for="comentario" class="block text-sm font-medium text-universo-text mb-2">
                    Comentario <span class="text-universo-text-muted">(opcional)</span>
                </label>
                <textarea 
                    name="comentario" 
                    id="comentario" 
                    rows="3" 
                    class="input-field" 
                    placeholder="Comparte tu opinión sobre este proyecto..."
                    maxlength="500"
                ></textarea>
                <p class="text-xs text-universo-text-muted mt-1">Máximo 500 caracteres</p>
            </div>
            
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

@push('scripts')
<script src="{{ asset('js/valoracion.js') }}"></script>
@endpush