<?php
/**
 * Vista: Detalle de Horario (ver.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$detalle = $detalle ?? ['detasig_id' => 1, 'ASIGNACION_asig_id' => 1, 'detasig_hora_ini' => '08:00', 'detasig_hora_fin' => '12:00'];
// --- Fin datos de prueba ---

$title = 'Detalle de Horario';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Detalles', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Detalle'],
];

// Incluir el header seg˙n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Detalle de Horario</h1>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID Detalle</div>
                    <div class="detail-value"><?php echo htmlspecialchars($detalle['detasig_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">ID Asignaci√≥n</div>
                    <div class="detail-value"><?php echo htmlspecialchars($detalle['ASIGNACION_asig_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Hora Inicio</div>
                    <div class="detail-value"><?php echo htmlspecialchars($detalle['detasig_hora_ini']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Hora Fin</div>
                    <div class="detail-value"><?php echo htmlspecialchars($detalle['detasig_hora_fin']); ?></div>
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
