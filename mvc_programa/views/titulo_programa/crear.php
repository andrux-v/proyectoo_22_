<?php
/**
 * Vista: Registrar Típulo de Programa (crear.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear/editar)
if ($rol === 'instructor') {
    header('Location: ' . addRolParam('index.php', $rol));
    exit;
}
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$errores = $errores ?? [];
$old = $old ?? [];
// --- Fin datos de prueba ---

$title = 'Registrar Título';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Títulos', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Registrar Título</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearTitulo" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="tibro_nombre" class="form-label">
                            Nombre del Título <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="tibro_nombre"
                            name="tibro_nombre"
                            class="form-input <?php echo isset($errores['tibro_nombre']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Tecnólogo"
                            value="<?php echo htmlspecialchars($old['tibro_nombre'] ?? ''); ?>"
                            required
                            maxlength="45"
                            autocomplete="off"
                        >
                        <div class="form-error <?php echo isset($errores['tibro_nombre']) ? 'visible' : ''; ?>" id="errorNombre">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['tibro_nombre'] ?? 'Este campo es obligatorio.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Título
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<script>
    document.getElementById('formCrearTitulo').addEventListener('submit', function(e) {
        var input = document.getElementById('tibro_nombre');
        var errorDiv = document.getElementById('errorNombre');

        if (!input.value.trim()) {
            e.preventDefault();
            input.classList.add('input-error');
            errorDiv.classList.add('visible');
            input.focus();
        } else {
            input.classList.remove('input-error');
            errorDiv.classList.remove('visible');
        }
    });

    document.getElementById('tibro_nombre').addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('input-error');
            document.getElementById('errorNombre').classList.remove('visible');
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
