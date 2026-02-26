<?php
/**
 * Vista: Registrar Ficha (crear.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: /proyectoo_22_/mvc_programa/views/ficha/index.php?rol=instructor');
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/FichaController.php';

$controller = new FichaController();

// Variables para el formulario
$errores = [];
$old = [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $resultado = $controller->create($_POST);
    
    if ($resultado['success']) {
        // Redirigir al index con mensaje de éxito
        $redirectUrl = '/proyectoo_22_/mvc_programa/views/ficha/index.php';
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

// Obtener datos para los selects
$programas = $controller->getProgramas();
$instructores = $controller->getInstructores();
$coordinaciones = $controller->getCoordinaciones();

$title = 'Registrar Ficha';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Fichas', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Registrar Ficha</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearFicha" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="fich_id" class="form-label">
                            Número de Ficha <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="fich_id"
                            name="fich_id"
                            class="form-input <?php echo isset($errores['fich_id']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: 228106-1"
                            value="<?php echo htmlspecialchars($old['fich_id'] ?? ''); ?>"
                            required
                            maxlength="20"
                        >
                        <div class="form-error <?php echo isset($errores['fich_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['fich_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fich_jornada" class="form-label">
                            Jornada <span class="required">*</span>
                        </label>
                        <select
                            id="fich_jornada"
                            name="fich_jornada"
                            class="form-input <?php echo isset($errores['fich_jornada']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <option value="Diurna" <?php echo(isset($old['fich_jornada']) && $old['fich_jornada'] == 'Diurna') ? 'selected' : ''; ?>>Diurna</option>
                            <option value="Nocturna" <?php echo(isset($old['fich_jornada']) && $old['fich_jornada'] == 'Nocturna') ? 'selected' : ''; ?>>Nocturna</option>
                            <option value="Mixta" <?php echo(isset($old['fich_jornada']) && $old['fich_jornada'] == 'Mixta') ? 'selected' : ''; ?>>Mixta</option>
                        </select>
                        <div class="form-error <?php echo isset($errores['fich_jornada']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['fich_jornada'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="PROGRAMA_prog_id" class="form-label">
                            Programa de Formación <span class="required">*</span>
                        </label>
                        <select
                            id="PROGRAMA_prog_id"
                            name="PROGRAMA_prog_id"
                            class="form-input <?php echo isset($errores['PROGRAMA_prog_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($programas as $p): ?>
                                <option
                                    value="<?php echo $p['prog_codigo']; ?>"
                                    <?php echo(isset($old['PROGRAMA_prog_id']) && $old['PROGRAMA_prog_id'] == $p['prog_codigo']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($p['prog_denominacion'] . ' (' . $p['prog_codigo'] . ')'); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['PROGRAMA_prog_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['PROGRAMA_prog_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="INSTRUCTOR_inst_id_lider" class="form-label">
                            Instructor Líder <span class="required">*</span>
                        </label>
                        <select
                            id="INSTRUCTOR_inst_id_lider"
                            name="INSTRUCTOR_inst_id_lider"
                            class="form-input <?php echo isset($errores['INSTRUCTOR_inst_id_lider']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($instructores as $inst): ?>
                                <option
                                    value="<?php echo $inst['inst_id']; ?>"
                                    <?php echo(isset($old['INSTRUCTOR_inst_id_lider']) && $old['INSTRUCTOR_inst_id_lider'] == $inst['inst_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($inst['nombre_completo']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['INSTRUCTOR_inst_id_lider']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['INSTRUCTOR_inst_id_lider'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="COORDINACION_coord_id" class="form-label">
                            Coordinación <span class="required">*</span>
                        </label>
                        <select
                            id="COORDINACION_coord_id"
                            name="COORDINACION_coord_id"
                            class="form-input <?php echo isset($errores['COORDINACION_coord_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($coordinaciones as $coord): ?>
                                <option
                                    value="<?php echo $coord['coord_id']; ?>"
                                    <?php echo(isset($old['COORDINACION_coord_id']) && $old['COORDINACION_coord_id'] == $coord['coord_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($coord['coord_nombre']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['COORDINACION_coord_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['COORDINACION_coord_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Ficha
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
