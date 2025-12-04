@extends('layouts.app')

@section('title', 'Crear Equipo - UniversoDev')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-universo-text mb-2">Crear Equipo</h1>
        <p class="text-universo-text-muted">Llena los datos para crear tu nuevo equipo</p>
    </div>

    @if ($errors->any())
    <div class="bg-red-500/20 border border-red-400 text-red-300 p-4 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('equipos.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Datos básicos -->
        <div class="space-y-4">
            <div>
                <label for="name" class="label">Nombre del Equipo</label>
                <input type="text" name="name" class="input-field w-full" placeholder="Nombre del equipo" required>
            </div>

            <div>
                <label for="descripcion" class="label">Descripción</label>
                <textarea name="descripcion" class="input-field w-full" rows="3" placeholder="Descripción"></textarea>
            </div>
            
            <div>
                <label for="max_miembros" class="label">Máximo de miembros</label>
                <input type="number" name="max_miembros" class="input-field w-full"
                    min="2" max="50" value="5" required>
            </div>
            
            <div>
                <label for="tecnologias" class="label">Tecnologías</label>
                <input type="text" name="tecnologias" class="input-field w-full"
                    placeholder="Tecnologías (separadas por coma)">
            </div>
            
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="es_publico" value="1" @checked(old('es_publico'))>
                    Público
                </label>
            </div>
        </div>

        <!-- Tabla de Miembros -->
        <div class="mt-8">
            <label class="label mb-4 block">Miembros del Equipo</label>
            
            <div class="overflow-visible mb-48">
                <table class="w-full border-collapse table-fixed">
                    <thead>
                        <tr class="bg-universo-dark/30">
                            <th class="text-left p-4 text-sm font-semibold text-universo-text w-2/5">Usuario</th>
                            <th class="text-left p-4 text-sm font-semibold text-universo-text w-2/5">Rol</th>
                            <th class="text-center p-4 text-sm font-semibold text-universo-text w-1/5">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="miembros-container" class="divide-y divide-universo-border/50">
                        <tr class="miembro-row">
                            <!-- Usuario -->
                            <td class="p-4">
                                <div class="relative">
                                    <input type="text" class="input-field usuario-input w-full" placeholder="@usuario">
                                    <input type="hidden" name="miembros[0][user_id]" class="user-id">
                                    
                                    <!-- Sugerencias -->
                                    <div class="sugerencias hidden absolute bg-universo-secondary border border-universo-border
                                                z-50 mt-1 rounded shadow-lg max-h-48 overflow-y-auto left-0 top-full w-full"></div>
                                </div>
                                <!-- Mensajes FUERA del div relative -->
                                <p class="text-sm text-red-400 hidden error-msg mt-1">Usuario no encontrado</p>
                                <p class="text-sm text-red-400 hidden usuario-obligatorio mt-1">
                                    ⚠️ Debes seleccionar un usuario
                                </p>
                            </td>

                            <!-- Rol -->
                            <td class="p-4">
                                <div class="relative">
                                    <!-- ✅ CORREGIDO: agregado name aquí -->
                                    <select class="input-field w-full rol-select" name="miembros[0][rol_equipo]">
                                        <option value="">Selecciona un rol</option>
                                        @foreach($rolesDisponibles as $rol)
                                            <option value="{{ $rol }}">{{ $rol }}</option>
                                        @endforeach
                                    </select>

                                    <input type="text"
                                        class="input-field w-full absolute inset-0 hidden otro-rol-input"
                                        placeholder="Especifica el rol">
                                </div>
                                <!-- Mensaje FUERA del div relative -->
                                <p class="text-sm text-red-400 hidden rol-obligatorio mt-1">
                                    ⚠️ Debes seleccionar un rol
                                </p>
                            </td>

                            <!-- Acción -->
                            <td class="p-4 text-center">
                                <button type="button"
                                        class="btn-secondary remove-btn px-4 py-2">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" id="agregar-miembro" class="btn-secondary mt-4">
                + Agregar miembro
            </button>
            <p id="miembros-error" class="text-sm text-red-500 mt-2 hidden">
            ¡Atención! No puedes agregar más miembros, se alcanzó el máximo. Cambia el campo máximo de miembros.
            </p>

        </div>

        <div class="pt-4 flex gap-3">
            <button type="submit" class="btn-primary">Crear Equipo</button>
            <a href="{{ route('equipos.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
const miembrosContainer = document.getElementById('miembros-container');
const agregarBtn = document.getElementById('agregar-miembro');
const roles = @json($rolesDisponibles ?? []);
let index = 1; // Para crear nombres únicos en inputs

/* ───── debounce ───── */
const debounce = (fn, delay = 300) => {
    let t;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), delay);
    };
};

/* ───── buscar usuarios ───── */
async function buscarUsuarios(input) {
    const value = input.value.trim();
    const row = input.closest('.miembro-row');
    const box = row.querySelector('.sugerencias');
    const hidden = row.querySelector('.user-id');
    const error = row.querySelector('.error-msg');

    hidden.value = '';
    error.classList.add('hidden');

    if (!value.startsWith('@') || value.length < 2) {
        box.classList.add('hidden');
        return;
    }

    try {
        const res = await fetch(`/usuarios/buscar?q=${value}`);
        const users = await res.json();

        box.innerHTML = '';
        if (!users.length) {
            error.classList.remove('hidden');
            box.classList.add('hidden');
            return;
        }

        users.forEach(u => {
            const div = document.createElement('div');
            div.className = 'px-3 py-2 cursor-pointer hover:bg-universo-hover';
            div.textContent = `@${u.name}`;
            div.onclick = () => {
                input.value = `@${u.name}`;
                hidden.value = u.id;
                box.classList.add('hidden');
                row.querySelector('.usuario-obligatorio').classList.add('hidden');
            };
            box.appendChild(div);
        });

        box.classList.remove('hidden');
    } catch {}
}

/* escuchar inputs de usuario */
document.addEventListener('input', debounce(e => {
    if (e.target.classList.contains('usuario-input')) {
        buscarUsuarios(e.target);
    }
}));

/* cerrar sugerencias al hacer click fuera */
document.addEventListener('click', (e) => {
    if (!e.target.classList.contains('usuario-input')) {
        document.querySelectorAll('.sugerencias').forEach(box => box.classList.add('hidden'));
    }
});

/* manejar "Otro" en rol */
function manejarRolOtro(row, idx) {
    const select = row.querySelector('.rol-select');
    const input = row.querySelector('.otro-rol-input');
    const rolError = row.querySelector('.rol-obligatorio');

    select.addEventListener('change', () => {
        if (select.value === 'Otro') {
            input.classList.remove('hidden');
            input.required = true;
            input.name = `miembros[${idx}][rol_equipo]`;
            select.name = '';
        } else {
            input.classList.add('hidden');
            input.value = '';
            input.required = false;
            select.name = `miembros[${idx}][rol_equipo]`;
            input.name = '';
        }

        if (select.value !== '') rolError.classList.add('hidden');
    });

    input.addEventListener('input', () => {
        if (input.value.trim() !== '') rolError.classList.add('hidden');
    });
}

/* inicializar fila inicial */
manejarRolOtro(document.querySelector('.miembro-row'), 0);

/* eliminar fila inicial */
document.querySelector('.miembro-row .remove-btn').addEventListener('click', function() {
    this.closest('.miembro-row').remove();
    // Habilitar botón si se eliminó una fila
    agregarBtn.disabled = false;
});

/* ───── agregar nuevas filas ───── */
agregarBtn.addEventListener('click', () => {
    const maxMiembros = parseInt(document.querySelector('input[name="max_miembros"]').value);
    const filasActuales = miembrosContainer.querySelectorAll('.miembro-row').length;
    const errorMsg = document.getElementById('miembros-error');

    if (filasActuales >= maxMiembros-1) {
        errorMsg.classList.remove('hidden'); // mostrar mensaje
        return; // detener la acción
    }

    // Si se puede agregar, ocultar mensaje por si estaba visible
    errorMsg.classList.add('hidden');

    const tr = document.createElement('tr');
    tr.className = 'miembro-row';
    tr.innerHTML = `
        <td class="p-4">
            <div class="relative">
                <input type="text" class="input-field usuario-input w-full" placeholder="@usuario">
                <input type="hidden" name="miembros[${index}][user_id]" class="user-id">
                <div class="sugerencias hidden absolute bg-universo-secondary border border-universo-border
                            z-50 mt-1 rounded shadow-lg max-h-48 overflow-y-auto left-0 top-full w-full"></div>
            </div>
            <p class="text-sm text-red-400 hidden error-msg mt-1">Usuario no encontrado</p>
            <p class="text-sm text-red-400 hidden usuario-obligatorio mt-1"> Debes seleccionar un usuario</p>
        </td>

        <td class="p-4">
            <div class="relative">
                <select class="input-field w-full rol-select" name="miembros[${index}][rol_equipo]">
                    <option value="">Selecciona un rol</option>
                    ${roles.map(r => `<option value="${r}">${r}</option>`).join('')}
                </select>
                <input type="text" class="input-field w-full absolute inset-0 hidden otro-rol-input" placeholder="Especifica el rol">
            </div>
            <p class="text-sm text-red-400 hidden rol-obligatorio mt-1"> Debes seleccionar un rol</p>
        </td>

        <td class="p-4 text-center">
            <button type="button" class="btn-secondary remove-btn px-4 py-2">Eliminar</button>
        </td>
    `;

    miembrosContainer.appendChild(tr);
    manejarRolOtro(tr, index);

    tr.querySelector('.remove-btn').addEventListener('click', () => {
        tr.remove();
        agregarBtn.disabled = false;
        errorMsg.classList.add('hidden'); // ocultar mensaje al eliminar
    });

    index++;

    if (miembrosContainer.querySelectorAll('.miembro-row').length >= maxMiembros - 1) {
        agregarBtn.disabled = true;
        errorMsg.classList.remove('hidden'); // mostrar mensaje si se llegó al máximo
    }
});

/* ───── VALIDACIÓN ANTES DE ENVIAR ───── */
const form = document.querySelector('form');
form.addEventListener('submit', function (e) {
    let valido = true;

    document.querySelectorAll('.usuario-obligatorio, .rol-obligatorio').forEach(msg => msg.classList.add('hidden'));

    document.querySelectorAll('.miembro-row').forEach(row => {
        const userId = row.querySelector('.user-id');
        const usuarioInput = row.querySelector('.usuario-input');
        const rolSelect = row.querySelector('.rol-select');
        const otroRol = row.querySelector('.otro-rol-input');
        const userError = row.querySelector('.usuario-obligatorio');
        const rolError = row.querySelector('.rol-obligatorio');

        const usuarioInputLleno = usuarioInput.value.trim() !== '';
        const tieneUsuario = userId.value.trim() !== '';
        let tieneRol = false;

        if (!otroRol.classList.contains('hidden')) {
            tieneRol = otroRol.value.trim() !== '';
        } else if (rolSelect.value !== '') {
            tieneRol = true;
        }

        const filaVacia = !tieneUsuario && !usuarioInputLleno && !tieneRol;
        if (filaVacia) return;

        if (usuarioInputLleno && !tieneUsuario) {
            userError.textContent = 'Debes seleccionar un usuario de la lista';
            userError.classList.remove('hidden');
            valido = false;
        }

        if (tieneUsuario && !tieneRol) {
            rolError.classList.remove('hidden');
            valido = false;
        }

        if (!tieneUsuario && tieneRol) {
            userError.textContent = 'Debes seleccionar un usuario';
            userError.classList.remove('hidden');
            valido = false;
        }
    });

    if (!valido) {
        e.preventDefault();
        alert('Revisa los miembros: usuario y rol deben ir juntos');

        const primerError = document.querySelector('.usuario-obligatorio:not(.hidden), .rol-obligatorio:not(.hidden)');
        if (primerError) primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    // Usuario
    usuarioInput.addEventListener('input', () => {
        row.querySelector('.usuario-obligatorio').classList.add('hidden');
    });

    // Rol "Otro"
    otroRol.addEventListener('input', () => {
        row.querySelector('.rol-obligatorio').classList.add('hidden');
    });
    
});

document.querySelectorAll('.miembro-row').forEach(row => {
    const userId = row.querySelector('.user-id').value.trim();
    const usuarioInput = row.querySelector('.usuario-input').value.trim();
    const rolSelect = row.querySelector('.rol-select').value.trim();
    const otroRol = row.querySelector('.otro-rol-input');
    const otroRolValue = otroRol.value.trim();
    const userError = row.querySelector('.usuario-obligatorio');
    const rolError = row.querySelector('.rol-obligatorio');

    // Si hay algo en la fila, validar ambos
    if (usuarioInput || rolSelect || otroRolValue) {
        // Validar usuario
        if (!userId) {
            userError.textContent = 'Debes seleccionar un usuario';
            userError.classList.remove('hidden');
            valido = false;
        }

        // Validar rol
        if (rolSelect === '' && otroRolValue === '') {
            rolError.classList.remove('hidden');
            valido = false;
        }
    }
});

</script>

@endsection