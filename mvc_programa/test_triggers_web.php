<?php
/**
 * Página de Prueba de Triggers
 * Permite probar las validaciones y auditoría desde el navegador
 */

session_start();
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/controller/AsignacionController.php';

$controller = new AsignacionController();
$db = Conexion::getConnect();

// Establecer usuario de prueba
$_SESSION['user_correo'] = 'test_triggers@sena.edu.co';

$resultado = null;
$error = null;

// Procesar pruebas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        case 'prueba_valida':
            // Prueba 1: Asignación válida (10 horas/semana)
            $data = [
                'instructor_inst_id' => 1,
                'competencia_comp_id' => 2, // 40 horas
                'asig_fecha_ini' => '2026-06-01',
                'asig_fecha_fin' => '2026-06-29', // 28 días = 4 semanas hábiles
                'ficha_fich_id' => 3115419,
                'ambiente_amb_id' => 'A101'
            ];
            $resultado = $controller->create($data);
            break;
            
        case 'prueba_invalida_20h':
            // Prueba 2: Supera 20 horas semanales
            $data = [
                'instructor_inst_id' => 1,
                'competencia_comp_id' => 2, // 40 horas
                'asig_fecha_ini' => '2026-07-01',
                'asig_fecha_fin' => '2026-07-08', // 7 días = 1 semana
                'ficha_fich_id' => 3115419,
                'ambiente_amb_id' => 'A101'
            ];
            $resultado = $controller->create($data);
            break;
            
        case 'prueba_invalida_40h':
            // Prueba 3: Supera 40 horas totales del instructor
            // Primero crear una asignación de 25 horas/semana
            $data1 = [
                'instructor_inst_id' => 2,
                'competencia_comp_id' => 4, // 150 horas
                'asig_fecha_ini' => '2026-08-01',
                'asig_fecha_fin' => '2026-08-29', // 4 semanas = 37.5 h/semana
                'ficha_fich_id' => 3142583,
                'ambiente_amb_id' => 'B102'
            ];
            $resultado = $controller->create($data1);
            break;
            
        case 'limpiar_pruebas':
            // Eliminar asignaciones de prueba
            $query = "DELETE FROM asignacion WHERE asig_fecha_ini >= '2026-06-01'";
            $db->exec($query);
            $resultado = ['success' => true, 'mensaje' => 'Asignaciones de prueba eliminadas'];
            break;
    }
}

// Obtener estadísticas
$query = "SELECT COUNT(*) as total FROM asignacion WHERE asig_fecha_ini >= '2026-06-01'";
$stmt = $db->query($query);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener auditoría reciente
$query = "SELECT * FROM auditoria_asignaciones ORDER BY fecha_registro DESC LIMIT 10";
$stmt = $db->query($query);
$auditoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener instructores
$query = "SELECT inst_id, CONCAT(inst_nombres, ' ', inst_apellidos) as nombre FROM instructor LIMIT 5";
$stmt = $db->query($query);
$instructores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener competencias
$query = "SELECT comp_id, comp_nombre_corto, comp_horas FROM competencia LIMIT 5";
$stmt = $db->query($query);
$competencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Triggers - Sistema SENA</title>
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        body {
            background: #f3f4f6;
            padding: 20px;
        }
        .test-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .test-header {
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .test-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .test-card h3 {
            margin: 0 0 16px 0;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .test-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .test-btn-success {
            background: #10b981;
            color: white;
        }
        .test-btn-danger {
            background: #ef4444;
            color: white;
        }
        .test-btn-warning {
            background: #f59e0b;
            color: white;
        }
        .test-btn-secondary {
            background: #6b7280;
            color: white;
        }
        .test-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .result-box {
            margin-top: 16px;
            padding: 16px;
            border-radius: 8px;
            font-size: 14px;
        }
        .result-success {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        .result-error {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }
        .audit-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .audit-table th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        .audit-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        .info-box {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            color: #1e40af;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #39A900;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="test-header">
            <h1 style="margin: 0 0 8px 0; font-size: 28px;">🧪 Prueba de Triggers y Validaciones</h1>
            <p style="margin: 0; opacity: 0.9;">Sistema de Gestión Académica SENA</p>
        </div>

        <?php if ($resultado): ?>
            <div class="result-box <?php echo $resultado['success'] ? 'result-success' : 'result-error'; ?>">
                <strong><?php echo $resultado['success'] ? '✅ Éxito:' : '❌ Error:'; ?></strong>
                <?php 
                if ($resultado['success']) {
                    echo htmlspecialchars($resultado['mensaje']);
                } else {
                    if (isset($resultado['errores'])) {
                        foreach ($resultado['errores'] as $campo => $mensaje) {
                            echo "<br>• " . htmlspecialchars($mensaje);
                        }
                    } else {
                        echo htmlspecialchars($resultado['error'] ?? 'Error desconocido');
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <strong>ℹ️ Información:</strong> Estas pruebas crean asignaciones con fechas futuras (2026) para no interferir con datos reales.
            Los triggers validarán automáticamente las horas semanales (máx 20h por competencia, 40h totales).
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Asignaciones de Prueba</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($auditoria); ?></div>
                <div class="stat-label">Registros de Auditoría</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($instructores); ?></div>
                <div class="stat-label">Instructores Disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($competencias); ?></div>
                <div class="stat-label">Competencias Disponibles</div>
            </div>
        </div>

        <div class="test-grid">
            <!-- Prueba 1: Válida -->
            <div class="test-card">
                <h3>
                    <i data-lucide="check-circle" style="color: #10b981;"></i>
                    Prueba 1: Asignación Válida
                </h3>
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
                    40 horas en 4 semanas = <strong>10 h/semana</strong> ✅
                </p>
                <form method="POST">
                    <input type="hidden" name="accion" value="prueba_valida">
                    <button type="submit" class="test-btn test-btn-success">
                        <i data-lucide="play"></i>
                        Ejecutar Prueba
                    </button>
                </form>
            </div>

            <!-- Prueba 2: Supera 20h -->
            <div class="test-card">
                <h3>
                    <i data-lucide="alert-triangle" style="color: #ef4444;"></i>
                    Prueba 2: Supera 20h/semana
                </h3>
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
                    40 horas en 1 semana = <strong>40 h/semana</strong> ❌
                </p>
                <form method="POST">
                    <input type="hidden" name="accion" value="prueba_invalida_20h">
                    <button type="submit" class="test-btn test-btn-danger">
                        <i data-lucide="play"></i>
                        Ejecutar Prueba
                    </button>
                </form>
            </div>

            <!-- Prueba 3: Supera 40h totales -->
            <div class="test-card">
                <h3>
                    <i data-lucide="alert-octagon" style="color: #f59e0b;"></i>
                    Prueba 3: Supera 40h totales
                </h3>
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
                    150 horas en 4 semanas = <strong>37.5 h/semana</strong> ⚠️
                </p>
                <form method="POST">
                    <input type="hidden" name="accion" value="prueba_invalida_40h">
                    <button type="submit" class="test-btn test-btn-warning">
                        <i data-lucide="play"></i>
                        Ejecutar Prueba
                    </button>
                </form>
            </div>

            <!-- Limpiar -->
            <div class="test-card">
                <h3>
                    <i data-lucide="trash-2" style="color: #6b7280;"></i>
                    Limpiar Datos de Prueba
                </h3>
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
                    Elimina todas las asignaciones de prueba
                </p>
                <form method="POST" onsubmit="return confirm('¿Eliminar todas las asignaciones de prueba?')">
                    <input type="hidden" name="accion" value="limpiar_pruebas">
                    <button type="submit" class="test-btn test-btn-secondary">
                        <i data-lucide="trash-2"></i>
                        Limpiar Todo
                    </button>
                </form>
            </div>
        </div>

        <!-- Auditoría -->
        <div class="test-card">
            <h3>
                <i data-lucide="file-text"></i>
                Auditoría Reciente (Últimos 10 registros)
            </h3>
            <?php if (!empty($auditoria)): ?>
                <div style="overflow-x: auto;">
                    <table class="audit-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Asignación</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($auditoria as $reg): ?>
                                <tr>
                                    <td><?php echo $reg['id_auditoria']; ?></td>
                                    <td>
                                        <span class="badge badge-info">#<?php echo $reg['id_asignacion']; ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($reg['usuario_que_creo']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($reg['fecha_registro'])); ?></td>
                                    <td style="max-width: 400px; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo htmlspecialchars(substr($reg['detalles'], 0, 100)); ?>...
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #6b7280; padding: 20px;">
                    No hay registros de auditoría aún
                </p>
            <?php endif; ?>
        </div>

        <!-- Datos disponibles -->
        <div class="test-grid">
            <div class="test-card">
                <h3>
                    <i data-lucide="users"></i>
                    Instructores Disponibles
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach ($instructores as $inst): ?>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                            <span class="badge badge-info">ID: <?php echo $inst['inst_id']; ?></span>
                            <?php echo htmlspecialchars($inst['nombre']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="test-card">
                <h3>
                    <i data-lucide="award"></i>
                    Competencias Disponibles
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach ($competencias as $comp): ?>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                            <span class="badge badge-success">ID: <?php echo $comp['comp_id']; ?></span>
                            <?php echo htmlspecialchars($comp['comp_nombre_corto']); ?>
                            <span style="color: #6b7280; font-size: 12px;">(<?php echo $comp['comp_horas']; ?>h)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/proyectoo_22_/mvc_programa/views/asignacion/index.php?rol=coordinador" class="btn btn-primary">
                <i data-lucide="calendar"></i>
                Ver Calendario de Asignaciones
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
