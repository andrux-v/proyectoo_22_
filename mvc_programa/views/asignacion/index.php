<?php
/**
 * Vista: Calendario de Asignaciones
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/AsignacionController.php';

$controller = new AsignacionController();

// Procesar eliminación (solo coordinador)
if ($rol === 'coordinador' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $resultado = $controller->delete($_POST['asig_id']);
    
    if ($resultado['success']) {
        $mensaje = $resultado['mensaje'];
    } else {
        $error = $resultado['error'];
    }
}

// Obtener asignaciones con detalles de horarios
if ($rol === 'instructor') {
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Verificar que el instructor esté logueado
    if (!isset($_SESSION['instructor_id'])) {
        header('Location: /proyectoo_22_/mvc_programa/views/instructor/login.php');
        exit;
    }
    
    // Obtener solo las asignaciones del instructor logueado
    $asignaciones = $controller->getAsignacionesByInstructor($_SESSION['instructor_id']);
} else {
    // Coordinador ve todas las asignaciones
    $asignaciones = $controller->getAllAsignaciones();
}

// Agregar detalles de horarios a cada asignación
foreach ($asignaciones as &$asig) {
    $asig['detalles'] = $controller->getDetallesByAsignacion($asig['asig_id']);
}

$mensaje = $_GET['mensaje'] ?? ($mensaje ?? null);
$error = $_GET['error'] ?? ($error ?? null);

$title = 'Calendario de Asignaciones';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Asignaciones'],
];

// Incluir el header según el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

<style>
.calendar-container {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e5e7eb;
}

.calendar-nav {
    display: flex;
    gap: 12px;
    align-items: center;
}

.calendar-nav button {
    padding: 8px 16px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.calendar-nav button:hover {
    background: #e5e7eb;
}

.calendar-month {
    font-size: 20px;
    font-weight: 600;
    color: #111827;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e5e7eb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.calendar-day-header {
    background: #f9fafb;
    padding: 12px;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
    color: #6b7280;
}

.calendar-day {
    background: white;
    min-height: 120px;
    padding: 8px;
    position: relative;
}

.calendar-day.other-month {
    background: #f9fafb;
    opacity: 0.5;
}

.calendar-day.today {
    background: #fef3c7;
}

.calendar-day.sunday {
    background: #fee2e2;
    opacity: 0.7;
}

.calendar-day.sunday .day-number {
    color: #dc2626;
    font-weight: 700;
}

.day-number {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
}

.calendar-day.other-month .day-number {
    color: #9ca3af;
}

.calendar-event {
    background: #39A900;
    color: white;
    padding: 4px 8px;
    margin: 2px 0;
    border-radius: 4px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 4px;
}

.calendar-event:hover {
    background: #007832;
    transform: translateY(-1px);
}

.calendar-event.event-start {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.calendar-event.event-end {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.event-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.event-indicator.start {
    background: #10b981;
    box-shadow: 0 0 4px rgba(16, 185, 129, 0.6);
}

.event-indicator.end {
    background: #ef4444;
    box-shadow: 0 0 4px rgba(239, 68, 68, 0.6);
}

.event-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.event-modal.active {
    display: flex;
}

.event-modal-content {
    background: white;
    border-radius: 12px;
    padding: 24px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.event-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e5e7eb;
}

.event-modal-title {
    font-size: 20px;
    font-weight: 600;
    color: #111827;
}

.event-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
}

.event-modal-close:hover {
    background: #f3f4f6;
}

.event-detail-row {
    margin-bottom: 16px;
}

.event-detail-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.event-detail-value {
    font-size: 16px;
    color: #111827;
}

.view-toggle {
    display: flex;
    gap: 8px;
    background: #f3f4f6;
    padding: 4px;
    border-radius: 8px;
}

.view-toggle button {
    padding: 8px 16px;
    background: transparent;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.2s;
}

.view-toggle button.active {
    background: white;
    color: #39A900;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.list-view {
    display: none;
}

.list-view.active {
    display: block;
}

.calendar-view.hidden {
    display: none;
}

.asignacion-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    transition: all 0.2s;
}

.asignacion-card:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.asignacion-card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 12px;
}

.asignacion-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.asignacion-badge {
    background: #39A900;
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.asignacion-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #6b7280;
}

.info-item i {
    width: 16px;
    height: 16px;
}

.asignacion-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
}
</style>

<div class="page-header">
    <h1 class="page-title">Calendario de Asignaciones</h1>
    <div style="display: flex; gap: 12px; align-items: center;">
        <div class="view-toggle">
            <button onclick="switchView('calendar')" class="active" id="calendarViewBtn">
                <i data-lucide="calendar"></i> Calendario
            </button>
            <button onclick="switchView('list')" id="listViewBtn">
                <i data-lucide="list"></i> Lista
            </button>
        </div>
        <?php if ($rol === 'coordinador'): ?>
            <a href="<?php echo addRolParam('crear.php', $rol); ?>" class="btn btn-primary">
                <i data-lucide="plus"></i>
                Nueva Asignación
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Buscador y Filtros -->
<div class="calendar-container" style="margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px;">
            <input 
                type="text" 
                id="searchInput" 
                class="form-input" 
                placeholder="Buscar por instructor, ficha, ambiente o competencia..."
                onkeyup="filterAsignaciones()"
                style="margin: 0;"
            >
        </div>
        <div style="min-width: 200px;">
            <select id="filterInstructor" class="form-input" onchange="filterAsignaciones()" style="margin: 0;">
                <option value="">Todos los instructores</option>
                <?php
                $instructores = [];
                foreach ($asignaciones as $asig) {
                    $nombre = $asig['inst_nombre'] ?? $asig['instructor_nombre'] ?? '';
                    if ($nombre && !in_array($nombre, $instructores)) {
                        $instructores[] = $nombre;
                    }
                }
                sort($instructores);
                foreach ($instructores as $instructor):
                ?>
                    <option value="<?php echo htmlspecialchars($instructor); ?>">
                        <?php echo htmlspecialchars($instructor); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="min-width: 180px;">
            <select id="filterFicha" class="form-input" onchange="filterAsignaciones()" style="margin: 0;">
                <option value="">Todas las fichas</option>
                <?php
                $fichas = [];
                foreach ($asignaciones as $asig) {
                    $ficha = $asig['fich_id'] ?? $asig['ficha_numero'] ?? '';
                    if ($ficha && !in_array($ficha, $fichas)) {
                        $fichas[] = $ficha;
                    }
                }
                sort($fichas);
                foreach ($fichas as $ficha):
                ?>
                    <option value="<?php echo htmlspecialchars($ficha); ?>">
                        Ficha <?php echo htmlspecialchars($ficha); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button onclick="clearFilters()" class="btn btn-secondary" style="white-space: nowrap;">
            <i data-lucide="x"></i>
            Limpiar
        </button>
    </div>
    <div id="filterResults" style="margin-top: 12px; font-size: 14px; color: #6b7280;">
        Mostrando <span id="resultCount"><?php echo count($asignaciones); ?></span> de <?php echo count($asignaciones); ?> asignaciones
    </div>
</div>

<!-- Alerts -->
<?php if ($mensaje): ?>
    <div class="alert alert-success">
        <i data-lucide="check-circle-2"></i>
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error">
        <i data-lucide="alert-circle"></i>
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<!-- Vista de Calendario -->
<div class="calendar-view" id="calendarView">
    <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-nav">
                <button onclick="previousMonth()">
                    <i data-lucide="chevron-left"></i> Anterior
                </button>
                <span class="calendar-month" id="currentMonth"></span>
                <button onclick="nextMonth()">
                    Siguiente <i data-lucide="chevron-right"></i>
                </button>
            </div>
            <button onclick="goToToday()" class="btn btn-secondary">Hoy</button>
        </div>

        <div class="calendar-grid" id="calendarGrid">
            <!-- El calendario se genera con JavaScript -->
        </div>
    </div>
</div>

<!-- Vista de Lista -->
<div class="list-view" id="listView">
    <div class="calendar-container">
        <div id="listContainer">
            <?php if (!empty($asignaciones)): ?>
                <?php foreach ($asignaciones as $asig): ?>
                    <div class="asignacion-card" 
                         data-instructor="<?php echo htmlspecialchars($asig['inst_nombre'] ?? $asig['instructor_nombre'] ?? ''); ?>"
                         data-ficha="<?php echo htmlspecialchars($asig['fich_id'] ?? $asig['ficha_numero'] ?? ''); ?>"
                         data-ambiente="<?php echo htmlspecialchars($asig['amb_nombre'] ?? ''); ?>"
                         data-competencia="<?php echo htmlspecialchars($asig['comp_nombre_corto'] ?? ''); ?>">
                        <div class="asignacion-card-header">
                            <div class="asignacion-title">
                                <?php echo htmlspecialchars($asig['amb_nombre'] ?? 'Sin ambiente'); ?>
                            </div>
                            <div class="asignacion-badge">
                                Ficha <?php echo htmlspecialchars($asig['fich_id'] ?? $asig['ficha_numero'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="asignacion-info">
                            <div class="info-item">
                                <i data-lucide="user"></i>
                                <?php echo htmlspecialchars($asig['inst_nombre'] ?? $asig['instructor_nombre'] ?? 'Sin instructor'); ?>
                            </div>
                            <div class="info-item">
                                <i data-lucide="book-open"></i>
                                <?php echo htmlspecialchars($asig['comp_nombre_corto'] ?? 'Sin competencia'); ?>
                            </div>
                            <div class="info-item">
                                <i data-lucide="calendar"></i>
                                <?php echo date('d/m/Y', strtotime($asig['asig_fecha_ini'])); ?>
                            </div>
                            <div class="info-item">
                                <i data-lucide="calendar"></i>
                                <?php echo date('d/m/Y', strtotime($asig['asig_fecha_fin'])); ?>
                            </div>
                        </div>
                        <?php if (!empty($asig['detalles'])): ?>
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                            <div style="font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">HORARIOS</div>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                <?php foreach ($asig['detalles'] as $detalle): ?>
                                <div style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f0fdf4; border: 1px solid #39A900; border-radius: 6px; font-size: 13px; color: #007832;">
                                    <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
                                    <?php echo htmlspecialchars($detalle['detasig_hora_ini']); ?> - <?php echo htmlspecialchars($detalle['detasig_hora_fin']); ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="asignacion-actions">
                            <a href="<?php echo addRolParam('ver.php?id=' . $asig['asig_id'], $rol); ?>" class="action-btn view-btn" title="Ver detalle">
                                <i data-lucide="eye"></i>
                            </a>
                            <?php if ($rol === 'coordinador'): ?>
                                <a href="<?php echo addRolParam('editar.php?id=' . $asig['asig_id'], $rol); ?>" class="action-btn edit-btn" title="Editar">
                                    <i data-lucide="pencil-line"></i>
                                </a>
                                <button type="button" class="action-btn delete-btn" title="Eliminar" onclick="confirmDelete(<?php echo $asig['asig_id']; ?>)">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="table-empty">
                    <div class="table-empty-icon">
                        <i data-lucide="calendar-days"></i>
                    </div>
                    <div class="table-empty-title">No hay asignaciones registradas</div>
                </div>
            <?php endif; ?>
        </div>
        <div id="noResultsMessage" style="display: none;">
            <div class="table-empty">
                <div class="table-empty-icon">
                    <i data-lucide="search-x"></i>
                </div>
                <div class="table-empty-title">No se encontraron asignaciones</div>
                <div class="table-empty-text">Intenta con otros criterios de búsqueda</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalle de Evento -->
<div class="event-modal" id="eventModal">
    <div class="event-modal-content">
        <div class="event-modal-header">
            <h3 class="event-modal-title" id="modalTitle">Detalle de Asignación</h3>
            <button class="event-modal-close" onclick="closeEventModal()">×</button>
        </div>
        <div id="modalBody">
            <!-- El contenido se carga dinámicamente -->
        </div>
    </div>
</div>

<script>
// Datos de asignaciones desde PHP
const asignaciones = <?php echo json_encode($asignaciones); ?>;
let filteredAsignaciones = [...asignaciones];

let currentDate = new Date();
const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    
    const firstDayOfWeek = firstDay.getDay();
    const lastDate = lastDay.getDate();
    const prevLastDate = prevLastDay.getDate();
    
    let calendarHTML = '';
    
    // Headers de días
    dayNames.forEach(day => {
        calendarHTML += `<div class="calendar-day-header">${day}</div>`;
    });
    
    // Días del mes anterior
    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
        calendarHTML += `<div class="calendar-day other-month">
            <div class="day-number">${prevLastDate - i}</div>
        </div>`;
    }
    
    // Días del mes actual
    const today = new Date();
    for (let day = 1; day <= lastDate; day++) {
        const currentDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const currentDay = new Date(year, month, day);
        const isToday = today.getDate() === day && today.getMonth() === month && today.getFullYear() === year;
        const isSunday = currentDay.getDay() === 0;
        
        // Filtrar asignaciones para este día (usar filteredAsignaciones en lugar de asignaciones)
        const dayEvents = filteredAsignaciones.filter(asig => {
            const fechaIni = new Date(asig.asig_fecha_ini);
            const fechaFin = new Date(asig.asig_fecha_fin);
            const currentDayDate = new Date(year, month, day);
            
            // Normalizar fechas para comparación (solo fecha, sin hora)
            fechaIni.setHours(0, 0, 0, 0);
            fechaFin.setHours(0, 0, 0, 0);
            currentDayDate.setHours(0, 0, 0, 0);
            
            return currentDayDate >= fechaIni && currentDayDate <= fechaFin;
        });
        
        let sundayClass = isSunday ? ' sunday' : '';
        calendarHTML += `<div class="calendar-day ${isToday ? 'today' : ''}${sundayClass}">
            <div class="day-number">${day}</div>`;
        
        // Mostrar eventos solo si no es domingo
        if (!isSunday) {
            dayEvents.forEach(event => {
                const fechaIni = new Date(event.asig_fecha_ini);
                const fechaFin = new Date(event.asig_fecha_fin);
                const currentDayDate = new Date(year, month, day);
                
                fechaIni.setHours(0, 0, 0, 0);
                fechaFin.setHours(0, 0, 0, 0);
                currentDayDate.setHours(0, 0, 0, 0);
                
                const isStart = currentDayDate.getTime() === fechaIni.getTime();
                const isEnd = currentDayDate.getTime() === fechaFin.getTime();
                
                let eventClass = '';
                let indicator = '';
                let eventText = event.amb_nombre || 'Sin ambiente';
                
                if (isStart) {
                    eventClass = 'event-start';
                    indicator = '<span class="event-indicator start"></span>';
                    eventText = '🟢 ' + eventText;
                } else if (isEnd) {
                    eventClass = 'event-end';
                    indicator = '<span class="event-indicator end"></span>';
                    eventText = eventText + ' 🔴';
                }
                
                calendarHTML += `<div class="calendar-event ${eventClass}" onclick="showEventDetail(${event.asig_id})">
                    ${indicator}${eventText}
                </div>`;
            });
        }
        
        calendarHTML += `</div>`;
    }
    
    // Días del siguiente mes
    const remainingDays = 42 - (firstDayOfWeek + lastDate);
    for (let i = 1; i <= remainingDays; i++) {
        calendarHTML += `<div class="calendar-day other-month">
            <div class="day-number">${i}</div>
        </div>`;
    }
    
    document.getElementById('calendarGrid').innerHTML = calendarHTML;
}

// Función para filtrar asignaciones
function filterAsignaciones() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const filterInstructor = document.getElementById('filterInstructor').value;
    const filterFicha = document.getElementById('filterFicha').value;
    
    // Filtrar asignaciones
    filteredAsignaciones = asignaciones.filter(asig => {
        const instructor = (asig.inst_nombre || asig.instructor_nombre || '').toLowerCase();
        const ficha = (asig.fich_id || asig.ficha_numero || '').toString();
        const ambiente = (asig.amb_nombre || '').toLowerCase();
        const competencia = (asig.comp_nombre_corto || '').toLowerCase();
        
        // Filtro de texto
        const matchesSearch = !searchText || 
            instructor.includes(searchText) ||
            ficha.includes(searchText) ||
            ambiente.includes(searchText) ||
            competencia.includes(searchText);
        
        // Filtro de instructor
        const matchesInstructor = !filterInstructor || 
            (asig.inst_nombre || asig.instructor_nombre || '') === filterInstructor;
        
        // Filtro de ficha
        const matchesFicha = !filterFicha || 
            (asig.fich_id || asig.ficha_numero || '').toString() === filterFicha;
        
        return matchesSearch && matchesInstructor && matchesFicha;
    });
    
    // Actualizar contador
    document.getElementById('resultCount').textContent = filteredAsignaciones.length;
    
    // Actualizar vista de calendario
    renderCalendar();
    
    // Actualizar vista de lista
    updateListView();
}

// Función para actualizar la vista de lista
function updateListView() {
    const cards = document.querySelectorAll('.asignacion-card');
    const noResults = document.getElementById('noResultsMessage');
    let visibleCount = 0;
    
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const filterInstructor = document.getElementById('filterInstructor').value;
    const filterFicha = document.getElementById('filterFicha').value;
    
    cards.forEach(card => {
        const instructor = card.dataset.instructor.toLowerCase();
        const ficha = card.dataset.ficha;
        const ambiente = card.dataset.ambiente.toLowerCase();
        const competencia = card.dataset.competencia.toLowerCase();
        
        const matchesSearch = !searchText || 
            instructor.includes(searchText) ||
            ficha.includes(searchText) ||
            ambiente.includes(searchText) ||
            competencia.includes(searchText);
        
        const matchesInstructor = !filterInstructor || card.dataset.instructor === filterInstructor;
        const matchesFicha = !filterFicha || card.dataset.ficha === filterFicha;
        
        if (matchesSearch && matchesInstructor && matchesFicha) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Mostrar mensaje si no hay resultados
    if (visibleCount === 0 && cards.length > 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
    
    lucide.createIcons();
}

// Función para limpiar filtros
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterInstructor').value = '';
    document.getElementById('filterFicha').value = '';
    filterAsignaciones();
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

function goToToday() {
    currentDate = new Date();
    renderCalendar();
}

function showEventDetail(asigId) {
    const asignacion = asignaciones.find(a => a.asig_id == asigId);
    if (!asignacion) return;
    
    // Construir HTML de horarios si existen
    let horariosHTML = '';
    if (asignacion.detalles && asignacion.detalles.length > 0) {
        horariosHTML = `
        <div class="event-detail-row">
            <div class="event-detail-label">Horarios</div>
            <div class="event-detail-value">
                ${asignacion.detalles.map(detalle => `
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f3f4f6; border-radius: 6px; margin-bottom: 6px;">
                        <i data-lucide="clock" style="width: 16px; height: 16px; color: #39A900;"></i>
                        <span>${detalle.detasig_hora_ini} - ${detalle.detasig_hora_fin}</span>
                    </div>
                `).join('')}
            </div>
        </div>`;
    }
    
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="event-detail-row">
            <div class="event-detail-label">Ambiente</div>
            <div class="event-detail-value">${asignacion.amb_nombre || 'Sin ambiente'}</div>
        </div>
        <div class="event-detail-row">
            <div class="event-detail-label">Instructor</div>
            <div class="event-detail-value">${asignacion.inst_nombre || asignacion.instructor_nombre || 'Sin instructor'}</div>
        </div>
        <div class="event-detail-row">
            <div class="event-detail-label">Ficha</div>
            <div class="event-detail-value">${asignacion.fich_id || asignacion.ficha_numero || 'N/A'}</div>
        </div>
        <div class="event-detail-row">
            <div class="event-detail-label">Competencia</div>
            <div class="event-detail-value">${asignacion.comp_nombre_corto || 'Sin competencia'}</div>
        </div>
        <div class="event-detail-row">
            <div class="event-detail-label">Fecha Inicio</div>
            <div class="event-detail-value">${new Date(asignacion.asig_fecha_ini).toLocaleDateString('es-ES')}</div>
        </div>
        <div class="event-detail-row">
            <div class="event-detail-label">Fecha Fin</div>
            <div class="event-detail-value">${new Date(asignacion.asig_fecha_fin).toLocaleDateString('es-ES')}</div>
        </div>
        ${horariosHTML}
        <div style="margin-top: 20px; display: flex; gap: 8px;">
            <a href="ver.php?id=${asigId}&rol=<?php echo $rol; ?>" class="btn btn-secondary" style="flex: 1;">
                <i data-lucide="eye"></i> Ver Detalle
            </a>
            <?php if ($rol === 'coordinador'): ?>
            <a href="editar.php?id=${asigId}&rol=<?php echo $rol; ?>" class="btn btn-primary" style="flex: 1;">
                <i data-lucide="pencil-line"></i> Editar
            </a>
            <?php endif; ?>
        </div>
    `;
    
    document.getElementById('eventModal').classList.add('active');
    lucide.createIcons();
}

function closeEventModal() {
    document.getElementById('eventModal').classList.remove('active');
}

function switchView(view) {
    if (view === 'calendar') {
        document.getElementById('calendarView').classList.remove('hidden');
        document.getElementById('listView').classList.remove('active');
        document.getElementById('calendarViewBtn').classList.add('active');
        document.getElementById('listViewBtn').classList.remove('active');
    } else {
        document.getElementById('calendarView').classList.add('hidden');
        document.getElementById('listView').classList.add('active');
        document.getElementById('calendarViewBtn').classList.remove('active');
        document.getElementById('listViewBtn').classList.add('active');
    }
    lucide.createIcons();
}

// Cerrar modal al hacer clic fuera
document.getElementById('eventModal').addEventListener('click', function(e) {
    if (e.target === this) closeEventModal();
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEventModal();
});

// Inicializar calendario
renderCalendar();
</script>

<?php if ($rol === 'coordinador'): ?>
<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-body">
            <div class="modal-icon">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h3 class="modal-title">Eliminar Asignación</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar esta asignación?
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="asig_id" id="deleteModalId">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                    <i data-lucide="trash-2"></i>
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('deleteModalId').value = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
