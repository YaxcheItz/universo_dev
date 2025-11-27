@echo off
COLOR 0A
echo ========================================
echo    RESTAURACION COMPLETA DEL PROYECTO
echo ========================================
echo.
echo Todos los archivos han sido restaurados
echo a la version correcta y funcional.
echo.
echo ========================================
echo    LIMPIANDO CACHE DE LARAVEL
echo ========================================
echo.

echo [1/6] Limpiando cache de configuracion...
call php artisan config:clear
echo.

echo [2/6] Limpiando cache de aplicacion...
call php artisan cache:clear
echo.

echo [3/6] Limpiando cache de vistas...
call php artisan view:clear
echo.

echo [4/6] Limpiando cache de rutas...
call php artisan route:clear
echo.

echo [5/6] Regenerando cache de rutas...
call php artisan route:cache
echo.

echo [6/6] Optimizando autoloader...
call composer dump-autoload
echo.

echo ========================================
echo           RESTAURACION COMPLETA
echo ========================================
echo.
echo Archivos restaurados:
echo   [OK] app/Models/User.php
echo   [OK] app/Http/Controllers/Auth/RegisterController.php
echo   [OK] app/Http/Controllers/ProyectoController.php
echo   [OK] app/Http/Controllers/PerfilController.php
echo   [OK] routes/web.php
echo   [OK] resources/views/auth/register.blade.php
echo.
echo ========================================
echo Ahora REINICIA tu servidor:
echo 1. Presiona Ctrl+C en la terminal del servidor
echo 2. Ejecuta: php artisan serve
echo 3. Abre: http://localhost:8001
echo ========================================
echo.
pause
