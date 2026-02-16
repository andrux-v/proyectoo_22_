<?php
/**
 * Vista: Editar Asignaci贸n (editar.php)
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
$asignacion = $asignacion ?? [
    'asig_id' => 1,
    'FICHA_fich_id' => '228106-1',
    'INSTRUCTOR_inst_id' => 1,
    'AMBIENTE_id_ambiente' => 1,
    'COMPETENCIA_comp_id' => 1,
    'asig_fecha_ini' => '2023-01-20',
    'asig_fecha_fin' => '2023-06-20'
];
$fichas = $fichas ?? [['fich_id' => '228106-1']];
$instructores = $instructores ?? [['inst_id' => 1, 'inst_nombre' => 'Juan', 'inst_apellidos' => 'P茅rez']];
$ambientes = $ambientes ?? [['id_ambiente' => 1, 'amb_nombre' => 'Laboratorio 1']];
$competencias = $competencias ?? [['comp_id' => 1, 'comp_nombre_corto' => 'Promover salud']];
$errores = $errores ?? [];
// --- Fin datos de prueba ---

$title = 'Editar Asignaci贸n';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Asignaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
];

// Incluir el header seg鷑 el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Editar Asignaci贸n</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formEditarAsig" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="asig_id" value="<?php echo htmlspecialchars($asignacion['asig_id']); ?>">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="FICHA_fich_id" class="form-label">Ficha <span class="required">*</span></label>
                            <select id="FICHA_fich_id" name="FICHA_fich_id" class="form-input" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($fichas as $f): ?>
                                    <option value="<?php echo $f['fich_id']; ?>" <?php echo($asignacion['FICHA_fich_id'] == $f['fich_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($f['fich_id']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="INSTRUCTOR_inst_id" class="form-label">Instructor <span class="required">*</span></label>
                            <select id="INSTRUCTOR_inst_id" name="INSTRUCTOR_inst_id" class="form-input" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($instructores as $inst): ?>
                                    <option value="<?php echo $inst['inst_id']; ?>" <?php echo($asignacion['INSTRUCTOR_inst_id'] == $inst['inst_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($inst['inst_nombre'] . ' ' . $inst['inst_apellidos']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="AMBIENTE_id_ambiente" class="form-label">Ambiente <span class="required">*</span></label>
                            <select id="AMBIENTE_id_ambiente" name="AMBIENTE_id_ambiente" class="form-input" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($ambientes as $amb): ?>
                                    <option value="<?php echo $amb['id_ambiente']; ?>" <?php echo($asignacion['AMBIENTE_id_ambiente'] == $amb['id_ambiente']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($amb['amb_nombre']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="COMPETENCIA_comp_id" class="form-label">Competencia <span class="required">*</span></label>
                            <select id="COMPETENCIA_comp_id" name="COMPETENCIA_comp_id" class="form-input" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($competencias as $comp): ?>
                                    <option value="<?php echo $comp['comp_id']; ?>" <?php echo($asignacion['COMPETENCIA_comp_id'] == $comp['comp_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($comp['comp_nombre_corto']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="asig_fecha_ini" class="form-label">Fecha Inicio <span class="required">*</span></label>
                            <input type="date" id="asig_fecha_ini" name="asig_fecha_ini" class="form-input" value="<?php echo htmlspecialchars($asignacion['asig_fecha_ini']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="asig_fecha_fin" class="form-label">Fecha Fin <span class="required">*</span></label>
                            <input type="date" id="asig_fecha_fin" name="asig_fecha_fin" class="form-input" value="<?php echo htmlspecialchars($asignacion['asig_fecha_fin']); ?>" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Actualizar Asignaci贸n
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
