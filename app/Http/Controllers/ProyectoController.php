<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use App\Models\ArchivoProyecto;
use Illuminate\Support\Facades\Storage;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $query = Proyecto::with('creador');

        // Si hay búsqueda, filtrar
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('lenguaje_principal', 'like', "%{$search}%");
            });
        }

        $proyectos = $query->paginate(9);

        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        // Obtener equipos donde el usuario autenticado es líder
        $equiposLiderados = Equipo::where('lider_id', Auth::id())->get();

        return view('proyectos.create', compact('equiposLiderados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lenguaje_principal' => 'required|string',
            'tecnologias' => 'nullable|array',
            'estado' => 'required|string',
            'equipo_id' => 'required|exists:equipos,id',
        ]);

        $validated['user_id'] = Auth::id();

        // Si seleccionó un equipo, verificar que sea líder
        if (isset($validated['equipo_id'])) {
            $equipo = Equipo::find($validated['equipo_id']);
            if ($equipo && $equipo->lider_id !== Auth::id()) {
                return back()->withErrors(['equipo_id' => 'Solo puedes asignar equipos donde eres líder'])->withInput();
            }
        }

        $proyecto = Proyecto::create($validated);

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto creado exitosamente');
    }

    public function show(Proyecto $proyecto)
    {
        $proyecto->load(['creador', 'equipo.lider', 'equipo.miembros']);

        $pending_files = $proyecto->archivos()->where('status', 'pending')->with('user')->get();
        $accepted_files = $proyecto->archivos()->where('status', 'accepted')->with('user')->get();

        $user = auth()->user();
        $isCreador = $user->id === $proyecto->user_id;
        $isMember = $proyecto->equipo ? $proyecto->equipo->esMiembro($user) : false;
        $canAccept = $isCreador || ($proyecto->equipo && $proyecto->equipo->esLider($user));

        return view('proyectos.show', compact('proyecto', 'pending_files', 'accepted_files', 'isMember', 'canAccept', 'isCreador'));
    }

    public function edit(Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este proyecto');
        }
        return view('proyectos.edit', compact('proyecto'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este proyecto');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lenguaje_principal' => 'required|string',
            'tecnologias' => 'nullable|array',
            'estado' => 'required|string',
        ]);

        $proyecto->update($validated);

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Proyecto actualizado exitosamente');
    }

    public function destroy(Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este proyecto');
        }

        $proyecto->delete();

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente');
    }

    public function uploadFile(Request $request, Proyecto $proyecto)
    {
        $user = $request->user();

        // Validar que sea miembro del equipo o el creador
        $isMember = $proyecto->equipo ? $proyecto->equipo->esMiembro($user) : false;
        $isCreador = $proyecto->user_id === $user->id;

        if (!$isMember && !$isCreador) {
            abort(403, 'No tienes permiso para subir archivos a este proyecto.');
        }

        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB
            'comentario' => 'nullable|string|max:500'
        ]);

        $file = $request->file('file');
        $path = $file->store("proyectos/{$proyecto->id}", 'public');

        $canAccept = $isCreador || ($proyecto->equipo && $proyecto->equipo->esLider($user));

        $archivo = ArchivoProyecto::create([
            'proyecto_id' => $proyecto->id,
            'user_id' => $user->id,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'status' => $canAccept ? 'accepted' : 'pending',
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Archivo subido correctamente.');
    }

    public function acceptFile(Request $request, Proyecto $proyecto, ArchivoProyecto $file)
    {
        $user = $request->user();

        // Solo creador o líder del equipo puede aceptar
        $canAccept = ($proyecto->user_id === $user->id) ||
                     ($proyecto->equipo && $proyecto->equipo->esLider($user));

        if (!$canAccept) {
            abort(403, 'No tienes permiso para aceptar archivos.');
        }

        $file->update(['status' => 'accepted']);

        return back()->with('success', 'Archivo aceptado.');
    }

    public function rejectFile(Request $request, Proyecto $proyecto, ArchivoProyecto $file)
    {
        $user = $request->user();

        $canReject = ($proyecto->user_id === $user->id) ||
                     ($proyecto->equipo && $proyecto->equipo->esLider($user));

        if (!$canReject) {
            abort(403, 'No tienes permiso para rechazar archivos.');
        }

        // Eliminar el archivo físicamente al rechazar
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('success', 'Archivo rechazado y eliminado.');
    }

    public function deleteFile(Request $request, Proyecto $proyecto, ArchivoProyecto $file)
    {
        $user = $request->user();

        // Puede eliminar: creador, líder, o el autor del archivo (si está pending)
        $canDelete = ($proyecto->user_id === $user->id) ||
                     ($proyecto->equipo && $proyecto->equipo->esLider($user)) ||
                     ($file->user_id === $user->id && $file->status === 'pending');

        if (!$canDelete) {
            abort(403, 'No tienes permiso para eliminar este archivo.');
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('success', 'Archivo eliminado (rollback realizado).');
    }
}