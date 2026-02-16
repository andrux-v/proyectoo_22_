<?php
/**
 * Vista: Registrar Competencia (crear.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor (no tiene permisos para crear)
if ($rol === 'instructor') {
    header('Location: ' . addRolParam('index.php', $rol));
    exit;
}

// --- Datos de prueba ---
$errores = $errores ?? [];
$old = $old ?? [];
// --- Fin datos de prueba ---

$title = 'Registrar Competencia';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Competencias', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Registrar Competencia</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearComp" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="comp_nombre_corto" class="form-label">
                            Nombre Corto <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="comp_nombre_corto"
                            name="comp_nombre_corto"
                            class="form-input <?php echo isset($errores['comp_nombre_corto']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Promover salud"
                            value="<?php echo htmlspecialchars($old['comp_nombre_corto'] ?? ''); ?>"
                            required
                            maxlength="45"
                        >
                        <div class="form-error <?php echo isset($errores['comp_nombre_corto']) ? 'visible' : ''; ?>" id="errorNombre">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['comp_nombre_corto'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comp_nombre_unidad_competencia" class="form-label">
                            Nombre Unidad de Competencia <span class="required">*</span>
                        </label>
                        <textarea
                            id="comp_nombre_unidad_competencia"
                            name="comp_nombre_unidad_competencia"
                            class="form-input <?php echo isset($errores['comp_nombre_unidad_competencia']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: Promover la interacción idónea consigo mismo..."
                            required
                            rows="3"
                        ><?php echo htmlspecialchars($old['comp_nombre_unidad_competencia'] ?? ''); ?></textarea>
                        <div class="form-error <?php echo isset($errores['comp_nombre_unidad_competencia']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['comp_nombre_unidad_competencia'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="comp_horas" class="form-label">
                            Horas <span class="required">*</span>
                        </label>
                        <input
                            type="number"
                            id="comp_horas"
                            name="comp_horas"
                            class="form-input <?php echo isset($errores['comp_horas']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: 40"
                            value="<?php echo htmlspecialchars($old['comp_horas'] ?? ''); ?>"
                            required
                            min="1"
                        >
                        <div class="form-error <?php echo isset($errores['comp_horas']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['comp_horas'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Competencia
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
