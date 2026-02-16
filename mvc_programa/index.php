<?php
/**
 * Página de Inicio - Selección de Rol
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Académico SENA</title>
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .login-header h1 {
            margin: 0 0 10px 0;
            font-size: 32px;
            font-weight: 700;
        }
        .login-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .login-body {
            padding: 40px;
        }
        .role-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }
        .role-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .role-card:hover {
            border-color: #667eea;
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.2);
        }
        .role-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .role-card:hover .role-icon {
            transform: scale(1.1);
        }
        .role-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: #1a202c;
        }
        .role-description {
            color: #718096;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }
        .role-features {
            list-style: none;
            padding: 0;
            margin: 20px 0 0 0;
            text-align: left;
        }
        .role-features li {
            padding: 8px 0;
            color: #4a5568;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .role-features li:before {
            content: "✓";
            color: #667eea;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Sistema Académico SENA</h1>
                <p>Seleccione su rol para continuar</p>
            </div>
            <div class="login-body">
                <div class="role-selection">
                    <!-- Coordinador -->
                    <a href="/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php" class="role-card">
                        <div class="role-icon">
                            <i data-lucide="shield-check" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h2 class="role-title">Coordinador</h2>
                        <p class="role-description">Acceso completo al sistema de gestión académica</p>
                        <ul class="role-features">
                            <li>Gestionar centros y coordinaciones</li>
                            <li>Administrar programas y competencias</li>
                            <li>Asignar instructores y fichas</li>
                            <li>Control total del sistema</li>
                        </ul>
                    </a>

                    <!-- Instructor -->
                    <a href="/proyectoo_22_/mvc_programa/views/instructor/dashboard.php" class="role-card">
                        <div class="role-icon">
                            <i data-lucide="user" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h2 class="role-title">Instructor</h2>
                        <p class="role-description">Consulta de información académica</p>
                        <ul class="role-features">
                            <li>Ver mis asignaciones</li>
                            <li>Consultar horarios</li>
                            <li>Ver fichas asignadas</li>
                            <li>Acceso de solo lectura</li>
                        </ul>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
