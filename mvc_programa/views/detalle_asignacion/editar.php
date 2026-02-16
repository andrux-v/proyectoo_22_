<?php
/**
 * Vista: Editar Detalle de Asignación (editar.php)
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
$detalle = $detalle ?? ['detasig_id' => 1, 'ASIGNACION_asig_id' => 1, 'detasig_hora_ini' => '08:00', 'detasig_hora_fin' => '12:00'];
$asignaciones = $asignaciones ?? [['asig_id' => 1, 'inst_nombre' => 'Juan Pérez']];
$errores = $errores ?? [];
// --- Fin datos de prueba ---

$title = 'Editar Horario';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Detalles', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Editar Horario</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formEditarDetalle" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="detasig_id" value="<?php echo htmlspecialchars($detalle['detasig_id']); ?>">

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
                                    <?php echo($detalle['ASIGNACION_asig_id'] == $asig['asig_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars('#' . $asig['asig_id'] . ' - ' . $asig['inst_nombre']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="detasig_hora_ini" class="form-label">
                            Hora Inicio <span class="required">*</span>
                        </label>
                        <input
                            type="time"
                            id="detasig_hora_ini"
                            name="detasig_hora_ini"
                            class="form-input"
                            value="<?php echo htmlspecialchars($detalle['detasig_hora_ini']); ?>"
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
                            class="form-input"
                            value="<?php echo htmlspecialchars($detalle['detasig_hora_fin']); ?>"
                            required
                        >
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Actualizar Horario
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
