<?php
/**
 * Vista: Detalle de Título (ver.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$titulo = $titulo ?? ['tibro_id' => 1, 'tibro_nombre' => 'Tecnólogo'];
// --- Fin datos de prueba ---

$title = 'Detalle de Título';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Títulos', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Detalle'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Detalle de Título</h1>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID del Título</div>
                    <div class="detail-value"><?php echo htmlspecialchars($titulo['tibro_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nombre</div>
                    <div class="detail-value"><?php echo htmlspecialchars($titulo['tibro_nombre']); ?></div>
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
