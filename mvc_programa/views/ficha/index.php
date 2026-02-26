<?php
/**
 * Vista: Listado de Fichas (index.php)
 */

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

// Incluir el controlador
require_once __DIR__ . '/../../controller/FichaController.php';

$controller = new FichaController();

// Variables para mensajes
$mensaje = $_GET['mensaje'] ?? null;
$error = $_GET['error'] ?? null;

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $resultado = $controller->delete($_POST['fich_id']);
    
    if ($resultado['success']) {
        $mensaje = $resultado['mensaje'];
    } else {
        $error = $resultado['error'];
    }
}

// Obtener todas las fichas
$fichas = $controller->index();

$title = 'Gestión de Fichas';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Fichas'],
];

// Incluir el header según el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Fichas de Caracterización</h1>
            <?php if ($rol === 'coordinador'): ?>
                <a href="crear.php" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Registrar Ficha
                </a>
            <?php
endif; ?>
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
            <?php if (!empty($fichas)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Ficha</th>
                            <th>Programa</th>
                            <th>Instructor Líder</th>
                            <th>Jornada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fichas as $ficha): ?>
                        <tr>
                            <td><span class="table-id"><?php echo htmlspecialchars($ficha['fich_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($ficha['prog_denominacion']); ?></td>
                            <td><?php echo htmlspecialchars($ficha['instructor_lider']); ?></td>
                            <td><?php echo htmlspecialchars($ficha['fich_jornada']); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="ver.php?id=<?php echo $ficha['fich_id']; ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador'): ?>
                                        <a href="editar.php?id=<?php echo $ficha['fich_id']; ?>" class="action-btn edit-btn" title="Editar ficha">
                                            <i data-lucide="pencil-line"></i>
                                        </a>
                                        <button type="button" class="action-btn delete-btn" title="Eliminar ficha" onclick="confirmDelete('<?php echo $ficha['fich_id']; ?>')">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    <?php
        endif; ?>
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
                    <div class="table-empty-title">No hay fichas registradas</div>
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
            <h3 class="modal-title">Eliminar Ficha</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar la ficha
                <strong id="deleteModalName"></strong>?
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="fich_id" id="deleteModalId">
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
        document.getElementById('deleteModalName').textContent = id;
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
