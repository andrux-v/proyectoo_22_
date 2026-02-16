<?php
/**
 * Vista: Registrar Coordinación (crear.php)
 *
 * Variables esperadas del controlador:
 *   $rol      — 'coordinador' | 'instructor'
 *   $centros  — Array de centros de formación [['cent_id' => 1, 'cent_nombre' => '...'], ...]
 *   $errores  — (Opcional) Array de errores ['coord_nombre' => 'El nombre es requerido']
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
$centros = $centros ?? [
    ['cent_id' => 1, 'cent_nombre' => 'Centro de Gestión de Mercados, Logística y TIC'],
    ['cent_id' => 2, 'cent_nombre' => 'Centro de Tecnologías del Transporte'],
    ['cent_id' => 3, 'cent_nombre' => 'Centro de Manufactura en Textil y Cuero'],
];
$errores = $errores ?? [];
$old = $old ?? [];
// --- Fin datos de prueba ---

$title = 'Registrar Coordinación';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Coordinaciones', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Registrar Coordinación</h1>
        </div>

        <!-- Form -->
        <div class="form-container">
            <div class="form-card">
                <form id="formCrearCoordinacion" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="coord_nombre" class="form-label">
                            Nombre de la Coordinación <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="coord_nombre"
                            name="coord_nombre"
                            class="form-input <?php echo isset($errores['coord_nombre']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Coordinación Académica"
                            value="<?php echo htmlspecialchars($old['coord_nombre'] ?? ''); ?>"
                            required
                            maxlength="45"
                            autocomplete="off"
                        >
                        <div class="form-error <?php echo isset($errores['coord_nombre']) ? 'visible' : ''; ?>" id="errorNombre">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['coord_nombre'] ?? 'Este campo es obligatorio.'); ?></span>
                        </div>
                        <div class="form-hint">Ingrese el nombre de la coordinación.</div>
                    </div>

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
                        <div class="form-error <?php echo isset($errores['CENTRO_FORMACION_cent_id']) ? 'visible' : ''; ?>" id="errorCentro">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['CENTRO_FORMACION_cent_id'] ?? 'Debe seleccionar un centro de formación.'); ?></span>
                        </div>
                        <div class="form-hint">Seleccione el centro de formación al que pertenece esta coordinación.</div>
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
    // Client-side visual validation (real validation in controller)
    document.getElementById('formCrearCoordinacion').addEventListener('submit', function(e) {
        var inputNombre = document.getElementById('coord_nombre');
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
    document.getElementById('coord_nombre').addEventListener('input', function() {
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
