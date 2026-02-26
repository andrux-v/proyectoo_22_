<?php
/**
 * Vista: Detalle de Instructor (ver.php)
 */

require_once __DIR__ . '/../../controller/InstructorController.php';

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

$controller = new InstructorController();

// Obtener ID del instructor
$inst_id = $_GET['id'] ?? null;

if (!$inst_id) {
    header('Location: index.php?error=' . urlencode('ID de instructor no especificado'));
    exit;
}

// Obtener datos del instructor
$instructor = $controller->show($inst_id);

if (!$instructor) {
    header('Location: index.php?error=' . urlencode('Instructor no encontrado'));
    exit;
}

$title = 'Detalle de Instructor';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Instructores', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Detalle de Instructor</h1>
        </div>

        <div class="detail-card">
            <div class="detail-card-body">
                <div class="detail-row">
                    <div class="detail-label">ID</div>
                    <div class="detail-value"><?php echo htmlspecialchars($instructor['inst_id']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nombres</div>
                    <div class="detail-value"><?php echo htmlspecialchars($instructor['inst_nombres']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Apellidos</div>
                    <div class="detail-value"><?php echo htmlspecialchars($instructor['inst_apellidos']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Correo</div>
                    <div class="detail-value"><?php echo htmlspecialchars($instructor['inst_correo']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Teléfono</div>
                    <div class="detail-value"><?php echo htmlspecialchars($instructor['inst_telefono']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Centro de Formación</div>
                    <div class="detail-value"><?php echo htmlspecialchars($instructor['cent_nombre'] ?? 'N/A'); ?></div>
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
