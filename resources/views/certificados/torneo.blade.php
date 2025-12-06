<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $torneo->name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #2a5298;
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
        }

        .certificado {
            width: 297mm;
            height: 210mm;
            padding: 0;
            margin: 0;
            position: relative;
            background-color: #2a5298;
        }

        /* Borde dorado principal */
        .border-outer {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 5px solid #FFD700;
        }

        .border-inner {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 2px solid #FFD700;
        }

        /* Contenedor principal */
        .contenido {
            position: absolute;
            top: 18mm;
            left: 20mm;
            right: 20mm;
            bottom: 18mm;
            text-align: center;
            color: white;
        }

        /* Header */
        .header {
            margin-bottom: 5mm;
        }

        .logo {
            font-size: 28pt;
            font-weight: bold;
            color: #FFD700;
            margin: 0;
            padding: 0;
        }

        .titulo {
            font-size: 42pt;
            font-weight: bold;
            color: #FFD700;
            text-transform: uppercase;
            letter-spacing: 8px;
            margin: 2mm 0;
            padding: 0;
        }

        .linea-divisora {
            width: 180px;
            height: 2px;
            background-color: #FFD700;
            margin: 3mm auto;
        }

        /* Texto principal */
        .subtitulo {
            font-size: 13pt;
            color: white;
            margin: 3mm 0;
            text-transform: uppercase;
        }

        .nombre-box {
            background-color: rgba(255, 215, 0, 0.15);
            border: 2px solid #FFD700;
            padding: 8px 25px;
            display: inline-block;
            margin: 5mm 0;
        }

        .nombre-usuario {
            font-size: 26pt;
            font-weight: bold;
            color: white;
            margin: 0;
        }

        /* Medalla y reconocimiento */
        .medalla-seccion {
            margin: 5mm 0;
        }

        .medalla {
            font-size: 50pt;
            line-height: 1;
            margin: 0;
        }

        .lugar {
            font-size: 30pt;
            font-weight: bold;
            color: #FFD700;
            text-transform: uppercase;
            margin: 2mm 0;
        }

        .badge {
            background-color: rgba(255, 215, 0, 0.2);
            border: 1px solid #FFD700;
            color: #FFD700;
            padding: 3px 12px;
            display: inline-block;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 2mm;
        }

        /* Información del torneo */
        .info-texto {
            font-size: 12pt;
            color: white;
            margin: 3mm 0;
        }

        .equipo-nombre {
            font-size: 20pt;
            font-weight: bold;
            color: #FFD700;
            margin: 3mm 0;
        }

        .torneo-nombre {
            font-size: 18pt;
            font-weight: bold;
            color: white;
            font-style: italic;
            margin: 3mm 0;
        }

        /* Estadísticas - usando tabla simple */
        .stats-table {
            width: 450px;
            margin: 5mm auto;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .stats-table td {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px;
            text-align: center;
            width: 33.33%;
        }

        .stat-label {
            font-size: 8pt;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            display: block;
            margin-bottom: 3px;
        }

        .stat-value {
            font-size: 18pt;
            font-weight: bold;
            color: #FFD700;
            display: block;
        }

        /* Footer */
        .footer {
            margin-top: 5mm;
        }

        .fecha-texto {
            font-size: 9pt;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1mm;
        }

        .fecha-valor {
            font-size: 11pt;
            font-weight: bold;
            color: white;
            margin-bottom: 4mm;
        }

        /* Firmas - tabla simple */
        .firmas-table {
            width: 500px;
            margin: 0 auto;
            border-collapse: collapse;
        }

        .firmas-table td {
            width: 50%;
            text-align: center;
            padding: 0 15px;
        }

        .firma-linea {
            width: 140px;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.6);
            margin: 0 auto 3px;
        }

        .firma-nombre {
            font-size: 11pt;
            font-weight: bold;
            color: white;
            margin: 2px 0;
        }

        .firma-cargo {
            font-size: 8pt;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
        }

        /* Sello */
        .sello {
            position: absolute;
            bottom: 20mm;
            right: 25mm;
            width: 70px;
            height: 70px;
            border: 3px solid #FFD700;
            border-radius: 35px;
            background-color: rgba(255, 215, 0, 0.1);
            text-align: center;
            padding-top: 12px;
        }

        .sello-check {
            font-size: 22pt;
            color: #FFD700;
            line-height: 1;
            margin: 0;
        }

        .sello-texto {
            font-size: 7pt;
            font-weight: bold;
            color: #FFD700;
            text-transform: uppercase;
            line-height: 1.2;
            margin-top: 2px;
        }

        /* Código */
        .codigo {
            position: absolute;
            bottom: 10mm;
            left: 50%;
            margin-left: -80px;
            width: 160px;
            font-size: 7pt;
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
        }

        /* Esquinas decorativas */
        .esquina {
            position: absolute;
            width: 35px;
            height: 35px;
        }

        .esquina-tl {
            top: 6mm;
            left: 6mm;
            border-top: 3px solid #FFD700;
            border-left: 3px solid #FFD700;
        }

        .esquina-tr {
            top: 6mm;
            right: 6mm;
            border-top: 3px solid #FFD700;
            border-right: 3px solid #FFD700;
        }

        .esquina-bl {
            bottom: 6mm;
            left: 6mm;
            border-bottom: 3px solid #FFD700;
            border-left: 3px solid #FFD700;
        }

        .esquina-br {
            bottom: 6mm;
            right: 6mm;
            border-bottom: 3px solid #FFD700;
            border-right: 3px solid #FFD700;
        }
    </style>
</head>
<body>
    <div class="certificado">
        <!-- Esquinas -->
        <div class="esquina esquina-tl"></div>
        <div class="esquina esquina-tr"></div>
        <div class="esquina esquina-bl"></div>
        <div class="esquina esquina-br"></div>

        <!-- Bordes -->
        <div class="border-outer"></div>
        <div class="border-inner"></div>

        <!-- Contenido -->
        <div class="contenido">
            <!-- Header -->
            <div class="header">
                <div class="logo">UniversoDev</div>
                <div class="titulo">CERTIFICADO</div>
                <div class="linea-divisora"></div>
            </div>

            <!-- Subtítulo -->
            <div class="subtitulo">Se otorga el presente reconocimiento a:</div>

            <!-- Nombre -->
            <div class="nombre-box">
                <div class="nombre-usuario">{{ $usuario->name }}</div>
            </div>

            <!-- Medalla y lugar -->
            <div class="medalla-seccion">
                @if($participacion->posicion == 1)
                    <div class="medalla">🥇</div>
                    <div class="lugar">PRIMER LUGAR</div>
                    <div class="badge">★ Excelencia en Programación ★</div>
                @elseif($participacion->posicion == 2)
                    <div class="medalla">🥈</div>
                    <div class="lugar">SEGUNDO LUGAR</div>
                    <div class="badge">★ Destacada Participación ★</div>
                @elseif($participacion->posicion == 3)
                    <div class="medalla">🥉</div>
                    <div class="lugar">TERCER LUGAR</div>
                    <div class="badge">★ Mérito en Desarrollo ★</div>
                @endif
            </div>

            <!-- Información -->
            <div class="info-texto">Por su destacada participación como miembro del equipo</div>
            <div class="equipo-nombre">{{ $equipo->name }}</div>
            <div class="info-texto">en el torneo de programación</div>
            <div class="torneo-nombre">"{{ $torneo->name }}"</div>

            <!-- Estadísticas -->
            <table class="stats-table">
                <tr>
                    <td>
                        <span class="stat-label">Posición</span>
                        <span class="stat-value">#{{ $participacion->posicion }}</span>
                    </td>
                    <td>
                        <span class="stat-label">Puntaje Final</span>
                        <span class="stat-value">{{ number_format($participacion->puntaje_total, 1) }}</span>
                    </td>
                    <td>
                        <span class="stat-label">Miembros</span>
                        <span class="stat-value">{{ $equipo->miembros_actuales }}</span>
                    </td>
                </tr>
            </table>

            <!-- Footer -->
            <div class="footer">
                <div class="fecha-texto">FECHA DE EMISIÓN</div>
                <div class="fecha-valor">{{ $fecha_finalizacion }}</div>

                <!-- Firmas -->
                <table class="firmas-table">
                    <tr>
                        <td>
                            <div class="firma-linea"></div>
                            <div class="firma-nombre">{{ $torneo->organizador->name ?? 'Organizador' }}</div>
                            <div class="firma-cargo">Organizador del Torneo</div>
                        </td>
                        <td>
                            <div class="firma-linea"></div>
                            <div class="firma-nombre">UniversoDev</div>
                            <div class="firma-cargo">Dirección General</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Sello -->
        <div class="sello">
            <div class="sello-check">✓</div>
            <div class="sello-texto">CERTIFICADO<br>OFICIAL<br>{{ date('Y') }}</div>
        </div>

        <!-- Código -->
        <div class="codigo">
            ID: CERT-{{ strtoupper(substr(md5($torneo->id . $equipo->id . $usuario->id), 0, 12)) }}
        </div>
    </div>
</body>
</html>
