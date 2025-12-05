<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\User;
use App\Models\EquipoMiembro;
use App\Models\EquipoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudMiembro;
use App\Events\UserGroupStatusChanged;

class EquipoController extends Controller
{
    /**
 * Mostrar lista de equipos
 */
public function index(Request $request)
{
    $userId = Auth::id();

    // ========== MIS EQUIPOS DONDE SOY LÍDER ==========
    $misEquiposLider = Equipo::where('lider_id', $userId)
        ->with(['lider', 'miembros'])
        ->withCount('miembros')
        ->orderBy('created_at', 'desc')
        ->get();

    // ========== MIS EQUIPOS DONDE SOY MIEMBRO (NO LÍDER) ==========
    $misEquiposMiembro = Equipo::whereHas('miembros', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('lider_id', '!=', $userId) // Excluir equipos donde soy líder
        ->with(['lider', 'miembros'])
        ->withCount('miembros')
        ->orderBy('created_at', 'desc')
        ->get();

    // ========== EQUIPOS PÚBLICOS (EXCLUIR MIS EQUIPOS) ==========
    $misEquiposIds = $misEquiposLider->pluck('id')
        ->merge($misEquiposMiembro->pluck('id'))
        ->toArray();

    $query = Equipo::with(['lider', 'miembros'])
        ->withCount('miembros')
        ->activos()
        ->publicos()
        ->orderBy('created_at', 'desc');


    // Filtros de búsqueda
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('acepta_miembros') && $request->acepta_miembros == '1') {
        $query->aceptanMiembros();
    } elseif ($request->filled('acepta_miembros') && $request->acepta_miembros == '0') {
        $query->where('acepta_miembros', false);
    }

    $equipos = $query->paginate(12)->withQueryString();

    // ========== SOLICITUDES PENDIENTES (SI SOY LÍDER) ==========
    $solicitudesPendientes = collect();

    if (Auth::user()->esLiderDeAlguno()) {
        $solicitudesPendientes = SolicitudMiembro::with('user', 'equipo')
            ->where('estado', 'Pendiente')
            ->whereHas('equipo', function($q) use ($userId) {
                $q->where('lider_id', $userId);
            })
            ->get();
    }

    return view('equipos.index', compact(
        'equipos',
        'misEquiposLider',
        'misEquiposMiembro',
        'solicitudesPendientes'
    ));
}


    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Los jueces no pueden crear equipos
        if (Auth::user()->rol === 'Juez') {
            return redirect()->route('equipos.index')
                ->with('error', 'Los jueces no pueden crear equipos. Tu rol es evaluar proyectos.');
        }

        $rolesDisponibles = [
            'Programador Frontend',
            'Programador Backend',
            'Full-Stack',
            'Android',
            'iOS',
            'DevOps',
            'Data Scientist',
            'ML Engineer',
            'QA Engineer',
            'UI/UX Designer',
            'Product Manager',
            'Otro'
        ];

        // Obtener usuarios disponibles (excluir jueces)
        $usuarios = User::select('id', 'name', 'rol')
            ->whereNotIn('rol', ['Juez'])
            ->get();

        return view('equipos.create', compact('rolesDisponibles', 'usuarios'));
    }

    /**
     * Guardar nuevo equipo
     */
   public function store(Request $request)
    {
        // Los jueces no pueden crear equipos
        if (Auth::user()->rol === 'Juez') {
            return redirect()->route('equipos.index')
                ->with('error', 'Los jueces no pueden crear equipos. Tu rol es evaluar proyectos.');
        }


    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'avatar' => 'nullable|string',
        'max_miembros' => 'required|integer|min:1',
        'tecnologias' => 'nullable|string',  // viene como texto, no array
        'es_publico' => 'boolean',
        'acepta_miembros' => 'boolean',
        'yo_lider' => 'nullable|boolean',

        // Validación flexible de miembros
        'miembros' => 'nullable|array',
        'miembros.*.user_id' => 'nullable|exists:users,id',
        'miembros.*.rol_equipo' => 'nullable|string',
    ]);
    //evitar duplicados
    $miembrosIds = collect($validated['miembros'] ?? [])
    ->pluck('user_id')
    ->filter() // elimina null
    ->all();

    if (count($miembrosIds) !== count(array_unique($miembrosIds))) {
        return back()->withErrors([
            'miembros' => 'No puedes agregar el mismo usuario más de una vez.'
        ])->withInput();
    }

    /* Convertir tecnologías a array */
    $tecnologiasArray = [];
    if ($request->filled('tecnologias')) {
        $tecnologiasArray = array_map('trim', explode(',', $request->tecnologias));
    }

    $yoLider = $request->boolean('yo_lider', true);

    /* Si no sera el líder, validar que exista un líder en miembros */
    if (!$yoLider) {
        $lideres = collect($validated['miembros'] ?? [])
            ->where('rol_equipo', 'Líder de Equipo')
            ->whereNotNull('user_id');

        if ($lideres->count() !== 1) {
            return back()->withErrors([
                'miembros' => 'Debes seleccionar exactamente un líder del equipo.',
            ])->withInput();
        }

        $liderId = $lideres->first()['user_id'];
    } else {
        $liderId = Auth::id();
    }

    /* CREAR EQUIPO */
    $equipo = Equipo::create([
        'name' => $validated['name'],
        'descripcion' => $validated['descripcion'],
        'avatar' => $validated['avatar'] ?? null,
        'max_miembros' => $validated['max_miembros'],
        'tecnologias' => $tecnologiasArray,
        'es_publico' => $request->boolean('es_publico'),
        // Si los miembros iniciales + líder llenan el equipo, no aceptar más miembros
        'acepta_miembros' => (count($validated['miembros'] ?? []) + 1) < $validated['max_miembros'],
        'lider_id' => $liderId,
        'fecha_creacion' => now(),
        'miembros_actuales' => 0, // se irá sumando
    ]);


    /* AGREGAR MIEMBROS */

    // Caso: el creador será el líder
    if ($yoLider) {
        EquipoMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => Auth::id(),
            'rol_equipo' => 'Líder de Equipo',
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
        ]);

        $equipo->increment('miembros_actuales');
    }

    // Agregar miembros enviados
    if (isset($validated['miembros'])) {
        foreach ($validated['miembros'] as $miembro) {

            if (empty($miembro['user_id']) || empty($miembro['rol_equipo'])) {
                continue; // ignorar filas vacías
            }

            EquipoMiembro::create([
                'equipo_id' => $equipo->id,
                'user_id' => $miembro['user_id'],
                'rol_equipo' => $miembro['rol_equipo'],
                'fecha_ingreso' => now(),
                'estado' => 'Activo',
            ]);

            $equipo->increment('miembros_actuales');
        }
    }

    return redirect()->route('equipos.index')->with('success', 'Equipo creado con éxito.');
    }

    /**
     * Mostrar detalles del equipo
     */
   /**
 * Mostrar detalles del equipo
 */
    public function show(Equipo $equipo)
    {
        $equipo->load(['lider', 'miembros', 'proyectos', 'torneoParticipaciones.torneo']);

        // verificar si el usuario actual es miembro del equipo
        $esMiembro = $equipo->miembros->contains('id', Auth::id());
        $solicitudesPendientes = collect(); // Inicializar como colección vacía

        // Si el usuario actual es el líder del equipo, cargar las solicitudes pendientes
        if (Auth::id() === $equipo->lider_id) {
            $solicitudesPendientes = EquipoSolicitud::where('equipo_id', $equipo->id)
                ->where('estado', 'pendiente')
                ->with('usuario') // Cargar los datos del usuario que hizo la solicitud
                ->get();
        }
        
        // Roles disponibles para unirse al equipo
        $rolesDisponibles = [
            'Programador Frontend',
            'Programador Backend',
            'Full-Stack',
            'Android',
            'iOS',
            'DevOps',
            'Data Scientist',
            'ML Engineer',
            'QA Engineer',
            'UI/UX Designer',
            'Product Manager',
            'Otro'
        ];
        
        return view('equipos.show', compact('equipo', 'esMiembro', 'rolesDisponibles', 'solicitudesPendientes'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este equipo');
        }

        $rolesDisponibles = [
            
            'Programador Frontend',
            'Programador Backend',
            'Full-Stack',
            'Android',
            'iOS',
            'DevOps',
            'Data Scientist',
            'ML Engineer',
            'QA Engineer',
            'UI/UX Designer',
            'Product Manager',
            'Otro'
        ];
        $miembros = $equipo->miembros()->get();

        return view('equipos.edit', compact('equipo', 'rolesDisponibles', 'miembros'));
    }

    /**
     * Actualizar equipo
     */
    public function update(Request $request, Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este equipo');
        }

        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipos,name,' . $equipo->id,
            'descripcion' => 'nullable|string',
            'max_miembros' => 'required|integer|min:2|max:50',
            'tecnologias' => 'nullable|string',
            'estado' => 'required|string|in:Activo,Inactivo',
            'es_publico' => 'boolean',
            'acepta_miembros' => 'boolean',
            'miembros' => 'nullable|array',
            'miembros.*.rol_equipo' => 'nullable|string',
            'nuevo_miembro.user_id' => 'nullable|exists:users,id',
            'nuevo_miembro.rol_equipo' => 'nullable|string',
        ]);

        // Convertir tecnologías
        $tecnologiasArray = $request->filled('tecnologias') 
            ? array_map('trim', explode(',', $request->tecnologias)) 
            : [];

        // Actualizar datos básicos
        $equipo->update([
            'name' => $validated['name'],
            'descripcion' => $validated['descripcion'],
            'max_miembros' => $validated['max_miembros'],
            'tecnologias' => $tecnologiasArray,
            'estado' => $validated['estado'],
            'es_publico' => $request->boolean('es_publico'),
            'acepta_miembros' => $request->boolean('acepta_miembros'),
        ]);

        // --- Manejo de miembros existentes ---
        $liderId = $equipo->lider_id;

        if (!empty($validated['miembros'])) {
            foreach ($validated['miembros'] as $userId => $datos) {
                $rol = $datos['rol_equipo'] ?? 'Miembro';

                // No permitir cambiar al líder a otro miembro desde aquí
                if ($userId == $liderId && $rol !== 'Líder de Equipo') {
                    $rol = 'Líder de Equipo';
                }

                // Actualizar solo si el miembro existe
                if ($equipo->miembros->contains('id', $userId)) {
                    $equipo->miembros()->updateExistingPivot($userId, [
                        'rol_equipo' => $rol,
                        'estado' => 'Activo',
                    ]);
                }
            }
        }

        // --- Agregar nuevo miembro si se proporcionó ---
        if ($request->filled('nuevo_miembro.user_id') && $request->filled('nuevo_miembro.rol_equipo')) {
            $userId = $request->nuevo_miembro['user_id'];
            $rol = $request->nuevo_miembro['rol_equipo'];

            if (!$equipo->miembros->contains('id', $userId)) {
                $equipo->miembros()->attach($userId, [
                    'rol_equipo' => $rol,
                    'fecha_ingreso' => now(),
                    'estado' => 'Activo',
                ]);
            }
        }

        // Actualizar cantidad de miembros
        $equipo->miembros_actuales = $equipo->miembros()->count();
        $equipo->save();

        return redirect()->route('equipos.show', $equipo)
                        ->with('success', 'Equipo actualizado exitosamente');
    }

    /**
     * Eliminar equipo
     */
    public function destroy(Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este equipo');
        }

        $equipo->forceDelete(); 
        
        return redirect()->route('equipos.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }

 //este es para Seccion equipos-Magali

    public function solicitar(Request $request, Equipo $equipo)
    {
        if ($equipo->miembros_actuales >= $equipo->max_miembros) {
            return back()->with('error', 'El equipo está lleno');
        }

        if ($equipo->miembros->contains('id', Auth::id())) {
            return back()->with('error', 'Ya eres miembro de este equipo');
        }

        $request->validate([
            'rol_equipo' => 'required|string'
        ]);

        SolicitudMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => Auth::id(),
            'rol_equipo' => $request->rol_equipo,
            'estado' => 'Pendiente',
        ]);

        return back()->with('success', 'Solicitud enviada al líder del equipo');
    }

    /**
     * Aceptar o rechazar solicitud -seccion equipo--Magali
     */
    public function manejarSolicitud(Request $request, SolicitudMiembro $solicitud)
    {
        $equipo = $solicitud->equipo;
         $user = $solicitud->user;


        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'Solo el líder puede manejar solicitudes');
        }

        $request->validate([
            'accion' => 'required|in:Aceptar,Rechazar'
        ]);

        if ($request->accion === 'Aceptar') {
            $solicitud->estado = 'Aceptada';
            $solicitud->save();

            // Agregar al equipo
            EquipoMiembro::create([
                'equipo_id' => $equipo->id,
                'user_id' => $solicitud->user_id,
                'rol_equipo' => $solicitud->rol_equipo,
                'fecha_ingreso' => now(),
                'estado' => 'Activo',
            ]);

            $equipo->increment('miembros_actuales');
            event(new UserGroupStatusChanged($user, $equipo, 'accepted'));
        } else {
            $solicitud->estado = 'Rechazada';
            $solicitud->save();
            //notificacion rechazado
            event(new UserGroupStatusChanged($user, $equipo, 'rejected'));
        }

        return back()->with('success', 'Solicitud actualizada correctamente');
    }

    /**
     * Unirse a un equipo
     */
    public function unirse(Request $request, Equipo $equipo)
    {
        // Los jueces no pueden unirse a equipos
        if (Auth::user()->rol === 'Juez') {
            return back()->with('error', 'Los jueces no pueden unirse a equipos. Tu rol es evaluar proyectos.');
        }

        if (!$equipo->acepta_miembros) {
            return back()->with('error', 'Este equipo no está aceptando nuevos miembros');
        }

        if ($equipo->miembros_actuales >= $equipo->max_miembros) {
            return back()->with('error', 'El equipo está lleno');
        }

        if ($equipo->miembros->contains('id', Auth::id())) {
            return back()->with('error', 'Ya eres miembro de este equipo');
        }

        $request->validate([
            'rol_equipo' => 'required|string',
        ]);

        EquipoMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => Auth::id(),
            'rol_equipo' => $request->rol_equipo,
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
        ]);

        $equipo->increment('miembros_actuales');

        return back()->with('success', '¡Te has unido al equipo exitosamente!');
    }

    /**
     * Enviar una solicitud para unirse a un equipo.
     */
    public function solicitarUnirse(Request $request, Equipo $equipo)
    {
        $user = Auth::user();

        // Los jueces no pueden solicitar unirse a equipos
        if ($user->rol === 'Juez') {
            return back()->with('error', 'Los jueces no pueden unirse a equipos. Tu rol es evaluar proyectos.');
        }

        // Si viene de un contexto de torneo, validar que el usuario no esté en otro equipo del torneo
        if ($request->filled('torneo_id')) {
            $torneoId = $request->torneo_id;

            // Verificar que el usuario no esté ya en un equipo participante de este torneo
            $equiposDelUsuarioEnTorneo = $user->equipos()
                ->whereHas('torneoParticipaciones', function($query) use ($torneoId) {
                    $query->where('torneo_id', $torneoId);
                })
                ->exists();

            if ($equiposDelUsuarioEnTorneo) {
                return back()->with('error', 'Ya estás participando en este torneo con otro equipo. No puedes solicitar unirte a más equipos.');
            }

            // Verificar que el torneo tenga inscripciones abiertas
            $torneo = \App\Models\Torneo::find($torneoId);
            if ($torneo && $torneo->estado !== 'Inscripciones Abiertas') {
                return back()->with('error', 'Las inscripciones para este torneo no están abiertas.');
            }
        }

        // Validar que el equipo acepte miembros
        if (!$equipo->acepta_miembros) {
            return back()->with('error', 'Este equipo no está aceptando nuevos miembros actualmente.');
        }

        // Validar que el equipo no esté lleno
        if ($equipo->miembros_actuales >= $equipo->max_miembros) {
            return back()->with('error', 'Este equipo ya ha alcanzado su capacidad máxima de miembros.');
        }

        // Validar que el usuario no sea ya miembro del equipo
        if ($equipo->miembros->contains($user->id)) {
            return back()->with('error', 'Ya eres miembro de este equipo.');
        }

        // Validar que no exista ya una solicitud pendiente
        $solicitudExistente = EquipoSolicitud::where('user_id', $user->id)
            ->where('equipo_id', $equipo->id)
            ->where('estado', 'pendiente')
            ->exists();

        if ($solicitudExistente) {
            return back()->with('error', 'Ya has enviado una solicitud a este equipo. Por favor, espera a que el líder la revise.');
        }

        // Crear la solicitud
        EquipoSolicitud::create([
            'user_id' => $user->id,
            'equipo_id' => $equipo->id,
        ]);

        return back()->with('success', 'Tu solicitud para unirte al equipo ha sido enviada.');
    }

    /**
     * Agregar miembro (solo líder)
     */
    public function agregarMiembro(Request $request, Equipo $equipo)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'Solo el líder puede agregar miembros');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rol_equipo' => 'required|string'
        ]);

        // Verificar que el usuario a agregar no sea un juez
        $userToAdd = User::find($validated['user_id']);
        if ($userToAdd && $userToAdd->rol === 'Juez') {
            return back()->with('error', 'Los jueces no pueden ser parte de equipos. Su rol es evaluar proyectos.');
        }

        // No permitir agregar otro líder si ya existe uno
        if ($validated['rol_equipo'] === "Líder de Equipo") {
            return back()->withErrors([
                'rol_equipo' => 'No puedes asignar otro líder; ya existe un líder.'
            ]);
        }

        if ($equipo->miembros_actuales >= $equipo->max_miembros) {
            return back()->with('error', 'El equipo está lleno');
        }

        if ($equipo->miembros->contains('user_id', $validated['user_id'])) {
            return back()->with('error', 'Este usuario ya es miembro del equipo');
        }

        EquipoMiembro::create([
            'equipo_id' => $equipo->id,
            'user_id' => $validated['user_id'],
            'rol_equipo' => $validated['rol_equipo'],
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
        ]);

        $equipo->increment('miembros_actuales');

        return back()->with('success', 'Miembro agregado exitosamente');
    }



    /**
     * Remover miembro (solo líder)
     */
    public function removerMiembro(Equipo $equipo, User $user)
    {
        if ($equipo->lider_id !== Auth::id()) {
            abort(403, 'Solo el líder puede remover miembros');
        }

        if ($user->id === $equipo->lider_id) {
            return back()->with('error', 'No puedes removerte a ti mismo como líder');
        }

        if (!$equipo->miembros->contains('id', $user->id)) {
            return back()->with('error', 'Este usuario no es miembro del equipo');
        }

        // Remover al miembro
        $equipo->miembros()->detach($user->id);

        $equipo->decrement('miembros_actuales');

        return back()->with('success', 'Miembro removido del equipo');
    }
}
