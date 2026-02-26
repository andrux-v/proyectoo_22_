<?php
/**
 * Vista: Editar Programa
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
require_once __DIR__ . '/../../controller/ProgramaController.php';

$controller = new ProgramaController();

// Obtener código del programa
$prog_codigo = $_GET['codigo'] ?? null;

if (!$prog_codigo) {
    header('Location: index.php?rol=' . $rol . '&error=' . urlencode('Código de programa no proporcionado'));
    exit;
}

// Obtener el programa
$programa = $controller->show($prog_codigo);

if (!$programa) {
    header('Location: index.php?rol=' . $rol . '&error=' . urlencode('Programa no encontrado'));
    exit;
}

$errores = [];
$old = [];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'prog_codigo' => $prog_codigo,
        'prog_denominacion' => $_POST['prog_denominacion'] ?? '',
        'tit_programa_titpro_id' => $_POST['tit_programa_titpro_id'] ?? '',
        'prog_tipo' => $_POST['prog_tipo'] ?? ''
    ];

    $resultado = $controller->update($data);

    if ($resultado['success']) {
        $redirectUrl = 'index.php?rol=' . $rol . '&mensaje=' . urlencode($resultado['mensaje']);
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        $errores = $resultado['errores'];
        $old = $data;
        // Actualizar programa con los datos enviados para mostrar en el formulario
        $programa = array_merge($programa, $data);
    }
}

// Obtener niveles de formación
$titulos = $controller->getTitulosPrograma();

$rol = $rol ?? 'coordinador';

$title = 'Editar Programa';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : ($rol === 'centro' ? '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php')],
    ['label' => 'Programas', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
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
            <h1 class="page-title">Editar Programa</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formEditarProg" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <!-- Código es PK, se envía como hidden para el WHERE, pero también se muestra readonly -->
                    <input type="hidden" name="prog_codigo_pk" value="<?php echo htmlspecialchars($programa['prog_codigo']); ?>">

                    <div class="form-group">
                        <label for="prog_codigo" class="form-label">
                            Código del Programa (No editable)
                        </label>
                        <input
                            type="text"
                            id="prog_codigo"
                            name="prog_codigo"
                            class="form-input"
                            value="<?php echo htmlspecialchars($programa['prog_codigo']); ?>"
                            readonly
                            style="background-color: var(--gray-100); color: var(--gray-500);"
                        >
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
                            value="<?php echo htmlspecialchars($programa['prog_denominacion']); ?>"
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
                            <option value="Titulada" <?php echo($programa['prog_tipo'] == 'Titulada') ? 'selected' : ''; ?>>Titulada</option>
                            <option value="Complementaria" <?php echo($programa['prog_tipo'] == 'Complementaria') ? 'selected' : ''; ?>>Complementaria</option>
                        </select>
                        <div class="form-error <?php echo isset($errores['prog_tipo']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['prog_tipo'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tit_programa_titpro_id" class="form-label">
                            Nivel de Formación <span class="required">*</span>
                        </label>
                        <select
                            id="tit_programa_titpro_id"
                            name="tit_programa_titpro_id"
                            class="form-input <?php echo isset($errores['tit_programa_titpro_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($titulos as $titulo): ?>
                                <option
                                    value="<?php echo $titulo['titpro_id']; ?>"
                                    <?php echo($programa['tit_programa_titpro_id'] == $titulo['titpro_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($titulo['titpro_nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-error <?php echo isset($errores['tit_programa_titpro_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['tit_programa_titpro_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Actualizar Programa
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
