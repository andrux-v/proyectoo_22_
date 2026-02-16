<?php
/**
 * Vista: Editar Ambiente (editar.php)
 */

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: /proyectoo_22_/mvc_programa/views/ambiente/index.php?rol=instructor');
    exit;
}

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

// Variables para el formulario
$errores = [];
$ambiente = null;

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $resultado = $controller->update($_POST);
    
    if ($resultado['success']) {
        // Redirigir al index con mensaje de éxito
        $redirectUrl = '/proyectoo_22_/mvc_programa/views/ambiente/index.php';
        if ($rol === 'instructor') {
            $redirectUrl .= '?rol=instructor';
        }
        $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'mensaje=' . urlencode($resultado['mensaje']);
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        $errores = $resultado['errores'];
        // Mantener los datos del formulario
        $ambiente = $_POST;
    }
}

// Si no hay datos del POST, obtener del controlador
if (!$ambiente) {
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
}

// Obtener sedes para el select
$sedes = $controller->getSedes();

$title = 'Editar Ambiente';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Ambientes', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Editar Ambiente</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formEditarAmb" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="amb_id" value="<?php echo htmlspecialchars($ambiente['amb_id']); ?>">

                    <div class="form-group">
                        <label for="amb_nombre" class="form-label">
                            Nombre del Ambiente <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="amb_nombre"
                            name="amb_nombre"
                            class="form-input <?php echo isset($errores['amb_nombre']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($ambiente['amb_nombre']); ?>"
                            required
                            maxlength="45"
                        >
                        <div class="form-error <?php echo isset($errores['amb_nombre']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['amb_nombre'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="SEDE_sede_id" class="form-label">
                            Sede <span class="required">*</span>
                        </label>
                        <select
                            id="SEDE_sede_id"
                            name="SEDE_sede_id"
                            class="form-input <?php echo isset($errores['SEDE_sede_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione una Sede</option>
                            <?php foreach ($sedes as $sede): ?>
                                <option
                                    value="<?php echo $sede['sede_id']; ?>"
                                    <?php echo($ambiente['SEDE_sede_id'] == $sede['sede_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($sede['sede_nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['SEDE_sede_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['SEDE_sede_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Actualizar Ambiente
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
