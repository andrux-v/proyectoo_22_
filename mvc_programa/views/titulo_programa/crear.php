<?php
/**
 * Vista: Registrar Título de Programa (crear.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: /proyectoo_22_/mvc_programa/views/titulo_programa/index.php?rol=instructor');
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/TituloProgramaController.php';

$controller = new TituloProgramaController();

// Variables para el formulario
$errores = [];
$old = [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $resultado = $controller->create($_POST);
    
    if ($resultado['success']) {
        // Redirigir al index con mensaje de éxito
        $redirectUrl = '/proyectoo_22_/mvc_programa/views/titulo_programa/index.php';
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

$title = 'Registrar Título';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Títulos', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Registrar'],
];

// Incluir el header según el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Registrar Título</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearTitulo" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="titpro_nombre" class="form-label">
                            Nombre del Título <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="titpro_nombre"
                            name="titpro_nombre"
                            class="form-input <?php echo isset($errores['titpro_nombre']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Tecnólogo"
                            value="<?php echo htmlspecialchars($old['titpro_nombre'] ?? ''); ?>"
                            required
                            maxlength="100"
                            autocomplete="off"
                        >
                        <div class="form-error <?php echo isset($errores['titpro_nombre']) ? 'visible' : ''; ?>" id="errorNombre">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['titpro_nombre'] ?? 'Este campo es obligatorio.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Título
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<script>
    document.getElementById('formCrearTitulo').addEventListener('submit', function(e) {
        var input = document.getElementById('titpro_nombre');
        var errorDiv = document.getElementById('errorNombre');

        if (!input.value.trim()) {
            e.preventDefault();
            input.classList.add('input-error');
            errorDiv.classList.add('visible');
            input.focus();
        } else {
            input.classList.remove('input-error');
            errorDiv.classList.remove('visible');
        }
    });

    document.getElementById('titpro_nombre').addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('input-error');
            document.getElementById('errorNombre').classList.remove('visible');
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
