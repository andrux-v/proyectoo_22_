<?php
/**
 * Vista: Registrar Asignación
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor
if ($rol === 'instructor') {
    header('Location: ' . addRolParam('index.php', $rol));
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/AsignacionController.php';

$controller = new AsignacionController();
$errores = [];
$old = [];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'instructor_inst_id' => $_POST['INSTRUCTOR_inst_id'] ?? '',
        'ficha_fich_id' => $_POST['FICHA_fich_id'] ?? '',
        'ambiente_amb_id' => $_POST['AMBIENTE_id_ambiente'] ?? '',
        'competencia_comp_id' => $_POST['COMPETENCIA_comp_id'] ?? '',
        'asig_fecha_ini' => $_POST['asig_fecha_ini'] ?? '',
        'asig_fecha_fin' => $_POST['asig_fecha_fin'] ?? '',
        'detalles' => []
    ];

    $resultado = $controller->create($data);

    if ($resultado['success']) {
        $redirectUrl = 'index.php?rol=' . $rol . '&mensaje=' . urlencode($resultado['mensaje']);
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        $errores = $resultado['errores'];
        $old = $data;
    }
}

// Obtener datos para los selects
$instructores = $controller->getInstructores();
$fichas = $controller->getFichas();
$ambientes = $controller->getAmbientes();
$competencias = $controller->getCompetencias();

$rol = $rol ?? 'coordinador';

$title = 'Nueva Asignación';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Asignaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Nueva'],
];

if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

<div class="page-header">
    <h1 class="page-title">Nueva Asignación</h1>
</div>

<div class="form-container">
    <div class="form-card">
        <form id="formCrearAsig" method="POST" action="" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="FICHA_fich_id" class="form-label">
                        Ficha <span class="required">*</span>
                    </label>
                    <select id="FICHA_fich_id" name="FICHA_fich_id" class="form-input <?php echo isset($errores['ficha_fich_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($fichas as $f): ?>
                            <option value="<?php echo $f['fich_id']; ?>" <?php echo(isset($old['ficha_fich_id']) && $old['ficha_fich_id'] == $f['fich_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($f['fich_id']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['ficha_fich_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['ficha_fich_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="INSTRUCTOR_inst_id" class="form-label">
                        Instructor <span class="required">*</span>
                    </label>
                    <select id="INSTRUCTOR_inst_id" name="INSTRUCTOR_inst_id" class="form-input <?php echo isset($errores['instructor_inst_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($instructores as $inst): ?>
                            <option value="<?php echo $inst['inst_id']; ?>" <?php echo(isset($old['instructor_inst_id']) && $old['instructor_inst_id'] == $inst['inst_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($inst['nombre_completo'] ?? ($inst['inst_nombres'] . ' ' . $inst['inst_apellidos'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['instructor_inst_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['instructor_inst_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="AMBIENTE_id_ambiente" class="form-label">
                        Ambiente <span class="required">*</span>
                    </label>
                    <select id="AMBIENTE_id_ambiente" name="AMBIENTE_id_ambiente" class="form-input <?php echo isset($errores['ambiente_amb_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($ambientes as $amb): ?>
                            <option value="<?php echo $amb['amb_id']; ?>" <?php echo(isset($old['ambiente_amb_id']) && $old['ambiente_amb_id'] == $amb['amb_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($amb['amb_nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['ambiente_amb_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['ambiente_amb_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="COMPETENCIA_comp_id" class="form-label">
                        Competencia <span class="required">*</span>
                    </label>
                    <select id="COMPETENCIA_comp_id" name="COMPETENCIA_comp_id" class="form-input <?php echo isset($errores['competencia_comp_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($competencias as $comp): ?>
                            <option value="<?php echo $comp['comp_id']; ?>" <?php echo(isset($old['competencia_comp_id']) && $old['competencia_comp_id'] == $comp['comp_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($comp['comp_nombre_corto']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['competencia_comp_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['competencia_comp_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="asig_fecha_ini" class="form-label">
                        Fecha Inicio <span class="required">*</span>
                    </label>
                    <input type="date" id="asig_fecha_ini" name="asig_fecha_ini" class="form-input <?php echo isset($errores['asig_fecha_ini']) ? 'input-error' : ''; ?>" value="<?php echo htmlspecialchars($old['asig_fecha_ini'] ?? ''); ?>" required>
                    <div class="form-error <?php echo isset($errores['asig_fecha_ini']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['asig_fecha_ini'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="asig_fecha_fin" class="form-label">
                        Fecha Fin <span class="required">*</span>
                    </label>
                    <input type="date" id="asig_fecha_fin" name="asig_fecha_fin" class="form-input <?php echo isset($errores['asig_fecha_fin']) ? 'input-error' : ''; ?>" value="<?php echo htmlspecialchars($old['asig_fecha_fin'] ?? ''); ?>" required>
                    <div class="form-error <?php echo isset($errores['asig_fecha_fin']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['asig_fecha_fin'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i>
                    Guardar Asignación
                </button>
                <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
