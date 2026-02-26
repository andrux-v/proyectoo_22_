<?php
/**
 * Vista: Listado de Centros de Formación (index.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/CentroFormacionController.php';

$controller = new CentroFormacionController();

// Variables para mensajes
$mensaje = $_GET['mensaje'] ?? null;
$error = $_GET['error'] ?? null;

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $resultado = $controller->delete($_POST['cent_id']);
    
    if ($resultado['success']) {
        $mensaje = $resultado['mensaje'];
    } else {
        $error = $resultado['error'];
    }
}

// Obtener todos los centros
$centros = $controller->index();

$title = 'Gestión de Centros de Formación';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Centros de Formación'],
];

// Incluir el header según el rol
includeRoleHeader($rol);
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Gestión de Centros de Formación</h1>
            <?php if ($rol === 'coordinador'): ?>
            <a href="<?php echo addRolParam('crear.php', $rol); ?>" class="btn btn-primary">
                <i data-lucide="plus"></i>
                Registrar Centro
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
            <?php if (!empty($centros)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Centro de Formación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($centros as $centro): ?>
                        <tr>
                            <td><span class="table-id"><?php echo htmlspecialchars($centro['cent_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($centro['cent_nombre']); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?php echo addRolParam('ver.php?id=' . $centro['cent_id'], $rol); ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador'): ?>
                                    <a href="<?php echo addRolParam('editar.php?id=' . $centro['cent_id'], $rol); ?>" class="action-btn edit-btn" title="Editar centro">
                                        <i data-lucide="pencil-line"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn" title="Eliminar centro" onclick="confirmDelete(<?php echo $centro['cent_id']; ?>, '<?php echo htmlspecialchars(addslashes($centro['cent_nombre']), ENT_QUOTES); ?>')">
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
                        <i data-lucide="building"></i>
                    </div>
                    <div class="table-empty-title">No hay centros de formación registrados</div>
                    <div class="table-empty-text">
                        <?php if ($rol === 'coordinador'): ?>
                        Haz clic en "Registrar Centro" para agregar el primer centro de formación.
                        <?php else: ?>
                        No se encontraron centros de formación en el sistema.
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
            <h3 class="modal-title">Eliminar Centro de Formación</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar el centro
                <strong id="deleteModalName"></strong>?
                Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="cent_id" id="deleteModalId">
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
