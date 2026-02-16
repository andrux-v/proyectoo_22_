<?php
/**
 * Vista: Registrar Centro de Formación (crear.php)
 *
 * Variables esperadas del controlador:
 *   $rol      — 'coordinador' | 'instructor'
 *   $errores  — (Opcional) Array de errores ['cent_nombre' => 'El nombre es requerido']
 *   $old      — (Opcional) Datos anteriores para repoblar el formulario
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: ' . addRolParam('index.php', $rol));
    exit;
}
// --- Datos de prueba (eliminar cuando el controlador los proporcione) ---
$errores = $errores ?? [];
$old = $old ?? [];
// --- Fin datos de prueba ---

$title = 'Registrar Centro de Formación';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Centros de Formación', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Registrar'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Registrar Centro de Formación</h1>
        </div>

        <!-- Form -->
        <div class="form-container">
            <div class="form-card">
                <form id="formCrearCentro" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

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
                        <div class="form-hint">Ingrese el nombre completo del centro de formación.</div>
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
    // Client-side visual validation (real validation in controller)
    document.getElementById('formCrearCentro').addEventListener('submit', function(e) {
        var input = document.getElementById('cent_nombre');
        var errorDiv = document.getElementById('errorNombre');
        var value = input.value.trim();

        if (!value) {
            e.preventDefault();
            input.classList.add('input-error');
            errorDiv.classList.add('visible');
            input.focus();
        } else {
            input.classList.remove('input-error');
            errorDiv.classList.remove('visible');
        }
    });

    // Remove error state on input
    document.getElementById('cent_nombre').addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('input-error');
            document.getElementById('errorNombre').classList.remove('visible');
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
