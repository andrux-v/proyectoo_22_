<?php
/**
 * Vista: Registrar Programa (crear.php)
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
$titulos = $titulos ?? [
    ['tibro_id' => 1, 'tibro_nombre' => 'Tecn贸logo'],
    ['tibro_id' => 2, 'tibro_nombre' => 'T茅cnico'],
];
// --- Fin datos de prueba ---

$title = 'Registrar Programa';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Programas', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Registrar'],
];

// Incluir el header seg鷑 el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Registrar Programa</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearProg" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="prog_codigo" class="form-label">
                            C贸digo del Programa <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="prog_codigo"
                            name="prog_codigo"
                            class="form-input <?php echo isset($errores['prog_codigo']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: 228106"
                            value="<?php echo htmlspecialchars($old['prog_codigo'] ?? ''); ?>"
                            required
                            maxlength="20"
                        >
                        <div class="form-error <?php echo isset($errores['prog_codigo']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['prog_codigo'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prog_denominacion" class="form-label">
                            Denominaci贸n <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="prog_denominacion"
                            name="prog_denominacion"
                            class="form-input <?php echo isset($errores['prog_denominacion']) ? 'input-error' : ''; ?>"
                            placeholder="Ej: An谩lisis y Desarrollo de Software"
                            value="<?php echo htmlspecialchars($old['prog_denominacion'] ?? ''); ?>"
                            required
                            maxlength="200"
                        >
                        <div class="form-error <?php echo isset($errores['prog_denominacion']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['prog_denominacion'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prog_tipo" class="form-label">
                            Tipo de Formaci贸n <span class="required">*</span>
                        </label>
                        <select
                            id="prog_tipo"
                            name="prog_tipo"
                            class="form-input <?php echo isset($errores['prog_tipo']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <option value="Titulada" <?php echo(isset($old['prog_tipo']) && $old['prog_tipo'] == 'Titulada') ? 'selected' : ''; ?>>Titulada</option>
                            <option value="Complementaria" <?php echo(isset($old['prog_tipo']) && $old['prog_tipo'] == 'Complementaria') ? 'selected' : ''; ?>>Complementaria</option>
                        </select>
                        <div class="form-error <?php echo isset($errores['prog_tipo']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['prog_tipo'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="TIT_PROGRAMA_tibro_id" class="form-label">
                            Nivel de Formaci贸n <span class="required">*</span>
                        </label>
                        <select
                            id="TIT_PROGRAMA_tibro_id"
                            name="TIT_PROGRAMA_tibro_id"
                            class="form-input <?php echo isset($errores['TIT_PROGRAMA_tibro_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($titulos as $titulo): ?>
                                <option
                                    value="<?php echo $titulo['tibro_id']; ?>"
                                    <?php echo(isset($old['TIT_PROGRAMA_tibro_id']) && $old['TIT_PROGRAMA_tibro_id'] == $titulo['tibro_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($titulo['tibro_nombre']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['TIT_PROGRAMA_tibro_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['TIT_PROGRAMA_tibro_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Programa
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
