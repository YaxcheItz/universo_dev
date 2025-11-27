<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar conexión a la base de datos
try {
    DB::connection()->getPdo();
    echo "✅ Conexión a base de datos exitosa\n\n";
} catch (\Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit;
}

// Verificar cantidad de proyectos
$proyectos = DB::table('proyectos')->count();
echo "📊 Total de proyectos en BD: $proyectos\n\n";

// Verificar cantidad de usuarios
$usuarios = DB::table('users')->count();
echo "👥 Total de usuarios en BD: $usuarios\n\n";

// Verificar proyectos con datos
if ($proyectos > 0) {
    echo "🔍 Primeros 5 proyectos:\n";
    $primeros = DB::table('proyectos')->limit(5)->get();
    foreach ($primeros as $proyecto) {
        echo "  - ID: {$proyecto->id} | Name: {$proyecto->name} | User ID: {$proyecto->user_id}\n";
    }
    echo "\n";
}

// Verificar si hay proyectos sin creador
$sinCreador = DB::table('proyectos')
    ->whereNotIn('user_id', function($query) {
        $query->select('id')->from('users');
    })
    ->count();

if ($sinCreador > 0) {
    echo "⚠️  Hay $sinCreador proyectos con user_id que no existe en la tabla users\n\n";
} else {
    echo "✅ Todos los proyectos tienen un creador válido\n\n";
}

// Verificar si el modelo Proyecto funciona
echo "🧪 Probando modelo Proyecto:\n";
try {
    $proyectoModel = \App\Models\Proyecto::with('creador')->first();
    if ($proyectoModel) {
        echo "  - Proyecto encontrado: {$proyectoModel->name}\n";
        echo "  - Creador: " . ($proyectoModel->creador ? $proyectoModel->creador->name : 'NO ENCONTRADO') . "\n";
    } else {
        echo "  - No hay proyectos para probar\n";
    }
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}
