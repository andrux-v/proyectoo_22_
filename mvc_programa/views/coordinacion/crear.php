<?php
/**
 * Vista: Registrar Coordinación (crear.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: /proyectoo_22_/mvc_programa/views/coordinacion/index.php?rol=instructor');
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/CoordinacionController.php';

$controller = new CoordinacionController();

// Variables para el formulario
$errores = [];
$old = [];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $resultado = $controller->create($_POST);
    
    if ($resultado['success']) {
        // Redirigir al index con mensaje de éxito
        $redirectUrl = '/proyectoo_22_/mvc_programa/views/coordinacion/index.php';
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

// Obtener centros para el select
$centros = $controller->getCentrosFormacion();

$title = 'Registrar Coordinación';

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
    ['label' => 'Registrar'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Registrar Coordinación</h1>
        </div>

        <!-- Form -->
        <div class="form-container">
            <div class="form-card">
                <form id="formCrearCoordinacion" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

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
                            value="<?php echo htmlspecialchars($old['coord_descripcion'] ?? ''); ?>"
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
                                    <?php echo (isset($old['centro_formacion_cent_id']) && $old['centro_formacion_cent_id'] == $centro['cent_id']) ? 'selected' : ''; ?>>
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
                                value="<?php echo htmlspecialchars($old['coord_nombre_coordinador'] ?? ''); ?>"
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
                                value="<?php echo htmlspecialchars($old['coord_correo'] ?? ''); ?>"
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
                                Contraseña Temporal <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="coord_password"
                                name="coord_password"
                                class="form-input <?php echo isset($errores['coord_password']) ? 'input-error' : ''; ?>"
                                placeholder="Mínimo 6 caracteres"
                                required
                                minlength="6"
                                autocomplete="new-password"
                            >
                            <div class="form-error <?php echo isset($errores['coord_password']) ? 'visible' : ''; ?>" id="errorPassword">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['coord_password'] ?? 'La contraseña debe tener al menos 6 caracteres.'); ?></span>
                            </div>
                            <div class="form-hint">El coordinador podrá cambiar esta contraseña al iniciar sesión.</div>
                        </div>

                        <div class="form-group">
                            <label for="coord_password_confirm" class="form-label">
                                Confirmar Contraseña <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="coord_password_confirm"
                                name="coord_password_confirm"
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
                            Guardar Coordinación
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
    document.getElementById('formCrearCoordinacion').addEventListener('submit', function(e) {
        var isValid = true;

        // Validar descripción
        var descripcion = document.getElementById('coord_descripcion');
        if (!descripcion.value.trim()) {
            descripcion.classList.add('input-error');
            document.getElementById('errorDescripcion').classList.add('visible');
            isValid = false;
        }

        // Validar centro
        var centro = document.getElementById('centro_formacion_cent_id');
        if (!centro.value) {
            centro.classList.add('input-error');
            document.getElementById('errorCentro').classList.add('visible');
            isValid = false;
        }

        // Validar nombre coordinador
        var nombreCoord = document.getElementById('coord_nombre_coordinador');
        if (!nombreCoord.value.trim()) {
            nombreCoord.classList.add('input-error');
            document.getElementById('errorNombreCoord').classList.add('visible');
            isValid = false;
        }

        // Validar correo
        var correo = document.getElementById('coord_correo');
        if (!correo.value.trim() || !correo.validity.valid) {
            correo.classList.add('input-error');
            document.getElementById('errorCorreo').classList.add('visible');
            isValid = false;
        }

        // Validar contraseña
        var password = document.getElementById('coord_password');
        if (!password.value || password.value.length < 6) {
            password.classList.add('input-error');
            document.getElementById('errorPassword').classList.add('visible');
            isValid = false;
        }

        // Validar confirmación de contraseña
        var passwordConfirm = document.getElementById('coord_password_confirm');
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
    ['coord_descripcion', 'centro_formacion_cent_id', 'coord_nombre_coordinador', 'coord_correo', 'coord_password', 'coord_password_confirm'].forEach(function(id) {
        var element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function() {
                this.classList.remove('input-error');
                var errorId = 'error' + id.split('_').map(function(word) {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join('').replace('Coord', '');
                
                // Mapeo especial para algunos IDs
                if (id === 'coord_descripcion') errorId = 'errorDescripcion';
                if (id === 'centro_formacion_cent_id') errorId = 'errorCentro';
                if (id === 'coord_nombre_coordinador') errorId = 'errorNombreCoord';
                if (id === 'coord_correo') errorId = 'errorCorreo';
                if (id === 'coord_password') errorId = 'errorPassword';
                if (id === 'coord_password_confirm') errorId = 'errorPasswordConfirm';
                
                var errorDiv = document.getElementById(errorId);
                if (errorDiv) {
                    errorDiv.classList.remove('visible');
                }
            });
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
