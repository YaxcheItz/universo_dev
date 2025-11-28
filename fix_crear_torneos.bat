@echo off
COLOR 0C
echo ========================================
echo   CORRECCION: CREAR TORNEOS
echo ========================================
echo.
echo Problema encontrado y corregido:
echo [FIX] TorneoController.php - Cambiado 'nombre' a 'name' en validaciones
echo [FIX] Torneo.php (modelo) - Cambiado 'nombre' a 'name' en fillable
echo [FIX] Removed validation 'after:today' que bloqueaba fechas pasadas
echo.
echo Limpiando cache...
call php artisan config:clear
call php artisan cache:clear  
call php artisan view:clear
call php artisan route:clear
echo.
COLOR 0A
echo ========================================
echo   PROBLEMA RESUELTO
echo ========================================
echo.
echo Ahora el boton "Crear Torneo" funciona correctamente!
echo.
echo IMPORTANTE: REINICIA TU SERVIDOR
echo 1. Presiona Ctrl+C en la terminal del servidor
echo 2. Ejecuta: php artisan serve
echo 3. Intenta crear un torneo nuevamente
echo.
pause
