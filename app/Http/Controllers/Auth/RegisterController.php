<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Mostrar formulario de registro
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar el registro de nuevo usuario
     */
    public function register(Request $request)
    {
        // Validar datos del formulario
        $validated = $request->validate([
            // Información Personal (requeridos)
            'name' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            
            // Credenciales (requeridos)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            
            // Información de Contacto (opcionales)
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:255'],
            'codigo_postal' => ['nullable', 'string', 'max:10'],
            'pais' => ['nullable', 'string', 'max:255'],
            
            // Información Profesional (requeridos)
            'rol' => ['required', 'string', 'in:Desarrollador,Team Leader,Product Manager,Designer,DevOps,QA Engineer,Data Scientist'],
            'github_username' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            
            // Otros
            'biografia' => ['nullable', 'string', 'max:1000'],
            'habilidades' => ['nullable', 'array'],
        ], [
            // Mensajes personalizados
            'name.required' => 'El nombre es obligatorio',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio',
            'apellido_materno.required' => 'El apellido materno es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ser un correo electrónico válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        // Crear el usuario
        $user = User::create([
            // Información Personal
            'name' => $validated['name'],
            'apellido_paterno' => $validated['apellido_paterno'],
            'apellido_materno' => $validated['apellido_materno'],
            
            // Credenciales
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            
            // Información de Contacto
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
            'ciudad' => $validated['ciudad'] ?? null,
            'estado' => $validated['estado'] ?? null,
            'codigo_postal' => $validated['codigo_postal'] ?? null,
            'pais' => $validated['pais'] ?? 'México',
            
            // Información Profesional
            'rol' => $validated['rol'],
            'username' => $validated['username'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            
            // Otros
            'biografia' => $validated['biografia'] ?? null,
            'habilidades' => $validated['habilidades'] ?? [],
            
            // Valores por defecto
            'puntos_total' => 0,
            'proyectos_completados' => 0,
            'torneos_ganados' => 0,
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redirigir al dashboard con mensaje de éxito
        return redirect()->route('dashboard')->with('success', '¡Bienvenido a UniversoDev, ' . $user->name . '!');
    }
}
