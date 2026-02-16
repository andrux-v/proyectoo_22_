<?php
/**
 * Vista: Detalle de Asignaci贸n (ver.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$asignacion = $asignacion ?? [
    'asig_id' => 1,
    'fich_id' => '228106-1',
    'inst_nombre' => 'Juan P茅rez',
    'amb_nombre' => 'Laboratorio 1',
    'comp_nombre_corto' => 'Promover salud',
    'asig_fecha_ini' => '2023-01-20',
    'asig_fecha_fin' => '2023-06-20'
];
// --- Fin datos de prueba ---

$title = 'Detalle de Asignaci贸n';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Asignaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Detalle'],
];

// Incluir el header seg鷑 el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Detalle de Asignaci贸n</h1>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID Asignaci贸n</div>
                    <div class="detail-value"><?php echo htmlspecialchars($asignacion['asig_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ficha</div>
                    <div class="detail-value"><?php echo htmlspecialchars($asignacion['fich_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Instructor</div>
                    <div class="detail-value"><?php echo htmlspecialchars($asignacion['inst_nombre']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ambiente</div>
                    <div class="detail-value"><?php echo htmlspecialchars($asignacion['amb_nombre']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Competencia</div>
                    <div class="detail-value"><?php echo htmlspecialchars($asignacion['comp_nombre_corto']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Fecha Inicio</div>
                    <div class="detail-value"><?php echo htmlspecialchars($asignacion['asig_fecha_ini']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Fecha Fin</div>
                    <div class="detail-value"><?php echo htmlspecialchars($asignacion['asig_fecha_fin']); ?></div>
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
