<?php
/**
 * Vista: Detalle de Programa (ver.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$programa = $programa ?? ['prog_codigo' => '228106', 'prog_denominacion' => 'An谩lisis y Desarrollo de Software', 'prog_tipo' => 'Titulada', 'tibro_nombre' => 'Tecn贸logo'];
// --- Fin datos de prueba ---

$title = 'Detalle de Programa';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Programas', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Detalle de Programa</h1>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">C贸digo</div>
                    <div class="detail-value"><?php echo htmlspecialchars($programa['prog_codigo']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Denominaci贸n</div>
                    <div class="detail-value"><?php echo htmlspecialchars($programa['prog_denominacion']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tipo de Formaci贸n</div>
                    <div class="detail-value"><?php echo htmlspecialchars($programa['prog_tipo']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nivel / T铆tulo</div>
                    <div class="detail-value"><?php echo htmlspecialchars($programa['tibro_nombre']); ?></div>
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
