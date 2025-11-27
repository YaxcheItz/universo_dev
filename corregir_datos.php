<?php

/**
 * Script para corregir datos existentes en la base de datos
 * Ejecutar con: php corregir_datos.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔧 Iniciando corrección de datos...\n\n";

try {
    // Verificar si la columna 'nombre' existe
    if (Schema::hasColumn('users', 'nombre')) {
        echo "⚠️  Encontrada columna 'nombre' en tabla users\n";
        echo "   Copiando datos de 'nombre' a 'name'...\n";
        
        // Copiar datos de 'nombre' a 'name' solo si 'name' está vacío o null
        $affected = DB::table('users')
            ->whereNull('name')
            ->orWhere('name', '')
            ->update(['name' => DB::raw('nombre')]);
        
        echo "   ✅ $affected registros actualizados\n\n";
    } else {
        echo "✅ La columna 'nombre' no existe, todo está correcto\n\n";
    }
    
    // Verificar datos
    $usuarios = DB::table('users')->select('id', 'name', 'email')->limit(5)->get();
    
    echo "📊 Primeros 5 usuarios en la base de datos:\n";
    foreach ($usuarios as $usuario) {
        echo "   - ID: {$usuario->id} | Name: {$usuario->name} | Email: {$usuario->email}\n";
    }
    
    echo "\n✅ Corrección completada exitosamente!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
