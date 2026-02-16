<?php
/**
 * Vista: Registrar Ambiente (crear.php)
 *
 * Variables esperadas:
 *   $sedes  — Array de sedes para el select
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Variables para el formulario
$errores = [];
$old = [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $resultado = $controller->create($_POST);
    
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
        $old = $_POST;
    }
}

// Obtener sedes para el select
$sedes = $controller->getSedes();

$title = 'Registrar Ambiente';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Ambientes', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Registrar'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Registrar Ambiente</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearAmb" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="amb_id" class="form-label">
                            ID del Ambiente <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="amb_id"
                            name="amb_id"
                            class="form-input <?php echo isset($errores['amb_id']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: A101"
                            value="<?php echo htmlspecialchars($old['amb_id'] ?? ''); ?>"
                            required
                            maxlength="5"
                        >
                        <div class="form-error <?php echo isset($errores['amb_id']) ? 'visible' : ''; ?>" id="errorId">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['amb_id'] ?? 'El ID es requerido (máximo 5 caracteres).'); ?></span>
                        </div>
                        <div class="form-hint">Código único del ambiente (máximo 5 caracteres).</div>
                    </div>

                    <div class="form-group">
                        <label for="amb_nombre" class="form-label">
                            Nombre del Ambiente <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="amb_nombre"
                            name="amb_nombre"
                            class="form-input <?php echo isset($errores['amb_nombre']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Laboratorio de Software"
                            value="<?php echo htmlspecialchars($old['amb_nombre'] ?? ''); ?>"
                            required
                            maxlength="45"
                        >
                        <div class="form-error <?php echo isset($errores['amb_nombre']) ? 'visible' : ''; ?>" id="errorNombre">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['amb_nombre'] ?? 'El nombre es requerido.'); ?></span>
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
                                    <?php echo(isset($old['SEDE_sede_id']) && $old['SEDE_sede_id'] == $sede['sede_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($sede['sede_nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['SEDE_sede_id']) ? 'visible' : ''; ?>" id="errorSede">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['SEDE_sede_id'] ?? 'Debe seleccionar una sede.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Ambiente
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
