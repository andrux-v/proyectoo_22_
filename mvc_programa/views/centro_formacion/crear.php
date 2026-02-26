<?php
/**
 * Vista: Registrar Centro de Formación (crear.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: /proyectoo_22_/mvc_programa/views/centro_formacion/index.php?rol=instructor');
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/CentroFormacionController.php';

$controller = new CentroFormacionController();

// Variables para el formulario
$errores = [];
$old = [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $resultado = $controller->create($_POST);
    
    if ($resultado['success']) {
        // Redirigir al index con mensaje de éxito
        $redirectUrl = '/proyectoo_22_/mvc_programa/views/centro_formacion/index.php';
        if ($rol === 'instructor') {
            $redirectUrl .= '?rol=instructor';
        }
        $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'mensaje=' . urlencode($resultado['mensaje']);
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        $errores = $resultado['errores'];
        $old = $_POST;
        error_log("Errores al crear centro: " . print_r($errores, true));
    }
}

$title = 'Registrar Centro de Formación';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Centros de Formación', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Registrar'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Registrar Centro de Formación</h1>
        </div>

        <!-- Error General -->
        <?php if (isset($errores['general'])): ?>
            <div class="alert alert-error">
                <i data-lucide="alert-circle"></i>
                <?php echo htmlspecialchars($errores['general']); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="form-container">
            <div class="form-card">
                <form id="formCrearCentro" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cent_nombre" class="form-label">
                                Nombre del Centro de Formación <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="cent_nombre"
                                name="cent_nombre"
                                class="form-input <?php echo isset($errores['cent_nombre']) ? 'input-error' : ''; ?>"
                                placeholder="Ej: Centro de Gestión de Mercados, Logística y TIC"
                                value="<?php echo htmlspecialchars($old['cent_nombre'] ?? ''); ?>"
                                required
                                maxlength="100"
                                autocomplete="off"
                            >
                            <div class="form-error <?php echo isset($errores['cent_nombre']) ? 'visible' : ''; ?>" id="errorNombre">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['cent_nombre'] ?? 'Este campo es obligatorio.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cent_correo" class="form-label">
                                Correo Electrónico <span class="required">*</span>
                            </label>
                            <input
                                type="email"
                                id="cent_correo"
                                name="cent_correo"
                                class="form-input <?php echo isset($errores['cent_correo']) ? 'input-error' : ''; ?>"
                                placeholder="centro@sena.edu.co"
                                value="<?php echo htmlspecialchars($old['cent_correo'] ?? ''); ?>"
                                required
                                maxlength="100"
                                autocomplete="email"
                            >
                            <div class="form-error <?php echo isset($errores['cent_correo']) ? 'visible' : ''; ?>" id="errorCorreo">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['cent_correo'] ?? 'Este campo es obligatorio.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cent_password" class="form-label">
                                Contraseña <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="cent_password"
                                name="cent_password"
                                class="form-input <?php echo isset($errores['cent_password']) ? 'input-error' : ''; ?>"
                                placeholder="Mínimo 6 caracteres"
                                required
                                minlength="6"
                                autocomplete="new-password"
                            >
                            <div class="form-error <?php echo isset($errores['cent_password']) ? 'visible' : ''; ?>" id="errorPassword">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['cent_password'] ?? 'La contraseña debe tener al menos 6 caracteres.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cent_password_confirm" class="form-label">
                                Confirmar Contraseña <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="cent_password_confirm"
                                name="cent_password_confirm"
                                class="form-input"
                                placeholder="Repita la contraseña"
                                required
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
                            Guardar Centro
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<script>
    // Client-side validation
    document.getElementById('formCrearCentro').addEventListener('submit', function(e) {
        var nombre = document.getElementById('cent_nombre');
        var correo = document.getElementById('cent_correo');
        var password = document.getElementById('cent_password');
        var passwordConfirm = document.getElementById('cent_password_confirm');
        var isValid = true;

        // Validar nombre
        if (!nombre.value.trim()) {
            nombre.classList.add('input-error');
            document.getElementById('errorNombre').classList.add('visible');
            isValid = false;
        }

        // Validar correo
        if (!correo.value.trim() || !correo.validity.valid) {
            correo.classList.add('input-error');
            document.getElementById('errorCorreo').classList.add('visible');
            isValid = false;
        }

        // Validar contraseña
        if (!password.value || password.value.length < 6) {
            password.classList.add('input-error');
            document.getElementById('errorPassword').classList.add('visible');
            isValid = false;
        }

        // Validar confirmación de contraseña
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.classList.add('input-error');
            document.getElementById('errorPasswordConfirm').classList.add('visible');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Remove error states on input
    ['cent_nombre', 'cent_correo', 'cent_password', 'cent_password_confirm'].forEach(function(id) {
        var element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function() {
                this.classList.remove('input-error');
                var errorId = 'error' + id.split('_').map(function(word) {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join('').replace('Cent', '');
                var errorDiv = document.getElementById(errorId);
                if (errorDiv) {
                    errorDiv.classList.remove('visible');
                }
            });
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
