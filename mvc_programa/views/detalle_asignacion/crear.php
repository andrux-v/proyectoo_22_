<?php
/**
 * Vista: Registrar Detalle de Asignación (crear.php)
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
$asignaciones = $asignaciones ?? [
    ['asig_id' => 1, 'inst_nombre' => 'Juan Pérez', 'comp_nombre_corto' => 'Promover salud'],
];
// --- Fin datos de prueba ---

$title = 'Registrar Horario';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Detalles', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Registrar Horario</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearDetalle" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-group">
                        <label for="ASIGNACION_asig_id" class="form-label">
                            Asignación <span class="required">*</span>
                        </label>
                        <select
                            id="ASIGNACION_asig_id"
                            name="ASIGNACION_asig_id"
                            class="form-input <?php echo isset($errores['ASIGNACION_asig_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($asignaciones as $asig): ?>
                                <option
                                    value="<?php echo $asig['asig_id']; ?>"
                                    <?php echo(isset($old['ASIGNACION_asig_id']) && $old['ASIGNACION_asig_id'] == $asig['asig_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars('#' . $asig['asig_id'] . ' - ' . $asig['inst_nombre']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['ASIGNACION_asig_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['ASIGNACION_asig_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="detasig_hora_ini" class="form-label">
                            Hora Inicio <span class="required">*</span>
                        </label>
                        <input
                            type="time"
                            id="detasig_hora_ini"
                            name="detasig_hora_ini"
                            class="form-input <?php echo isset($errores['detasig_hora_ini']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($old['detasig_hora_ini'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="detasig_hora_fin" class="form-label">
                            Hora Fin <span class="required">*</span>
                        </label>
                        <input
                            type="time"
                            id="detasig_hora_fin"
                            name="detasig_hora_fin"
                            class="form-input <?php echo isset($errores['detasig_hora_fin']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($old['detasig_hora_fin'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Horario
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
