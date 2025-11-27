@echo off
echo ========================================
echo    LIMPIEZA COMPLETA DE LARAVEL
echo ========================================
echo.

echo [1/5] Limpiando cache de configuracion...
php artisan config:clear
echo.

echo [2/5] Limpiando cache de aplicacion...
php artisan cache:clear
echo.

echo [3/5] Limpiando cache de vistas...
php artisan view:clear
echo.

echo [4/5] Limpiando cache de rutas...
php artisan route:clear
echo.

echo [5/5] Regenerando cache de rutas...
php artisan route:cache
echo.

echo ========================================
echo Limpieza completada!
echo.
echo Ahora reinicia tu servidor:
echo 1. Presiona Ctrl+C para detener el servidor actual
echo 2. Ejecuta: php artisan serve
echo ========================================
pause
