<?php
/**
 * Vista: Detalle de Programa
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/ProgramaController.php';

$controller = new ProgramaController();

// Obtener código del programa
$prog_codigo = $_GET['codigo'] ?? null;

if (!$prog_codigo) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('Código de programa no proporcionado'));
    exit;
}

// Obtener el programa
$programa = $controller->show($prog_codigo);

if (!$programa) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('Programa no encontrado'));
    exit;
}

$rol = $rol ?? 'coordinador';

$title = 'Detalle de Programa';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : ($rol === 'centro' ? '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php')],
    ['label' => 'Programas', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Detalle'],
];

// Incluir el header según el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} elseif ($rol === 'centro') {
    include __DIR__ . '/../layout/header_centro.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Detalle de Programa</h1>
            <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
                <a href="<?php echo addRolParam('editar.php?codigo=' . $programa['prog_codigo'], $rol); ?>" class="btn btn-primary">
                    <i data-lucide="pencil-line"></i>
                    Editar Programa
                </a>
            <?php endif; ?>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">Código</div>
                    <div class="detail-value"><?php echo htmlspecialchars($programa['prog_codigo']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Denominación</div>
                    <div class="detail-value"><?php echo htmlspecialchars($programa['prog_denominacion']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nivel de Formación</div>
                    <div class="detail-value">
                        <span style="background: #39A900; color: white; padding: 4px 12px; border-radius: 12px; font-size: 14px; font-weight: 500;">
                            <?php echo htmlspecialchars($programa['titpro_nombre'] ?? 'N/A'); ?>
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tipo de Formación</div>
                    <div class="detail-value"><?php echo htmlspecialchars($programa['prog_tipo']); ?></div>
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
