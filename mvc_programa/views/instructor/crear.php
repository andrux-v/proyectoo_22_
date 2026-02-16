<?php
/**
 * Vista: Registrar Instructor (crear.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$errores = $errores ?? [];
$old = $old ?? [];
// --- Fin datos de prueba ---

$title = 'Registrar Instructor';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Instructores', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Registrar Instructor</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearInst" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="inst_nombre" class="form-label">
                            Nombres <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="inst_nombre"
                            name="inst_nombre"
                            class="form-input <?php echo isset($errores['inst_nombre']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Juan"
                            value="<?php echo htmlspecialchars($old['inst_nombre'] ?? ''); ?>"
                            required
                            maxlength="45"
                        >
                        <div class="form-error <?php echo isset($errores['inst_nombre']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['inst_nombre'] ?? 'Requerido.'); ?></span>
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
                            maxlength="45"
                        >
                        <div class="form-error <?php echo isset($errores['inst_telefono']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['inst_telefono'] ?? 'Requerido.'); ?></span>
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

<?php include __DIR__ . '/../layout/footer.php'; ?>
