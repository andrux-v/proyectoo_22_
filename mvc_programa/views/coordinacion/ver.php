<?php
/**
 * Vista: Detalle de Coordinación (ver.php)
 *
 * Variables esperadas del controlador:
 *   $coordinacion — Array con datos de la coordinación ['coord_id' => 1, 'coord_nombre' => '...', 'CENTRO_FORMACION_cent_id' => 1, 'cent_nombre' => '...']
 *   $rol          — 'coordinador' | 'instructor'
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/CoordinacionController.php';

$controller = new CoordinacionController();

// Obtener ID de la coordinación
$coord_id = $_GET['id'] ?? null;

if (!$coord_id) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('ID de coordinación no especificado'));
    exit;
}

// Obtener datos de la coordinación
$coordinacion = $controller->show($coord_id);

if (!$coordinacion) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('Coordinación no encontrada'));
    exit;
}

$title = 'Detalle de Coordinación';

// Determinar URL del dashboard según el rol
$dashboard_url = '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php';
if ($rol === 'instructor') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php';
} elseif ($rol === 'centro') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php';
}

$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $dashboard_url],
    ['label' => 'Coordinaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Detalle'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Detalle de Coordinación</h1>
        </div>

        <!-- Detail Card -->
        <div class="detail-card">
            <div class="detail-card-header" style="text-align: center;">
                <h2 class="detail-card-title">Información de la Coordinación</h2>
            </div>
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">Descripción</div>
                    <div class="detail-value"><?php echo htmlspecialchars($coordinacion['coord_descripcion']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Centro de Formación</div>
                    <div class="detail-value"><?php echo htmlspecialchars($coordinacion['cent_nombre'] ?? 'N/A'); ?></div>
                </div>
            </div>
        </div>

        <!-- Coordinador Info Card -->
        <div class="detail-card" style="margin-top: 20px;">
            <div class="detail-card-header" style="text-align: center;">
                <h2 class="detail-card-title">
                    <i data-lucide="user-cog" style="width: 20px; height: 20px; vertical-align: middle;"></i>
                    Información del Coordinador
                </h2>
            </div>
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">Nombre del Coordinador</div>
                    <div class="detail-value"><?php echo htmlspecialchars($coordinacion['coord_nombre_coordinador'] ?? 'No asignado'); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Correo Electrónico</div>
                    <div class="detail-value">
                        <?php if (!empty($coordinacion['coord_correo'])): ?>
                            <a href="mailto:<?php echo htmlspecialchars($coordinacion['coord_correo']); ?>" style="color: #39A900;">
                                <?php echo htmlspecialchars($coordinacion['coord_correo']); ?>
                            </a>
                        <?php else: ?>
                            No asignado
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="detail-card-footer">
                <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
                <a href="<?php echo addRolParam('editar.php?id=' . $coordinacion['coord_id'], $rol); ?>" class="btn btn-primary">
                    <i data-lucide="pencil-line"></i>
                    Editar Coordinación
                </a>
                <?php endif; ?>
                <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                    <i data-lucide="arrow-left"></i>
                    Volver al Listado
                </a>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
