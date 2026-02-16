<?php
/**
 * Vista: Detalle de Ambiente (ver.php)
 */

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/AmbienteController.php';

$controller = new AmbienteController();

// Verificar que se recibió el ID
if (!isset($_GET['id'])) {
    $redirectUrl = '/proyectoo_22_/mvc_programa/views/ambiente/index.php';
    if ($rol === 'instructor') {
        $redirectUrl .= '?rol=instructor';
    }
    header('Location: ' . $redirectUrl);
    exit;
}

$amb_id = $_GET['id'];

// Obtener el ambiente
$ambiente = $controller->show($amb_id);

if (!$ambiente) {
    $redirectUrl = '/proyectoo_22_/mvc_programa/views/ambiente/index.php';
    if ($rol === 'instructor') {
        $redirectUrl .= '?rol=instructor';
    }
    $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'error=' . urlencode('Ambiente no encontrado');
    header('Location: ' . $redirectUrl);
    exit;
}

$title = 'Detalle de Ambiente';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Ambientes', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Detalle de Ambiente</h1>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID</div>
                    <div class="detail-value"><?php echo htmlspecialchars($ambiente['amb_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nombre Ambiente</div>
                    <div class="detail-value"><?php echo htmlspecialchars($ambiente['amb_nombre']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Sede</div>
                    <div class="detail-value"><?php echo htmlspecialchars($ambiente['sede_nombre']); ?></div>
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
