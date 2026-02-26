<?php
/**
 * Vista: Detalle de Asignación con Horarios
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/AsignacionController.php';

$controller = new AsignacionController();

// Obtener ID de la asignación
$asig_id = $_GET['id'] ?? null;

if (!$asig_id) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('ID de asignación no proporcionado'));
    exit;
}

// Obtener la asignación
$asignacion = $controller->getAsignacionById($asig_id);

if (!$asignacion) {
    header('Location: ' . addRolParam('index.php', $rol) . '&error=' . urlencode('Asignación no encontrada'));
    exit;
}

// Obtener detalles de horarios
$asignacion['detalles'] = $controller->getDetallesByAsignacion($asig_id);

$rol = $rol ?? 'coordinador';

$title = 'Detalle de Asignación';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Asignaciones', 'url' => addRolParam('index.php', $rol)],
    ['label' => 'Detalle'],
];

// Incluir el header según el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

<style>
.horarios-section {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 2px solid #e5e7eb;
}

.horarios-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.horario-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.horario-icon {
    width: 48px;
    height: 48px;
    background: #39A900;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.horario-info {
    flex: 1;
}

.horario-time {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.horario-date {
    font-size: 14px;
    color: #6b7280;
}

.no-horarios {
    text-align: center;
    padding: 32px;
    color: #6b7280;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px dashed #d1d5db;
}
</style>

<div class="page-header">
    <h1 class="page-title">Detalle de Asignación</h1>
    <?php if ($rol === 'coordinador'): ?>
        <a href="<?php echo addRolParam('editar.php?id=' . $asignacion['asig_id'], $rol); ?>" class="btn btn-primary">
            <i data-lucide="pencil-line"></i>
            Editar Asignación
        </a>
    <?php endif; ?>
</div>

<div class="detail-card">
    <div class="detail-card-body">
        <div class="detail-row">
            <div class="detail-label">ID Asignación</div>
            <div class="detail-value"><?php echo htmlspecialchars($asignacion['asig_id']); ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Ficha</div>
            <div class="detail-value"><?php echo htmlspecialchars($asignacion['ficha_numero'] ?? $asignacion['fich_id'] ?? 'N/A'); ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Instructor</div>
            <div class="detail-value"><?php echo htmlspecialchars($asignacion['instructor_nombre'] ?? $asignacion['inst_nombre'] ?? 'N/A'); ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Ambiente</div>
            <div class="detail-value"><?php echo htmlspecialchars($asignacion['amb_nombre'] ?? 'N/A'); ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Competencia</div>
            <div class="detail-value"><?php echo htmlspecialchars($asignacion['comp_nombre_corto'] ?? 'N/A'); ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Fecha Inicio</div>
            <div class="detail-value"><?php echo date('d/m/Y', strtotime($asignacion['asig_fecha_ini'])); ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Fecha Fin</div>
            <div class="detail-value"><?php echo date('d/m/Y', strtotime($asignacion['asig_fecha_fin'])); ?></div>
        </div>

        <!-- Sección de Horarios -->
        <div class="horarios-section">
            <h3 class="horarios-title">
                <i data-lucide="clock"></i>
                Horarios de Clase
            </h3>
            
            <?php if (!empty($asignacion['detalles'])): ?>
                <?php foreach ($asignacion['detalles'] as $detalle): ?>
                    <div class="horario-card">
                        <div class="horario-icon">
                            <i data-lucide="calendar-clock"></i>
                        </div>
                        <div class="horario-info">
                            <div class="horario-time">
                                <?php echo date('H:i', strtotime($detalle['detasig_hora_ini'])); ?> - 
                                <?php echo date('H:i', strtotime($detalle['detasig_hora_fin'])); ?>
                            </div>
                            <div class="horario-date">
                                <?php echo date('l, d \d\e F \d\e Y', strtotime($detalle['detasig_hora_ini'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-horarios">
                    <i data-lucide="calendar-x" style="width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.5;"></i>
                    <p>No hay horarios específicos registrados para esta asignación.</p>
                    <?php if ($rol === 'coordinador'): ?>
                        <p style="margin-top: 8px;">
                            <a href="<?php echo addRolParam('editar.php?id=' . $asignacion['asig_id'], $rol); ?>" style="color: #39A900; text-decoration: underline;">
                                Agregar horarios
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="detail-card-footer">
        <a href="<?php echo addRolParam('index.php', $rol); ?>" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i>
            Volver al Calendario
        </a>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
