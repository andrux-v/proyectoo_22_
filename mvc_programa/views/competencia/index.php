<?php
/**
 * Vista: Listado de Competencias (index.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/CompetenciaController.php';

$controller = new CompetenciaController();

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $resultado = $controller->delete($_POST['comp_id']);
    
    if ($resultado['success']) {
        $mensaje = $resultado['mensaje'];
    } else {
        $error = $resultado['error'];
    }
}

// Obtener todas las competencias
$competencias = $controller->index();

// Obtener mensajes de la URL
$mensaje = $_GET['mensaje'] ?? $mensaje ?? null;
$error = $_GET['error'] ?? $error ?? null;

$title = 'Competencias';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Competencias'],
];

// Incluir el header según el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Competencias</h1>
            <?php if ($rol === 'coordinador'): ?>
                <a href="<?php echo addRolParam('crear.php', $rol); ?>" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Registrar Competencia
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

        <div class="table-container">
            <?php if (!empty($competencias)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Corto</th>
                            <th>Unidad de Competencia</th>
                            <th>Horas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($competencias as $comp): ?>
                        <tr>
                            <td><span class="table-id"><?php echo htmlspecialchars($comp['comp_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($comp['comp_nombre_corto']); ?></td>
                            <td><?php echo htmlspecialchars(substr($comp['comp_nombre_unidad_competencia'], 0, 50)) . (strlen($comp['comp_nombre_unidad_competencia']) > 50 ? '...' : ''); ?></td>
                            <td><?php echo htmlspecialchars($comp['comp_horas']); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?php echo addRolParam('ver.php?id=' . $comp['comp_id'], $rol); ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador'): ?>
                                        <a href="<?php echo addRolParam('editar.php?id=' . $comp['comp_id'], $rol); ?>" class="action-btn edit-btn" title="Editar competencia">
                                            <i data-lucide="pencil-line"></i>
                                        </a>
                                        <button type="button" class="action-btn delete-btn" title="Eliminar competencia" onclick="confirmDelete(<?php echo $comp['comp_id']; ?>, '<?php echo htmlspecialchars(addslashes($comp['comp_nombre_corto']), ENT_QUOTES); ?>')">
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
                        <i data-lucide="book-open"></i>
                    </div>
                    <div class="table-empty-title">No hay competencias registradas</div>
                    <div class="table-empty-text">
                        <?php if ($rol === 'coordinador'): ?>
                            Haz clic en "Registrar Competencia" para agregar la primera.
                        <?php
    else: ?>
                            No se encontraron competencias en el sistema.
                        <?php
    endif; ?>
                    </div>
                </div>
            <?php
endif; ?>
        </div>

<!-- Delete Confirmation Modal -->
<?php if ($rol === 'coordinador'): ?>
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-body">
            <div class="modal-icon">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h3 class="modal-title">Eliminar Competencia</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar la competencia
                <strong id="deleteModalName"></strong>?
                Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="comp_id" id="deleteModalId">
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

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
<?php
endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
