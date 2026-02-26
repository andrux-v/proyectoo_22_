<?php
/**
 * Vista: Listado de Coordinaciones (index.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/CoordinacionController.php';

$controller = new CoordinacionController();

// Variables para mensajes
$mensaje = $_GET['mensaje'] ?? null;
$error = $_GET['error'] ?? null;

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $resultado = $controller->delete($_POST['coord_id']);
    
    if ($resultado['success']) {
        $mensaje = $resultado['mensaje'];
    } else {
        $error = $resultado['error'];
    }
}

// Obtener todas las coordinaciones
$coordinaciones = $controller->index();

$title = 'Gestión de Coordinaciones';

// Determinar URL del dashboard según el rol
$dashboard_url = '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php';
if ($rol === 'instructor') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php';
} elseif ($rol === 'centro') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php';
}

$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $dashboard_url],
    ['label' => 'Coordinaciones'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Gestión de Coordinaciones</h1>
            <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
            <a href="<?php echo addRolParam('crear.php', $rol); ?>" class="btn btn-primary">
                <i data-lucide="plus"></i>
                Registrar Coordinación
            </a>
            <?php endif; ?>
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

        <!-- Data Table -->
        <div class="table-container">
            <?php if (!empty($coordinaciones)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Coordinación</th>
                            <th>Centro de Formación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coordinaciones as $coordinacion): ?>
                        <tr>
                            <td><span class="table-id"><?php echo htmlspecialchars($coordinacion['coord_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($coordinacion['coord_descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($coordinacion['cent_nombre'] ?? 'N/A'); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?php echo addRolParam('ver.php?id=' . $coordinacion['coord_id'], $rol); ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
                                    <a href="<?php echo addRolParam('editar.php?id=' . $coordinacion['coord_id'], $rol); ?>" class="action-btn edit-btn" title="Editar coordinación">
                                        <i data-lucide="pencil-line"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn" title="Eliminar coordinación" onclick="confirmDelete(<?php echo $coordinacion['coord_id']; ?>, '<?php echo htmlspecialchars(addslashes($coordinacion['coord_descripcion']), ENT_QUOTES); ?>')">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="table-empty">
                    <div class="table-empty-icon">
                        <i data-lucide="users"></i>
                    </div>
                    <div class="table-empty-title">No hay coordinaciones registradas</div>
                    <div class="table-empty-text">
                        <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
                        Haz clic en "Registrar Coordinación" para agregar la primera coordinación.
                        <?php else: ?>
                        No se encontraron coordinaciones en el sistema.
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-body">
            <div class="modal-icon">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h3 class="modal-title">Eliminar Coordinación</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar la coordinación
                <strong id="deleteModalName"></strong>?
                Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="coord_id" id="deleteModalId">
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
    function confirmDelete(id, nombre) {
        document.getElementById('deleteModalId').value = id;
        document.getElementById('deleteModalName').textContent = nombre;
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    // Close modal on overlay click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
