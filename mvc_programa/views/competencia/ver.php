<?php
/**
 * Vista: Detalle de Competencia (ver.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/CompetenciaController.php';

$controller = new CompetenciaController();

// Obtener el ID de la competencia
$comp_id = $_GET['id'] ?? null;

if (!$comp_id) {
    header('Location: /proyectoo_22_/mvc_programa/views/competencia/index.php?error=' . urlencode('ID de competencia no especificado'));
    exit;
}

// Obtener la competencia
$competencia = $controller->show($comp_id);

if (!$competencia) {
    header('Location: /proyectoo_22_/mvc_programa/views/competencia/index.php?error=' . urlencode('Competencia no encontrada'));
    exit;
}

$title = 'Detalle de Competencia';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Competencias', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Detalle de Competencia</h1>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID</div>
                    <div class="detail-value"><?php echo htmlspecialchars($competencia['comp_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nombre Corto</div>
                    <div class="detail-value"><?php echo htmlspecialchars($competencia['comp_nombre_corto']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Unidad de Competencia</div>
                    <div class="detail-value"><?php echo htmlspecialchars($competencia['comp_nombre_unidad_competencia']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Horas</div>
                    <div class="detail-value"><?php echo htmlspecialchars($competencia['comp_horas']); ?></div>
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
