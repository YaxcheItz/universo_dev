@echo off
echo ========================================
echo    SOLUCION DE DATOS NO MOSTRADOS
echo ========================================
echo.

echo [1/4] Limpiando cache de Laravel...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo [2/4] Verificando base de datos...
php diagnostico.php
echo.

echo [3/4] Corrigiendo datos si es necesario...
php corregir_datos.php
echo.

echo [4/4] Todo listo!
echo.
echo ========================================
echo Ahora puedes iniciar tu servidor con:
echo php artisan serve
echo ========================================
pause
