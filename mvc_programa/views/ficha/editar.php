<?php
/**
 * Vista: Editar Ficha (editar.php)
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
$ficha = $ficha ?? ['fich_id' => '228106-1', 'PROGRAMA_prog_id' => '228106', 'INSTRUCTOR_inst_id_lider' => 1, 'fich_jornada' => 'Diurna'];
$programas = $programas ?? [
    ['prog_codigo' => '228106', 'prog_denominacion' => 'Análisis y Desarrollo de Software'],
];
$instructores = $instructores ?? [
    ['inst_id' => 1, 'inst_nombre' => 'Juan', 'inst_apellidos' => 'Pérez'],
];
$errores = $errores ?? [];
// --- Fin datos de prueba ---

$title = 'Editar Ficha';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Fichas', 'url' => addRolParam('index.php', $rol)],
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
            <h1 class="page-title">Editar Ficha</h1>
        </div>

        <div class="form-container">
            <div class="form-card">
                <form id="formEditarFicha" method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="update">
                    <!-- Fich_id es PK, pero en FichaModel update usa fich_id en WHERE y SET? Asumiremos PK inmutable o hidden id old -->
                    <input type="hidden" name="fich_id_pk" value="<?php echo htmlspecialchars($ficha['fich_id']); ?>">

                    <div class="form-group">
                        <label for="fich_id" class="form-label">
                             Número de Ficha (No editable)
                        </label>
                        <input
                            type="text"
                            id="fich_id"
                            name="fich_id"
                            class="form-input"
                            value="<?php echo htmlspecialchars($ficha['fich_id']); ?>"
                            readonly
                            style="background-color: var(--gray-100); color: var(--gray-500);"
                        >
                    </div>

                    <div class="form-group">
                        <label for="fich_jornada" class="form-label">
                            Jornada <span class="required">*</span>
                        </label>
                        <select
                            id="fich_jornada"
                            name="fich_jornada"
                            class="form-input <?php echo isset($errores['fich_jornada']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <option value="Diurna" <?php echo($ficha['fich_jornada'] == 'Diurna') ? 'selected' : ''; ?>>Diurna</option>
                            <option value="Nocturna" <?php echo($ficha['fich_jornada'] == 'Nocturna') ? 'selected' : ''; ?>>Nocturna</option>
                            <option value="Mixta" <?php echo($ficha['fich_jornada'] == 'Mixta') ? 'selected' : ''; ?>>Mixta</option>
                        </select>
                        <div class="form-error <?php echo isset($errores['fich_jornada']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['fich_jornada'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="PROGRAMA_prog_id" class="form-label">
                            Programa de Formación <span class="required">*</span>
                        </label>
                        <select
                            id="PROGRAMA_prog_id"
                            name="PROGRAMA_prog_id"
                            class="form-input <?php echo isset($errores['PROGRAMA_prog_id']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($programas as $p): ?>
                                <option
                                    value="<?php echo $p['prog_codigo']; ?>"
                                    <?php echo($ficha['PROGRAMA_prog_id'] == $p['prog_codigo']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($p['prog_denominacion'] . ' (' . $p['prog_codigo'] . ')'); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['PROGRAMA_prog_id']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['PROGRAMA_prog_id'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="INSTRUCTOR_inst_id_lider" class="form-label">
                            Instructor Líder <span class="required">*</span>
                        </label>
                        <select
                            id="INSTRUCTOR_inst_id_lider"
                            name="INSTRUCTOR_inst_id_lider"
                            class="form-input <?php echo isset($errores['INSTRUCTOR_inst_id_lider']) ? 'input-error' : ''; ?>"
                            required
                        >
                            <option value="">Seleccione...</option>
                            <?php foreach ($instructores as $inst): ?>
                                <option
                                    value="<?php echo $inst['inst_id']; ?>"
                                    <?php echo($ficha['INSTRUCTOR_inst_id_lider'] == $inst['inst_id']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($inst['inst_nombre'] . ' ' . $inst['inst_apellidos']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                         <div class="form-error <?php echo isset($errores['INSTRUCTOR_inst_id_lider']) ? 'visible' : ''; ?>">
                            <i data-lucide="alert-circle"></i>
                            <span><?php echo htmlspecialchars($errores['INSTRUCTOR_inst_id_lider'] ?? 'Requerido.'); ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Actualizar Ficha
                        </button>
                        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
