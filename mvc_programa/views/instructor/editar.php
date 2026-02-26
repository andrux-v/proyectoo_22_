<?php
/**
 * Vista: Editar Instructor (editar.php)
 */

require_once __DIR__ . '/../../controller/InstructorController.php';

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

$controller = new InstructorController();
$errores = [];

// Obtener ID del instructor
$inst_id = $_GET['id'] ?? null;

if (!$inst_id) {
    header('Location: index.php?error=' . urlencode('ID de instructor no especificado'));
    exit;
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $resultado = $controller->update($_POST);
    
    if ($resultado['success']) {
        header('Location: index.php?mensaje=' . urlencode($resultado['mensaje']));
        exit;
    } else {
        $errores = $resultado['errores'];
    }
}

// Obtener datos del instructor
$instructor = $controller->show($inst_id);

if (!$instructor) {
    header('Location: index.php?error=' . urlencode('Instructor no encontrado'));
    exit;
}

// Obtener centros de formación para el select
$centros = $controller->getCentrosFormacion();

$title = 'Editar Instructor';

// Determinar URL del dashboard según el rol
$dashboard_url = '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php';
if ($rol === 'instructor') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php';
} elseif ($rol === 'centro') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php';
}

$breadcrumb = [
    ['label' => 'Inicio', 'url' => $dashboard_url],
    ['label' => 'Instructores', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <div class="page-header">
            <h1 class="page-title">Editar Instructor</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formEditarInst" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="inst_id" value="<?php echo htmlspecialchars($instructor['inst_id']); ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inst_nombres" class="form-label">
                                Nombres <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="inst_nombres"
                                name="inst_nombres"
                                class="form-input <?php echo isset($errores['inst_nombres']) ? 'input-error' : ''; ?>"
                                value="<?php echo htmlspecialchars($instructor['inst_nombres']); ?>"
                                required
                                maxlength="45"
                            >
                            <div class="form-error <?php echo isset($errores['inst_nombres']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_nombres'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inst_apellidos" class="form-label">
                                Apellidos <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="inst_apellidos"
                                name="inst_apellidos"
                                class="form-input <?php echo isset($errores['inst_apellidos']) ? 'input-error' : ''; ?>"
                                value="<?php echo htmlspecialchars($instructor['inst_apellidos']); ?>"
                                required
                                maxlength="45"
                            >
                            <div class="form-error <?php echo isset($errores['inst_apellidos']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_apellidos'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inst_correo" class="form-label">
                                Correo Electrónico <span class="required">*</span>
                            </label>
                            <input
                                type="email"
                                id="inst_correo"
                                name="inst_correo"
                                class="form-input <?php echo isset($errores['inst_correo']) ? 'input-error' : ''; ?>"
                                value="<?php echo htmlspecialchars($instructor['inst_correo']); ?>"
                                required
                                maxlength="45"
                            >
                            <div class="form-error <?php echo isset($errores['inst_correo']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_correo'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inst_telefono" class="form-label">
                                Teléfono <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="inst_telefono"
                                name="inst_telefono"
                                class="form-input <?php echo isset($errores['inst_telefono']) ? 'input-error' : ''; ?>"
                                value="<?php echo htmlspecialchars($instructor['inst_telefono']); ?>"
                                required
                                maxlength="10"
                            >
                            <div class="form-error <?php echo isset($errores['inst_telefono']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_telefono'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="CENTRO_FORMACION_cent_id" class="form-label">
                                Centro de Formación <span class="required">*</span>
                            </label>
                            <select
                                id="CENTRO_FORMACION_cent_id"
                                name="CENTRO_FORMACION_cent_id"
                                class="form-input <?php echo isset($errores['CENTRO_FORMACION_cent_id']) ? 'input-error' : ''; ?>"
                                required
                            >
                                <option value="">Seleccione un centro de formación</option>
                                <?php foreach ($centros as $centro): ?>
                                    <option value="<?php echo htmlspecialchars($centro['cent_id']); ?>"
                                        <?php echo ($instructor['CENTRO_FORMACION_cent_id'] == $centro['cent_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($centro['cent_nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-error <?php echo isset($errores['CENTRO_FORMACION_cent_id']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['CENTRO_FORMACION_cent_id'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inst_password" class="form-label">
                                Nueva Contraseña <span style="color: #666;">(opcional)</span>
                            </label>
                            <input
                                type="password"
                                id="inst_password"
                                name="inst_password"
                                class="form-input <?php echo isset($errores['inst_password']) ? 'input-error' : ''; ?>"
                                placeholder="Dejar en blanco para mantener la actual"
                                minlength="6"
                                maxlength="45"
                            >
                            <div class="form-error <?php echo isset($errores['inst_password']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_password'] ?? 'Mínimo 6 caracteres.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inst_password_confirm" class="form-label">
                                Confirmar Nueva Contraseña
                            </label>
                            <input
                                type="password"
                                id="inst_password_confirm"
                                name="inst_password_confirm"
                                class="form-input"
                                placeholder="Repita la nueva contraseña"
                                minlength="6"
                                maxlength="45"
                            >
                            <div class="form-error" id="error-password-confirm">
                                <i data-lucide="alert-circle"></i>
                                <span>Las contraseñas no coinciden.</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Actualizar Instructor
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<script>
    // Validación de contraseñas coincidentes (solo si se ingresa una nueva contraseña)
    const password = document.getElementById('inst_password');
    const passwordConfirm = document.getElementById('inst_password_confirm');
    const errorPasswordConfirm = document.getElementById('error-password-confirm');
    const form = document.getElementById('formEditarInst');

    function validarPasswords() {
        // Solo validar si se ingresó una contraseña
        if (password.value || passwordConfirm.value) {
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('input-error');
                errorPasswordConfirm.classList.add('visible');
                return false;
            } else {
                passwordConfirm.classList.remove('input-error');
                errorPasswordConfirm.classList.remove('visible');
                return true;
            }
        }
        return true;
    }

    passwordConfirm.addEventListener('input', validarPasswords);
    password.addEventListener('input', validarPasswords);

    form.addEventListener('submit', function(e) {
        if (!validarPasswords()) {
            e.preventDefault();
            passwordConfirm.focus();
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
