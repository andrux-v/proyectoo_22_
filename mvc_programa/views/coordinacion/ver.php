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
// --- Datos de prueba (eliminar cuando el controlador los proporcione) ---
$coordinacion = $coordinacion ?? [
    'coord_id' => 1,
    'coord_nombre' => 'Coordinación Académica',
    'CENTRO_FORMACION_cent_id' => 1,
    'cent_nombre' => 'Centro de Gestión de Mercados, Logística y TIC'
];
// --- Fin datos de prueba ---

$title = 'Detalle de Coordinación';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Coordinaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Detalle'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Detalle de Coordinación</h1>
        </div>

        <!-- Detail Card -->
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID de la Coordinación</div>
                    <div class="detail-value"><?php echo htmlspecialchars($coordinacion['coord_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nombre de la Coordinación</div>
                    <div class="detail-value"><?php echo htmlspecialchars($coordinacion['coord_nombre']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Centro de Formación</div>
                    <div class="detail-value"><?php echo htmlspecialchars($coordinacion['cent_nombre'] ?? 'N/A'); ?></div>
                </div>
            </div>
            <div class="detail-card-footer">
                <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                    <i data-lucide="arrow-left"></i>
                    Volver al Listado
                </a>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
