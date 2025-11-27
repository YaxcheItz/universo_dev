<div class="card flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-6 h-6 text-universo-success"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg>
                <h3 class="text-xl font-semibold text-universo-text">{{ $equipo->name }}</h3>
            </div>
            <span class="text-universo-text-muted text-sm">{{ $equipo->miembros_actuales }} miembros</span>
        </div>
        <p class="text-universo-text-muted text-sm mb-4">ID: {{ $equipo->id }}</p>
        <p class="text-universo-text-muted mb-4 text-sm">{{ Str::limit($equipo->descripcion, 100) }}</p>
    </div>

    <div>
        <h4 class="text-universo-text font-semibold mb-2">Miembros del equipo</h4>
        <ul class="space-y-2">
            @forelse ($equipo->miembros as $miembro)
                <li class="flex items-center gap-2 text-universo-text text-sm">
                    <div class="w-7 h-7 rounded-full bg-universo-purple/20 flex items-center justify-center text-universo-text text-xs font-bold">
                        {{ substr($miembro->user->name, 0, 1) }}{{ substr($miembro->user->apellido_paterno, 0, 1) }}
                    </div>
                    <span>{{ $miembro->user->name }} {{ $miembro->user->apellido_paterno }}</span>
                    @if($miembro->rol_equipo == 'Líder de Equipo')
                        <span class="badge badge-purple text-xs">Líder</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield w-4 h-4 text-universo-warning ml-auto"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    @else
                        <span class="text-universo-text-muted text-xs">({{ $miembro->rol_equipo }})</span>
                    @endif
                </li>
            @empty
                <li class="text-universo-text-muted text-sm">No hay miembros en este equipo.</li>
            @endforelse
        </ul>
        <a href="{{ route('equipos.show', $equipo) }}" class="w-full btn-primary mt-4 block text-center">Ver Detalles</a>
    </div>
</div>