<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $torneo->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@400;600;700&display=swap');

        @page {
            margin: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 25%, #7e22ce 50%, #9333ea 75%, #c026d3 100%);
            position: relative;
            overflow: hidden;
        }

        /* Patrón de fondo decorativo */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255, 215, 0, 0.08) 0%, transparent 50%),
                repeating-linear-gradient(45deg, transparent, transparent 100px, rgba(255, 255, 255, 0.02) 100px, rgba(255, 255, 255, 0.02) 200px);
            z-index: 1;
        }

        .certificado {
            width: 100%;
            height: 100vh;
            padding: 50px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        /* Esquinas decorativas */
        .esquina {
            position: absolute;
            width: 150px;
            height: 150px;
            border: 4px solid rgba(255, 215, 0, 0.6);
        }

        .esquina-tl {
            top: 30px;
            left: 30px;
            border-right: none;
            border-bottom: none;
        }

        .esquina-tr {
            top: 30px;
            right: 30px;
            border-left: none;
            border-bottom: none;
        }

        .esquina-bl {
            bottom: 30px;
            left: 30px;
            border-right: none;
            border-top: none;
        }

        .esquina-br {
            bottom: 30px;
            right: 30px;
            border-left: none;
            border-top: none;
        }

        /* Estrellas decorativas */
        .estrella {
            position: absolute;
            color: #FFD700;
            font-size: 24px;
            opacity: 0.7;
            animation: twinkle 2s infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }

        .estrella-1 { top: 60px; left: 200px; animation-delay: 0s; }
        .estrella-2 { top: 60px; right: 200px; animation-delay: 0.5s; }
        .estrella-3 { bottom: 60px; left: 180px; animation-delay: 1s; }
        .estrella-4 { bottom: 60px; right: 180px; animation-delay: 1.5s; }
        .estrella-5 { top: 150px; left: 100px; animation-delay: 0.3s; font-size: 16px; }
        .estrella-6 { top: 150px; right: 100px; animation-delay: 0.8s; font-size: 16px; }

        /* Bordes decorativos */
        .border-principal {
            position: absolute;
            top: 35px;
            left: 35px;
            right: 35px;
            bottom: 35px;
            border: 5px solid #FFD700;
            box-shadow:
                inset 0 0 40px rgba(255, 215, 0, 0.3),
                0 0 40px rgba(255, 215, 0, 0.2);
        }

        .border-secundario {
            position: absolute;
            top: 45px;
            left: 45px;
            right: 45px;
            bottom: 45px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .contenido {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 80px;
        }

        /* Logo y título */
        .logo-container {
            margin-bottom: 15px;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 900;
            color: #FFD700;
            text-shadow:
                3px 3px 6px rgba(0,0,0,0.4),
                0 0 20px rgba(255, 215, 0, 0.5);
            letter-spacing: 3px;
            position: relative;
            display: inline-block;
        }

        .logo::before,
        .logo::after {
            content: '★';
            position: absolute;
            color: #FFD700;
            font-size: 24px;
            top: 50%;
            transform: translateY(-50%);
        }

        .logo::before { left: -40px; }
        .logo::after { right: -40px; }

        .titulo {
            font-family: 'Playfair Display', serif;
            font-size: 64px;
            font-weight: 900;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 8px;
            background: linear-gradient(180deg, #FFD700 0%, #FFA500 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            position: relative;
        }

        /* Líneas decorativas */
        .linea-decorativa {
            width: 300px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #FFD700 50%, transparent 100%);
            margin: 20px auto;
            position: relative;
        }

        .linea-decorativa::before,
        .linea-decorativa::after {
            content: '◆';
            position: absolute;
            color: #FFD700;
            font-size: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .linea-decorativa::before { left: -30px; }
        .linea-decorativa::after { right: -30px; }

        .subtitulo {
            font-size: 22px;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Nombre del usuario */
        .nombre-container {
            margin: 25px 0;
            position: relative;
        }

        .nombre-usuario {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            padding: 25px 50px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.15) 100%);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            color: white;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
            letter-spacing: 2px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            display: inline-block;
        }

        .nombre-usuario::before,
        .nombre-usuario::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 60px;
            border: 3px solid #FFD700;
        }

        .nombre-usuario::before {
            top: -15px;
            left: -15px;
            border-right: none;
            border-bottom: none;
        }

        .nombre-usuario::after {
            bottom: -15px;
            right: -15px;
            border-left: none;
            border-top: none;
        }

        /* Medallas y reconocimientos */
        .reconocimiento-container {
            margin: 30px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .medalla-grande {
            font-size: 100px;
            margin-bottom: 10px;
            filter: drop-shadow(0 8px 16px rgba(0,0,0,0.3));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .lugar {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 900;
            color: #FFD700;
            margin: 15px 0;
            text-transform: uppercase;
            letter-spacing: 4px;
            text-shadow:
                3px 3px 6px rgba(0,0,0,0.4),
                0 0 20px rgba(255, 215, 0, 0.4);
        }

        .badge-excelencia {
            display: inline-block;
            padding: 8px 25px;
            background: rgba(255, 215, 0, 0.2);
            border: 2px solid #FFD700;
            border-radius: 25px;
            color: #FFD700;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        /* Texto principal */
        .texto-principal {
            font-size: 19px;
            margin: 18px 0;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.95);
            max-width: 800px;
            font-weight: 400;
        }

        .equipo-nombre {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #FFD700;
            margin: 18px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }

        .torneo-nombre {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin: 18px 0;
            font-style: italic;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Estadísticas */
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin: 25px 0;
        }

        .stat-item {
            text-align: center;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #FFD700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Fecha y sello */
        .footer-section {
            margin-top: 40px;
            width: 100%;
        }

        .fecha-container {
            margin-bottom: 35px;
        }

        .fecha-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .fecha {
            font-size: 18px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
        }

        /* Firmas */
        .firma-seccion {
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 750px;
            margin: 0 auto;
        }

        .firma {
            text-align: center;
            flex: 1;
        }

        .firma-linea {
            width: 220px;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.6) 50%, transparent 100%);
            margin: 0 auto 10px;
        }

        .firma-nombre {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin-bottom: 3px;
        }

        .firma-cargo {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Sello de autenticidad */
        .sello {
            position: absolute;
            bottom: 70px;
            right: 100px;
            width: 120px;
            height: 120px;
            border: 4px solid rgba(255, 215, 0, 0.4);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(255, 215, 0, 0.1);
            transform: rotate(-15deg);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        .sello-texto {
            font-size: 11px;
            font-weight: 700;
            color: #FFD700;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.3;
        }

        .sello-icono {
            font-size: 32px;
            margin-bottom: 5px;
        }

        /* Código de verificación */
        .codigo-verificacion {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="certificado">
        <!-- Esquinas decorativas -->
        <div class="esquina esquina-tl"></div>
        <div class="esquina esquina-tr"></div>
        <div class="esquina esquina-bl"></div>
        <div class="esquina esquina-br"></div>

        <!-- Estrellas decorativas -->
        <div class="estrella estrella-1">★</div>
        <div class="estrella estrella-2">★</div>
        <div class="estrella estrella-3">★</div>
        <div class="estrella estrella-4">★</div>
        <div class="estrella estrella-5">✦</div>
        <div class="estrella estrella-6">✦</div>

        <!-- Bordes -->
        <div class="border-principal"></div>
        <div class="border-secundario"></div>

        <!-- Contenido -->
        <div class="contenido">
            <div class="logo-container">
                <div class="logo">UniversoDev</div>
            </div>

            <div class="titulo">Certificado</div>
            <div class="linea-decorativa"></div>

            <div class="subtitulo">Se otorga el presente reconocimiento a:</div>

            <div class="nombre-container">
                <div class="nombre-usuario">{{ $usuario->name }}</div>
            </div>

            <div class="reconocimiento-container">
                @if($participacion->posicion == 1)
                    <div class="medalla-grande">🥇</div>
                    <div class="lugar">Primer Lugar</div>
                    <div class="badge-excelencia">★ Excelencia en Programación ★</div>
                @elseif($participacion->posicion == 2)
                    <div class="medalla-grande">🥈</div>
                    <div class="lugar">Segundo Lugar</div>
                    <div class="badge-excelencia">★ Destacada Participación ★</div>
                @elseif($participacion->posicion == 3)
                    <div class="medalla-grande">🥉</div>
                    <div class="lugar">Tercer Lugar</div>
                    <div class="badge-excelencia">★ Mérito en Desarrollo ★</div>
                @endif
            </div>

            <div class="texto-principal">
                Por su destacada participación y excelente desempeño como miembro del equipo
            </div>

            <div class="equipo-nombre">{{ $equipo->name }}</div>

            <div class="texto-principal">
                en el torneo de programación
            </div>

            <div class="torneo-nombre">"{{ $torneo->name }}"</div>

            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-label">Posición</div>
                    <div class="stat-value">#{{ $participacion->posicion }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Puntaje Final</div>
                    <div class="stat-value">{{ number_format($participacion->puntaje_total, 1) }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Miembros Equipo</div>
                    <div class="stat-value">{{ $equipo->miembros_actuales }}</div>
                </div>
            </div>

            <div class="footer-section">
                <div class="fecha-container">
                    <div class="fecha-label">Fecha de Emisión</div>
                    <div class="fecha">{{ $fecha_finalizacion }}</div>
                </div>

                <div class="firma-seccion">
                    <div class="firma">
                        <div class="firma-linea"></div>
                        <div class="firma-nombre">{{ $torneo->organizador->name ?? 'Organizador' }}</div>
                        <div class="firma-cargo">Organizador del Torneo</div>
                    </div>
                    <div class="firma">
                        <div class="firma-linea"></div>
                        <div class="firma-nombre">UniversoDev</div>
                        <div class="firma-cargo">Dirección General</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sello de autenticidad -->
        <div class="sello">
            <div class="sello-icono">✓</div>
            <div class="sello-texto">Certificado<br>Oficial<br>{{ date('Y') }}</div>
        </div>

        <!-- Código de verificación -->
        <div class="codigo-verificacion">
            ID: CERT-{{ strtoupper(substr(md5($torneo->id . $equipo->id . $usuario->id), 0, 12)) }}
        </div>
    </div>
</body>
</html>
