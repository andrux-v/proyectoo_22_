<?php
/**
 * Vista: Detalle de Sede (ver.php)
 *
 * Variables esperadas del controlador:
 *   $sede — Array con datos de la sede ['sede_id' => 1, 'sede_nombre' => '...']
 *   $rol  — 'coordinador' | 'instructor'
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba (eliminar cuando el controlador los proporcione) ---
$rol = $rol ?? 'coordinador';
$sede = $sede ?? ['sede_id' => 1, 'sede_nombre' => 'Centro de Gestión Industrial'];
// --- Fin datos de prueba ---

$title = 'Detalle de Sede';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Sedes', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Detalle de Sede</h1>
        </div>

        <!-- Detail Card -->
        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID de la Sede</div>
                    <div class="detail-value"><?php echo htmlspecialchars($sede['sede_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nombre de la Sede</div>
                    <div class="detail-value"><?php echo htmlspecialchars($sede['sede_nombre']); ?></div>
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
