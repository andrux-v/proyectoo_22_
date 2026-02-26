<?php
/**
 * Vista: Listado de Sedes (index.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/SedeController.php';

$controller = new SedeController();

// Variables para mensajes
$mensaje = $_GET['mensaje'] ?? null;
$error = $_GET['error'] ?? null;

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $resultado = $controller->delete($_POST['sede_id']);
    
    if ($resultado['success']) {
        $mensaje = $resultado['mensaje'];
    } else {
        $error = $resultado['error'];
    }
}

// Obtener todas las sedes
$sedes = $controller->index();

$title = 'Gestión de Sedes';

// Determinar URL del dashboard según el rol
$dashboard_url = '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php';
if ($rol === 'instructor') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php';
} elseif ($rol === 'centro') {
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php';
}

$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $dashboard_url],
    ['label' => 'Sedes'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Gestión de Sedes</h1>
            <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
                <a href="<?php echo addRolParam('crear.php', $rol); ?>" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Registrar Sede
                </a>
            <?php endif; ?>
        </div>

        <!-- Alerts -->
        <?php if ($mensaje): ?>
            <div class="alert alert-success">
                <i data-lucide="check-circle-2"></i>
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php
endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i data-lucide="alert-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php
endif; ?>

        <!-- Data Table -->
        <div class="table-container">
            <?php if (!empty($sedes)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Sede</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sedes as $sede): ?>
                        <tr>
                            <td><span class="table-id"><?php echo htmlspecialchars($sede['sede_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($sede['sede_nombre']); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?php echo addRolParam('ver.php?id=' . $sede['sede_id'], $rol); ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
                                        <a href="<?php echo addRolParam('editar.php?id=' . $sede['sede_id'], $rol); ?>" class="action-btn edit-btn" title="Editar sede">
                                            <i data-lucide="pencil-line"></i>
                                        </a>
                                        <button type="button" class="action-btn delete-btn" title="Eliminar sede" onclick="confirmDelete(<?php echo $sede['sede_id']; ?>, '<?php echo htmlspecialchars(addslashes($sede['sede_nombre']), ENT_QUOTES); ?>')">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php
    endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
else: ?>
                <div class="table-empty">
                    <div class="table-empty-icon">
                        <i data-lucide="building-2"></i>
                    </div>
                    <div class="table-empty-title">No hay sedes registradas</div>
                    <div class="table-empty-text">
                        <?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
                            Haz clic en "Registrar Sede" para agregar la primera sede.
                        <?php else: ?>
                            No se encontraron sedes en el sistema.
                        <?php endif; ?>
                    </div>
                </div>
            <?php
endif; ?>
        </div>

<!-- Delete Confirmation Modal -->
<?php if ($rol === 'coordinador' || $rol === 'centro'): ?>
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-body">
            <div class="modal-icon">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h3 class="modal-title">Eliminar Sede</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar la sede
                <strong id="deleteModalName"></strong>?
                Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="sede_id" id="deleteModalId">
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
<?php
endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
