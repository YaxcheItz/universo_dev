<div class="card flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-xl font-semibold text-universo-text flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-xml w-6 h-6 text-universo-purple"><path d="m18 16 4-4-4-4"></path><path d="m6 8-4 4 4 4"></path><path d="m14.5 4-5 16"></path></svg>
                {{ $proyecto->name }}
            </h3>
            @if($proyecto->lenguaje_principal)
                <span class="badge badge-purple">{{ $proyecto->lenguaje_principal }}</span>
            @endif
        </div>
        <p class="text-universo-text-muted text-sm mb-4">por {{ $proyecto->creador->name }}</p>
        <p class="text-universo-text-muted mb-4 text-sm">{{ Str::limit($proyecto->descripcion, 100) }}</p>
    </div>
    
    <div>
        <div class="flex items-center space-x-4 text-universo-text-muted text-sm mb-4">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-4 h-4 mr-1 text-yellow-500"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                {{ $proyecto->estrellas }}
            </div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-git-fork w-4 h-4 mr-1 text-green-500"><path d="M12 1v12"></path><path d="M12 12c-2.42 0-4-1.2-4-3s1.58-3 4-3c2.42 0 4 1.2 4 3s-1.58 3-4 3z"></path><path d="M12 12c-2.42 0-4-1.2-4-3s1.58-3 4-3c2.42 0 4 1.2 4 3s-1.58 3-4 3z" transform="rotate(180 12 12)"></path></svg>
                {{ $proyecto->forks }}
            </div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4 mr-1 text-blue-500"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                {{ $proyecto->contribuidores }}
            </div>
        </div>
        <p class="text-universo-text-muted text-xs">Actualizado el {{ $proyecto->updated_at->format('d/m/Y') }}</p>
    </div>
</div>