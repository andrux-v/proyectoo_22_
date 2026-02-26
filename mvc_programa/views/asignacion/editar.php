<?php
/**
 * Vista: Editar Asignación con Horarios
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Redirigir si es instructor
if ($rol === 'instructor') {
    header('Location: ' . addRolParam('index.php', $rol));
    exit;
}

// Incluir el controlador
require_once __DIR__ . '/../../controller/AsignacionController.php';

$controller = new AsignacionController();

// Obtener ID de la asignación
$asig_id = $_GET['id'] ?? null;

if (!$asig_id) {
    header('Location: index.php?rol=' . $rol . '&error=' . urlencode('ID de asignación no proporcionado'));
    exit;
}

// Obtener la asignación
$asignacion = $controller->getAsignacionById($asig_id);

if (!$asignacion) {
    header('Location: index.php?rol=' . $rol . '&error=' . urlencode('Asignación no encontrada'));
    exit;
}

// Obtener detalles de horarios
$detalles = $controller->getDetallesByAsignacion($asig_id);

$errores = [];
$old = [];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'asig_id' => $asig_id,
        'instructor_inst_id' => $_POST['INSTRUCTOR_inst_id'] ?? '',
        'ficha_fich_id' => $_POST['FICHA_fich_id'] ?? '',
        'ambiente_amb_id' => $_POST['AMBIENTE_id_ambiente'] ?? '',
        'competencia_comp_id' => $_POST['COMPETENCIA_comp_id'] ?? '',
        'asig_fecha_ini' => $_POST['asig_fecha_ini'] ?? '',
        'asig_fecha_fin' => $_POST['asig_fecha_fin'] ?? '',
        'detalles' => []
    ];

    // Procesar detalles de horarios
    if (isset($_POST['detalle_hora_ini']) && is_array($_POST['detalle_hora_ini'])) {
        foreach ($_POST['detalle_hora_ini'] as $index => $hora_ini) {
            if (!empty($hora_ini) && !empty($_POST['detalle_hora_fin'][$index])) {
                $data['detalles'][] = [
                    'hora_ini' => $hora_ini,
                    'hora_fin' => $_POST['detalle_hora_fin'][$index]
                ];
            }
        }
    }

    $resultado = $controller->update($data);

    if ($resultado['success']) {
        $redirectUrl = 'index.php?rol=' . $rol . '&mensaje=' . urlencode($resultado['mensaje']);
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        $errores = $resultado['errores'];
        $old = $data;
    }
}

// Obtener datos para los selects
$instructores = $controller->getInstructores();
$fichas = $controller->getFichas();
$ambientes = $controller->getAmbientes();
$competencias = $controller->getCompetencias();

$rol = $rol ?? 'coordinador';

$title = 'Editar Asignación';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Asignaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Editar'],
];

if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

<style>
.horarios-section {
    margin-top: 32px;
    padding-top: 32px;
    border-top: 2px solid #e5e7eb;
}

.horarios-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.horarios-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.horario-item {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 16px;
    align-items: end;
    margin-bottom: 16px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.btn-add-horario {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #39A900;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-add-horario:hover {
    background: #007832;
}

.btn-remove-horario {
    padding: 8px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.btn-remove-horario:hover {
    background: #dc2626;
}

.horarios-empty {
    text-align: center;
    padding: 32px;
    color: #6b7280;
    font-size: 14px;
}
</style>

<div class="page-header">
    <h1 class="page-title">Editar Asignación</h1>
</div>

<div class="form-container">
    <div class="form-card">
        <form id="formEditarAsig" method="POST" action="" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="FICHA_fich_id" class="form-label">
                        Ficha <span class="required">*</span>
                    </label>
                    <select id="FICHA_fich_id" name="FICHA_fich_id" class="form-input <?php echo isset($errores['ficha_fich_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($fichas as $f): ?>
                            <option value="<?php echo $f['fich_id']; ?>" <?php echo($asignacion['ficha_fich_id'] == $f['fich_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($f['fich_id']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['ficha_fich_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['ficha_fich_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="INSTRUCTOR_inst_id" class="form-label">
                        Instructor <span class="required">*</span>
                    </label>
                    <select id="INSTRUCTOR_inst_id" name="INSTRUCTOR_inst_id" class="form-input <?php echo isset($errores['instructor_inst_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($instructores as $inst): ?>
                            <option value="<?php echo $inst['inst_id']; ?>" <?php echo($asignacion['instructor_inst_id'] == $inst['inst_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($inst['nombre_completo'] ?? ($inst['inst_nombres'] . ' ' . $inst['inst_apellidos'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['instructor_inst_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['instructor_inst_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="AMBIENTE_id_ambiente" class="form-label">
                        Ambiente <span class="required">*</span>
                    </label>
                    <select id="AMBIENTE_id_ambiente" name="AMBIENTE_id_ambiente" class="form-input <?php echo isset($errores['ambiente_amb_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($ambientes as $amb): ?>
                            <option value="<?php echo $amb['amb_id']; ?>" <?php echo($asignacion['ambiente_amb_id'] == $amb['amb_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($amb['amb_nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['ambiente_amb_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['ambiente_amb_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="COMPETENCIA_comp_id" class="form-label">
                        Competencia <span class="required">*</span>
                    </label>
                    <select id="COMPETENCIA_comp_id" name="COMPETENCIA_comp_id" class="form-input <?php echo isset($errores['competencia_comp_id']) ? 'input-error' : ''; ?>" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($competencias as $comp): ?>
                            <option value="<?php echo $comp['comp_id']; ?>" <?php echo($asignacion['competencia_comp_id'] == $comp['comp_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($comp['comp_nombre_corto']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error <?php echo isset($errores['competencia_comp_id']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['competencia_comp_id'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="asig_fecha_ini" class="form-label">
                        Fecha Inicio <span class="required">*</span>
                    </label>
                    <input type="date" id="asig_fecha_ini" name="asig_fecha_ini" class="form-input <?php echo isset($errores['asig_fecha_ini']) ? 'input-error' : ''; ?>" value="<?php echo htmlspecialchars($asignacion['asig_fecha_ini']); ?>" required>
                    <div class="form-error <?php echo isset($errores['asig_fecha_ini']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['asig_fecha_ini'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="asig_fecha_fin" class="form-label">
                        Fecha Fin <span class="required">*</span>
                    </label>
                    <input type="date" id="asig_fecha_fin" name="asig_fecha_fin" class="form-input <?php echo isset($errores['asig_fecha_fin']) ? 'input-error' : ''; ?>" value="<?php echo htmlspecialchars($asignacion['asig_fecha_fin']); ?>" required>
                    <div class="form-error <?php echo isset($errores['asig_fecha_fin']) ? 'visible' : ''; ?>">
                        <i data-lucide="alert-circle"></i>
                        <span><?php echo htmlspecialchars($errores['asig_fecha_fin'] ?? 'Requerido.'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Sección de Horarios -->
            <div class="horarios-section">
                <div class="horarios-header">
                    <h3 class="horarios-title">
                        <i data-lucide="clock"></i>
                        Horarios de Clase
                    </h3>
                    <button type="button" class="btn-add-horario" onclick="addHorario()">
                        <i data-lucide="plus"></i>
                        Agregar Horario
                    </button>
                </div>

                <div id="horariosContainer">
                    <?php if (!empty($detalles)): ?>
                        <?php foreach ($detalles as $index => $detalle): ?>
                            <div class="horario-item" id="horario-<?php echo $index; ?>">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Hora Inicio</label>
                                    <input type="datetime-local" name="detalle_hora_ini[]" class="form-input" value="<?php echo date('Y-m-d\TH:i', strtotime($detalle['detasig_hora_ini'])); ?>" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Hora Fin</label>
                                    <input type="datetime-local" name="detalle_hora_fin[]" class="form-input" value="<?php echo date('Y-m-d\TH:i', strtotime($detalle['detasig_hora_fin'])); ?>" required>
                                </div>
                                <button type="button" class="btn-remove-horario" onclick="removeHorario(<?php echo $index; ?>)" title="Eliminar horario">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="horarios-empty">
                            No hay horarios agregados. Haz clic en "Agregar Horario" para añadir uno.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i>
                    Actualizar Asignación
                </button>
                <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let horarioCount = <?php echo count($detalles); ?>;

function addHorario() {
    const container = document.getElementById('horariosContainer');
    const emptyMsg = container.querySelector('.horarios-empty');
    if (emptyMsg) emptyMsg.remove();
    
    const horarioDiv = document.createElement('div');
    horarioDiv.className = 'horario-item';
    horarioDiv.id = `horario-${horarioCount}`;
    horarioDiv.innerHTML = `
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Hora Inicio</label>
            <input type="datetime-local" name="detalle_hora_ini[]" class="form-input" required>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Hora Fin</label>
            <input type="datetime-local" name="detalle_hora_fin[]" class="form-input" required>
        </div>
        <button type="button" class="btn-remove-horario" onclick="removeHorario(${horarioCount})" title="Eliminar horario">
            <i data-lucide="trash-2"></i>
        </button>
    `;
    
    container.appendChild(horarioDiv);
    horarioCount++;
    lucide.createIcons();
}

function removeHorario(id) {
    const horario = document.getElementById(`horario-${id}`);
    if (horario) horario.remove();
    
    const container = document.getElementById('horariosContainer');
    if (container.children.length === 0) {
        container.innerHTML = '<div class="horarios-empty">No hay horarios agregados. Haz clic en "Agregar Horario" para añadir uno.</div>';
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
