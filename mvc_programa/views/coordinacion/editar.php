<?php
/**
 * Vista: Editar Coordinación (editar.php)
 *
 * Variables esperadas del controlador:
 *   $coordinacion — Array con datos de la coordinación ['coord_id' => 1, 'coord_nombre' => '...', 'CENTRO_FORMACION_cent_id' => 1]
 *   $centros      — Array de centros de formación [['cent_id' => 1, 'cent_nombre' => '...'], ...]
 *   $rol          — 'coordinador' | 'instructor'
 *   $errores      — (Opcional) Array de errores ['coord_nombre' => 'El nombre es requerido']
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: ' . addRolParam('index.php', $rol));
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/CoordinacionController.php';

$controller = new CoordinacionController();

// Obtener ID de la coordinación
$coord_id = $_GET['id'] ?? null;

if (!$coord_id) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('ID de coordinación no especificado'));
    exit;
}

// Variables para el formulario
$errores = [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $resultado = $controller->update($_POST);
    
    if ($resultado['success']) {
        header('Location: ' . addRolParam('index.php', $rol) . '&mensaje=' . urlencode($resultado['mensaje']));
        exit;
    } else {
        $errores = $resultado['errores'];
    }
}

// Obtener datos de la coordinación
$coordinacion = $controller->show($coord_id);

if (!$coordinacion) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('Coordinación no encontrada'));
    exit;
}

// Obtener centros para el select
$centros = $controller->getCentrosFormacion();

$title = 'Editar Coordinación';

// Determinar URL del dashboard según el rol
$dashboard_url = '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php';
if ($rol === 'instructor') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php';
} elseif ($rol === 'centro') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php';
}

$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $dashboard_url],
    ['label' => 'Coordinaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Editar Coordinación</h1>
        </div>

        <!-- Form -->
        <div class="form-container">
            <div class="form-card">
                <form id="formEditarCoordinacion" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="coord_id" value="<?php echo htmlspecialchars($coordinacion['coord_id']); ?>">

                    <div class="form-group">
                        <label for="coord_descripcion" class="form-label">
                            Descripción de la Coordinación <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="coord_descripcion"
                            name="coord_descripcion"
                            class="form-input <?php echo isset($errores['coord_descripcion']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Coordinación Académica"
                            value="<?php echo htmlspecialchars($coordinacion['coord_descripcion']); ?>"
                            required
                            maxlength="45"
                            autocomplete="off"
                        >
                        <div class="form-error <?php echo isset($errores['coord_descripcion']) ? 'visible' : ''; ?>" id="errorDescripcion">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['coord_descripcion'] ?? 'Este campo es obligatorio.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="centro_formacion_cent_id" class="form-label">
                            Centro de Formación <span class="required">*</span>
                        </label>
                        <select
                            id="centro_formacion_cent_id"
                            name="centro_formacion_cent_id"
                            class="form-input <?php echo isset($errores['centro_formacion_cent_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione un centro de formación</option>
                            <?php foreach ($centros as $centro): ?>
                                <option value="<?php echo htmlspecialchars($centro['cent_id']); ?>"
                                    <?php echo ($coordinacion['centro_formacion_cent_id'] == $centro['cent_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($centro['cent_nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-error <?php echo isset($errores['centro_formacion_cent_id']) ? 'visible' : ''; ?>" id="errorCentro">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['centro_formacion_cent_id'] ?? 'Debe seleccionar un centro de formación.'); ?></span>
                        </div>
                    </div>

                    <hr style="margin: 30px 0; border: none; border-top: 1px solid #e2e8f0;">
                    
                    <h3 style="margin-bottom: 20px; color: #2d3748; font-size: 18px;">
                        <i data-lucide="user-cog" style="width: 20px; height: 20px; vertical-align: middle;"></i>
                        Datos del Coordinador
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="coord_nombre_coordinador" class="form-label">
                                Nombre del Coordinador <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="coord_nombre_coordinador"
                                name="coord_nombre_coordinador"
                                class="form-input <?php echo isset($errores['coord_nombre_coordinador']) ? 'input-error' : ''; ?>"
                                placeholder="Ej: Juan Pérez"
                                value="<?php echo htmlspecialchars($coordinacion['coord_nombre_coordinador'] ?? ''); ?>"
                                required
                                maxlength="100"
                                autocomplete="name"
                            >
                            <div class="form-error <?php echo isset($errores['coord_nombre_coordinador']) ? 'visible' : ''; ?>" id="errorNombreCoord">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['coord_nombre_coordinador'] ?? 'Este campo es obligatorio.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="coord_correo" class="form-label">
                                Correo Electrónico <span class="required">*</span>
                            </label>
                            <input
                                type="email"
                                id="coord_correo"
                                name="coord_correo"
                                class="form-input <?php echo isset($errores['coord_correo']) ? 'input-error' : ''; ?>"
                                placeholder="coordinador@sena.edu.co"
                                value="<?php echo htmlspecialchars($coordinacion['coord_correo'] ?? ''); ?>"
                                required
                                maxlength="100"
                                autocomplete="email"
                            >
                            <div class="form-error <?php echo isset($errores['coord_correo']) ? 'visible' : ''; ?>" id="errorCorreo">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['coord_correo'] ?? 'Ingrese un correo válido.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="coord_password" class="form-label">
                                Nueva Contraseña (opcional)
                            </label>
                            <input
                                type="password"
                                id="coord_password"
                                name="coord_password"
                                class="form-input <?php echo isset($errores['coord_password']) ? 'input-error' : ''; ?>"
                                placeholder="Dejar en blanco para mantener la actual"
                                minlength="6"
                                autocomplete="new-password"
                            >
                            <div class="form-error <?php echo isset($errores['coord_password']) ? 'visible' : ''; ?>" id="errorPassword">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['coord_password'] ?? 'La contraseña debe tener al menos 6 caracteres.'); ?></span>
                            </div>
                            <div class="form-hint">Solo complete este campo si desea cambiar la contraseña del coordinador.</div>
                        </div>

                        <div class="form-group">
                            <label for="coord_password_confirm" class="form-label">
                                Confirmar Nueva Contraseña
                            </label>
                            <input
                                type="password"
                                id="coord_password_confirm"
                                name="coord_password_confirm"
                                class="form-input"
                                placeholder="Repita la nueva contraseña"
                                minlength="6"
                                autocomplete="new-password"
                            >
                            <div class="form-error" id="errorPasswordConfirm">
                                <i data-lucide="alert-circle"></i>
                                <span>Las contraseñas no coinciden.</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Actualizar Coordinación
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<script>
    // Client-side visual validation (real validation in controller)
    document.getElementById('formEditarCoordinacion').addEventListener('submit', function(e) {
        var inputNombre = document.getElementById('coord_descripcion');
        var inputCentro = document.getElementById('CENTRO_FORMACION_cent_id');
        var errorNombre = document.getElementById('errorNombre');
        var errorCentro = document.getElementById('errorCentro');
        var isValid = true;

        // Validate nombre
        if (!inputNombre.value.trim()) {
            inputNombre.classList.add('input-error');
            errorNombre.classList.add('visible');
            isValid = false;
        } else {
            inputNombre.classList.remove('input-error');
            errorNombre.classList.remove('visible');
        }

        // Validate centro
        if (!inputCentro.value) {
            inputCentro.classList.add('input-error');
            errorCentro.classList.add('visible');
            isValid = false;
        } else {
            inputCentro.classList.remove('input-error');
            errorCentro.classList.remove('visible');
        }

        if (!isValid) {
            e.preventDefault();
            inputNombre.focus();
        }
    });

    // Remove error state on input
    document.getElementById('coord_descripcion').addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('input-error');
            document.getElementById('errorNombre').classList.remove('visible');
        }
    });

    document.getElementById('CENTRO_FORMACION_cent_id').addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('input-error');
            document.getElementById('errorCentro').classList.remove('visible');
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
