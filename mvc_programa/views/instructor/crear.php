<?php
/**
 * Vista: Registrar Instructor (crear.php)
 */

require_once __DIR__ . '/../../controller/InstructorController.php';

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

$controller = new InstructorController();
$errores = [];
$old = [];

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $resultado = $controller->create($_POST);
    
    if ($resultado['success']) {
        // Redirigir al listado con mensaje de éxito
        header('Location: index.php?mensaje=' . urlencode($resultado['mensaje']));
        exit;
    } else {
        $errores = $resultado['errores'];
        $old = $_POST;
    }
}

// Obtener centros de formación para el select
$centros = $controller->getCentrosFormacion();

$title = 'Registrar Instructor';

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
    ['label' => 'Registrar'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <div class="page-header">
            <h1 class="page-title">Registrar Instructor</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearInst" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

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
                                placeholder="Ej: Juan"
                                value="<?php echo htmlspecialchars($old['inst_nombres'] ?? ''); ?>"
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
                                placeholder="Ej: Pérez"
                                value="<?php echo htmlspecialchars($old['inst_apellidos'] ?? ''); ?>"
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
                                placeholder="Ej: juan@sena.edu.co"
                                value="<?php echo htmlspecialchars($old['inst_correo'] ?? ''); ?>"
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
                                placeholder="Ej: 3001234567"
                                value="<?php echo htmlspecialchars($old['inst_telefono'] ?? ''); ?>"
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
                                        <?php echo (isset($old['CENTRO_FORMACION_cent_id']) && $old['CENTRO_FORMACION_cent_id'] == $centro['cent_id']) ? 'selected' : ''; ?>>
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
                                Contraseña <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="inst_password"
                                name="inst_password"
                                class="form-input <?php echo isset($errores['inst_password']) ? 'input-error' : ''; ?>"
                                placeholder="Mínimo 6 caracteres"
                                required
                                minlength="6"
                                maxlength="45"
                            >
                            <div class="form-error <?php echo isset($errores['inst_password']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_password'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inst_password_confirm" class="form-label">
                                Confirmar Contraseña <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="inst_password_confirm"
                                name="inst_password_confirm"
                                class="form-input"
                                placeholder="Repita la contraseña"
                                required
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
                            Guardar Instructor
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<script>
    // Validación de contraseñas coincidentes
    const password = document.getElementById('inst_password');
    const passwordConfirm = document.getElementById('inst_password_confirm');
    const errorPasswordConfirm = document.getElementById('error-password-confirm');
    const form = document.getElementById('formCrearInst');

    function validarPasswords() {
        if (passwordConfirm.value && password.value !== passwordConfirm.value) {
            passwordConfirm.classList.add('input-error');
            errorPasswordConfirm.classList.add('visible');
            return false;
        } else {
            passwordConfirm.classList.remove('input-error');
            errorPasswordConfirm.classList.remove('visible');
            return true;
        }
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
