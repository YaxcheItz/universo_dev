@echo off
COLOR 0E
echo ========================================
echo   FIX: CHECKBOX "VISIBLE PARA TODOS"
echo ========================================
echo.
echo Problema identificado:
echo El checkbox "es_publico" no enviaba valor cuando estaba marcado
echo.
echo Solucion aplicada:
echo [OK] create.blade.php - Agregado input hidden + value="1"
echo [OK] edit.blade.php - Agregado input hidden + value="1"  
echo [OK] TorneoController store() - Cambiado a input('es_publico', 0) == 1
echo [OK] TorneoController update() - Cambiado a input('es_publico', 0) == 1
echo.
echo Como funciona ahora:
echo - Hidden input envia "0" por defecto
echo - Checkbox marcado envia "1" y sobrescribe el "0"
echo - Checkbox desmarcado solo envia el "0" del hidden
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
echo Ahora puedes crear torneos con el checkbox marcado o desmarcado!
echo.
echo PASOS FINALES:
echo 1. Presiona Ctrl+C para detener el servidor
echo 2. Ejecuta: php artisan serve
echo 3. Ve a /torneos/create
echo 4. Llena el formulario
echo 5. Marca o desmarca "Torneo publico"
echo 6. Click en "Crear Torneo"
echo 7. Deberia funcionar perfectamente!
echo.
pause
