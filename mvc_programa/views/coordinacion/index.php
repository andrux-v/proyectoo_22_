<?php
/**
 * Vista: Listado de Coordinaciones (index.php)
 *
 * Variables esperadas del controlador:
 *   $coordinaciones — Array de coordinaciones [['coord_id' => 1, 'coord_nombre' => '...', 'CENTRO_FORMACION_cent_id' => 1, 'cent_nombre' => '...'], ...]
 *   $rol            — 'coordinador' | 'instructor'
 *   $mensaje        — (Opcional) Mensaje de éxito
 *   $error          — (Opcional) Mensaje de error
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba (eliminar cuando el controlador los proporcione) ---
$coordinaciones = $coordinaciones ?? [
    ['coord_id' => 1, 'coord_nombre' => 'Coordinación Académica', 'CENTRO_FORMACION_cent_id' => 1, 'cent_nombre' => 'Centro de Gestión de Mercados, Logística y TIC'],
    ['coord_id' => 2, 'coord_nombre' => 'Coordinación de Formación Profesional', 'CENTRO_FORMACION_cent_id' => 1, 'cent_nombre' => 'Centro de Gestión de Mercados, Logística y TIC'],
    ['coord_id' => 3, 'coord_nombre' => 'Coordinación de Bienestar', 'CENTRO_FORMACION_cent_id' => 2, 'cent_nombre' => 'Centro de Tecnologías del Transporte'],
];
$mensaje = $mensaje ?? null;
$error = $error ?? null;
// --- Fin datos de prueba ---

$title = 'Gestión de Coordinaciones';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Coordinaciones'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Gestión de Coordinaciones</h1>
            <?php if ($rol === 'coordinador'): ?>
            <a href="<?php echo addRolParam('crear.php', $rol); ?>" class="btn btn-primary">
                <i data-lucide="plus"></i>
                Registrar Coordinación
            </a>
            <?php endif; ?>
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
                            <td><?php echo htmlspecialchars($coordinacion['coord_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($coordinacion['cent_nombre'] ?? 'N/A'); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?php echo addRolParam('ver.php?id=' . $coordinacion['coord_id'], $rol); ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador'): ?>
                                    <a href="<?php echo addRolParam('editar.php?id=' . $coordinacion['coord_id'], $rol); ?>" class="action-btn edit-btn" title="Editar coordinación">
                                        <i data-lucide="pencil-line"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn" title="Eliminar coordinación" onclick="confirmDelete(<?php echo $coordinacion['coord_id']; ?>, '<?php echo htmlspecialchars(addslashes($coordinacion['coord_nombre']), ENT_QUOTES); ?>')">
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
                        <?php if ($rol === 'coordinador'): ?>
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
