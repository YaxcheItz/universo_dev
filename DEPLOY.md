# Despliegue en Render

Esta guía te ayudará a desplegar tu aplicación Laravel en Render.

## Requisitos Previos

1. Una cuenta en [Render](https://render.com) (gratis)
2. Tu código subido a GitHub en el repositorio: https://github.com/YaxcheItz/universo_dev

## Pasos para Desplegar

### 1. Preparar el Repositorio

Asegúrate de que los siguientes archivos estén en tu repositorio:
- `build.sh` - Script de construcción
- `start.sh` - Script de inicio
- `render.yaml` - Configuración de Render

### 2. Conectar con Render

1. Ve a [Render Dashboard](https://dashboard.render.com/)
2. Haz clic en **"New +"** y selecciona **"Blueprint"**
3. Conecta tu repositorio de GitHub: `YaxcheItz/universo_dev`
4. Render detectará automáticamente el archivo `render.yaml`
5. Haz clic en **"Apply"**

### 3. Configurar Variables de Entorno (Importante)

Render creará automáticamente las variables de entorno del `render.yaml`, pero necesitas generar una `APP_KEY`:

1. En tu computadora local, ejecuta:
   ```bash
   php artisan key:generate --show
   ```

2. Copia la key generada (algo como: `base64:xxxxxxxxxxxxx`)

3. En Render Dashboard:
   - Ve a tu servicio web
   - Click en **"Environment"** en el menú lateral
   - Busca la variable `APP_KEY`
   - Pega la key que generaste
   - Haz clic en **"Save Changes"**

### 4. Esperar el Despliegue

Render automáticamente:
- Instalará las dependencias de PHP (Composer)
- Instalará las dependencias de Node (npm)
- Compilará los assets con Vite
- Ejecutará las migraciones de base de datos
- Iniciará tu aplicación

El primer despliegue puede tomar entre 5-10 minutos.

### 5. Verificar el Despliegue

Una vez completado, Render te dará una URL como:
```
https://universo-dev.onrender.com
```

Visita esa URL para ver tu aplicación funcionando.

## Configuración Incluida

El archivo `render.yaml` está configurado con:

- **Runtime**: PHP
- **Plan**: Free (gratis)
- **Base de datos**: SQLite
- **Sesiones**: Cookie
- **Cache**: File system
- **Modo producción**: APP_DEBUG=false

## Actualizaciones Automáticas

Cada vez que hagas `git push` a la rama principal de GitHub, Render automáticamente:
1. Detectará los cambios
2. Ejecutará el build
3. Desplegará la nueva versión

## Solución de Problemas

### Error: "Permission denied" en build.sh o start.sh

Si ves este error, asegúrate de que los scripts tengan permisos de ejecución:

```bash
chmod +x build.sh start.sh
git add build.sh start.sh
git commit -m "Fix: Add execution permissions to scripts"
git push
```

### Error: "APP_KEY not set"

Genera una nueva key y agrégala en las variables de entorno de Render:

```bash
php artisan key:generate --show
```

### Error en migraciones

Si las migraciones fallan, puedes ejecutarlas manualmente desde el Shell de Render:
1. Ve a tu servicio en Render Dashboard
2. Click en **"Shell"** en el menú lateral
3. Ejecuta: `php artisan migrate --force`

### Archivos estáticos no se cargan

Asegúrate de que el build haya completado correctamente:
```bash
npm run build
```

Verifica que la carpeta `public/build` exista en el build log.

## Notas Importantes

- El plan gratuito de Render puede "dormir" después de 15 minutos de inactividad
- La primera visita después de que duerma puede tardar 30-60 segundos en despertar
- SQLite no es ideal para producción con múltiples usuarios, pero funciona bien para proyectos escolares
- Los archivos subidos se perderán en cada deploy (usa storage externo como S3 para archivos permanentes)

## Recursos

- [Documentación de Render](https://render.com/docs)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Render Community](https://community.render.com/)

## Soporte

Si tienes problemas, revisa los logs en Render Dashboard:
1. Ve a tu servicio
2. Click en **"Logs"** en el menú lateral
3. Busca mensajes de error
