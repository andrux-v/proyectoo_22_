<?php
/**
 * Vista: Registrar Asignaci贸n (crear.php)
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
$fichas = $fichas ?? [
    ['fich_id' => '228106-1'],
];
$instructores = $instructores ?? [
    ['inst_id' => 1, 'inst_nombre' => 'Juan', 'inst_apellidos' => 'P茅rez'],
];
$ambientes = $ambientes ?? [
    ['id_ambiente' => 1, 'amb_nombre' => 'Laboratorio 1'],
];
$competencias = $competencias ?? [
    ['comp_id' => 1, 'comp_nombre_corto' => 'Promover salud'],
];
// --- Fin datos de prueba ---

$title = 'Nueva Asignaci贸n';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Asignaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Nueva'],
];

// Incluir el header seg鷑 el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Nueva Asignaci贸n</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formCrearAsig" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="create">

                    <div class="form-grid">
                        <!-- Ficha -->
                        <div class="form-group">
                            <label for="FICHA_fich_id" class="form-label">
                                Ficha <span class="required">*</span>
                            </label>
                            <select
                                id="FICHA_fich_id"
                                name="FICHA_fich_id"
                                class="form-input <?php echo isset($errores['FICHA_fich_id']) ? 'input-error' : ''; ?>"
                                required
                            >
                                <option value="">Seleccione...</option>
                                <?php foreach ($fichas as $f): ?>
                                    <option
                                        value="<?php echo $f['fich_id']; ?>"
                                        <?php echo(isset($old['FICHA_fich_id']) && $old['FICHA_fich_id'] == $f['fich_id']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars($f['fich_id']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                            <div class="form-error <?php echo isset($errores['FICHA_fich_id']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['FICHA_fich_id'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <!-- Instructor -->
                        <div class="form-group">
                            <label for="INSTRUCTOR_inst_id" class="form-label">
                                Instructor <span class="required">*</span>
                            </label>
                            <select
                                id="INSTRUCTOR_inst_id"
                                name="INSTRUCTOR_inst_id"
                                class="form-input <?php echo isset($errores['INSTRUCTOR_inst_id']) ? 'input-error' : ''; ?>"
                                required
                            >
                                <option value="">Seleccione...</option>
                                <?php foreach ($instructores as $inst): ?>
                                    <option
                                        value="<?php echo $inst['inst_id']; ?>"
                                        <?php echo(isset($old['INSTRUCTOR_inst_id']) && $old['INSTRUCTOR_inst_id'] == $inst['inst_id']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars($inst['inst_nombre'] . ' ' . $inst['inst_apellidos']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                            <div class="form-error <?php echo isset($errores['INSTRUCTOR_inst_id']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['INSTRUCTOR_inst_id'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <!-- Ambiente -->
                        <div class="form-group">
                            <label for="AMBIENTE_id_ambiente" class="form-label">
                                Ambiente <span class="required">*</span>
                            </label>
                            <select
                                id="AMBIENTE_id_ambiente"
                                name="AMBIENTE_id_ambiente"
                                class="form-input <?php echo isset($errores['AMBIENTE_id_ambiente']) ? 'input-error' : ''; ?>"
                                required
                            >
                                <option value="">Seleccione...</option>
                                <?php foreach ($ambientes as $amb): ?>
                                    <option
                                        value="<?php echo $amb['id_ambiente']; ?>"
                                        <?php echo(isset($old['AMBIENTE_id_ambiente']) && $old['AMBIENTE_id_ambiente'] == $amb['id_ambiente']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars($amb['amb_nombre']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                            <div class="form-error <?php echo isset($errores['AMBIENTE_id_ambiente']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['AMBIENTE_id_ambiente'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <!-- Competencia -->
                        <div class="form-group">
                            <label for="COMPETENCIA_comp_id" class="form-label">
                                Competencia <span class="required">*</span>
                            </label>
                            <select
                                id="COMPETENCIA_comp_id"
                                name="COMPETENCIA_comp_id"
                                class="form-input <?php echo isset($errores['COMPETENCIA_comp_id']) ? 'input-error' : ''; ?>"
                                required
                            >
                                <option value="">Seleccione...</option>
                                <?php foreach ($competencias as $comp): ?>
                                    <option
                                        value="<?php echo $comp['comp_id']; ?>"
                                        <?php echo(isset($old['COMPETENCIA_comp_id']) && $old['COMPETENCIA_comp_id'] == $comp['comp_id']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars($comp['comp_nombre_corto']); ?>
                                    </option>
                                <?php
endforeach; ?>
                            </select>
                            <div class="form-error <?php echo isset($errores['COMPETENCIA_comp_id']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['COMPETENCIA_comp_id'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="form-group">
                            <label for="asig_fecha_ini" class="form-label">
                                Fecha Inicio <span class="required">*</span>
                            </label>
                            <input
                                type="date"
                                id="asig_fecha_ini"
                                name="asig_fecha_ini"
                                class="form-input <?php echo isset($errores['asig_fecha_ini']) ? 'input-error' : ''; ?>"
                                value="<?php echo htmlspecialchars($old['asig_fecha_ini'] ?? ''); ?>"
                                required
                            >
                            <div class="form-error <?php echo isset($errores['asig_fecha_ini']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['asig_fecha_ini'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="asig_fecha_fin" class="form-label">
                                Fecha Fin <span class="required">*</span>
                            </label>
                            <input
                                type="date"
                                id="asig_fecha_fin"
                                name="asig_fecha_fin"
                                class="form-input <?php echo isset($errores['asig_fecha_fin']) ? 'input-error' : ''; ?>"
                                value="<?php echo htmlspecialchars($old['asig_fecha_fin'] ?? ''); ?>"
                                required
                            >
                             <div class="form-error <?php echo isset($errores['asig_fecha_fin']) ? 'visible' : ''; ?>">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['asig_fecha_fin'] ?? 'Requerido.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Guardar Asignaci贸n
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
