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
// --- Datos de prueba (eliminar cuando el controlador los proporcione) ---
$coordinacion = $coordinacion ?? ['coord_id' => 1, 'coord_nombre' => 'Coordinación Académica', 'CENTRO_FORMACION_cent_id' => 1];
$centros = $centros ?? [
    ['cent_id' => 1, 'cent_nombre' => 'Centro de Gestión de Mercados, Logística y TIC'],
    ['cent_id' => 2, 'cent_nombre' => 'Centro de Tecnologías del Transporte'],
    ['cent_id' => 3, 'cent_nombre' => 'Centro de Manufactura en Textil y Cuero'],
];
$errores = $errores ?? [];
// --- Fin datos de prueba ---

$title = 'Editar Coordinación';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Coordinaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
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
            <h1 class="page-title">Editar Coordinación</h1>
        </div>

        <!-- Form -->
        <div class="form-container">
            <div class="form-card">
                <form id="formEditarCoordinacion" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="coord_id" value="<?php echo htmlspecialchars($coordinacion['coord_id']); ?>">

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
                            value="<?php echo htmlspecialchars($coordinacion['coord_nombre']); ?>"
                            required
                            maxlength="45"
                            autocomplete="off"
                        >
                        <div class="form-error <?php echo isset($errores['coord_nombre']) ? 'visible' : ''; ?>" id="errorNombre">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['coord_nombre'] ?? 'Este campo es obligatorio.'); ?></span>
                        </div>
                        <div class="form-hint">Modifique el nombre de la coordinación.</div>
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
                                    <?php echo ($coordinacion['CENTRO_FORMACION_cent_id'] == $centro['cent_id']) ? 'selected' : ''; ?>>
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
