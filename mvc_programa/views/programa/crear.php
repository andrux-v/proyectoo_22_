<?php
/**
 * Vista: Registrar Programa (crear.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: /proyectoo_22_/mvc_programa/views/programa/index.php?rol=instructor');
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/ProgramaController.php';

$controller = new ProgramaController();

// Variables para el formulario
$errores = [];
$old = [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $resultado = $controller->create($_POST);
    
    if ($resultado['success']) {
        // Redirigir al index con mensaje de éxito
        $redirectUrl = '/proyectoo_22_/mvc_programa/views/programa/index.php';
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

// Obtener títulos de programa para el select
$titulos = $controller->getTitulosPrograma();

$title = 'Registrar Programa';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Programas', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Registrar Programa</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearProg" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="prog_codigo" class="form-label">
                            Código del Programa <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="prog_codigo"
                            name="prog_codigo"
                            class="form-input <?php echo isset($errores['prog_codigo']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: 228106"
                            value="<?php echo htmlspecialchars($old['prog_codigo'] ?? ''); ?>"
                            required
                            maxlength="20"
                        >
                        <div class="form-error <?php echo isset($errores['prog_codigo']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['prog_codigo'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prog_denominacion" class="form-label">
                            Denominación <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="prog_denominacion"
                            name="prog_denominacion"
                            class="form-input <?php echo isset($errores['prog_denominacion']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Análisis y Desarrollo de Software"
                            value="<?php echo htmlspecialchars($old['prog_denominacion'] ?? ''); ?>"
                            required
                            maxlength="200"
                        >
                        <div class="form-error <?php echo isset($errores['prog_denominacion']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['prog_denominacion'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prog_tipo" class="form-label">
                            Tipo de Formación <span class="required">*</span>
                        </label>
                        <select
                            id="prog_tipo"
                            name="prog_tipo"
                            class="form-input <?php echo isset($errores['prog_tipo']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <option value="Titulada" <?php echo(isset($old['prog_tipo']) && $old['prog_tipo'] == 'Titulada') ? 'selected' : ''; ?>>Titulada</option>
                            <option value="Complementaria" <?php echo(isset($old['prog_tipo']) && $old['prog_tipo'] == 'Complementaria') ? 'selected' : ''; ?>>Complementaria</option>
                        </select>
                        <div class="form-error <?php echo isset($errores['prog_tipo']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['prog_tipo'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="TIT_PROGRAMA_titpro_id" class="form-label">
                            Nivel de Formación <span class="required">*</span>
                        </label>
                        <select
                            id="TIT_PROGRAMA_titpro_id"
                            name="TIT_PROGRAMA_titpro_id"
                            class="form-input <?php echo isset($errores['TIT_PROGRAMA_titpro_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($titulos as $titulo): ?>
                                <option
                                    value="<?php echo $titulo['titpro_id']; ?>"
                                    <?php echo(isset($old['TIT_PROGRAMA_titpro_id']) && $old['TIT_PROGRAMA_titpro_id'] == $titulo['titpro_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($titulo['titpro_nombre']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['TIT_PROGRAMA_titpro_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['TIT_PROGRAMA_titpro_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Programa
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
