<?php
/**
 * Vista: Listado de Sedes (index.php)
 *
 * Variables esperadas del controlador:
 *   $sedes    — Array de sedes [['sede_id' => 1, 'sede_nombre' => '...'], ...]
 *   $rol      — 'coordinador' | 'instructor'
 *   $mensaje  — (Opcional) Mensaje de éxito
 *   $error    — (Opcional) Mensaje de error
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba (eliminar cuando el controlador los proporcione) ---
$rol = $rol ?? 'coordinador'; // Cambiar a 'instructor' para probar restricciones
$sedes = $sedes ?? [
    ['sede_id' => 1, 'sede_nombre' => 'Centro de Gestión Industrial'],
    ['sede_id' => 2, 'sede_nombre' => 'Centro de Tecnologías del Transporte'],
    ['sede_id' => 3, 'sede_nombre' => 'Centro de Manufactura en Textil y Cuero'],
    ['sede_id' => 4, 'sede_nombre' => 'Centro Metalmecánico'],
    ['sede_id' => 5, 'sede_nombre' => 'Centro de Formación de Talento Humano en Salud'],
];
$mensaje = $mensaje ?? null;
$error = $error ?? null;
// --- Fin datos de prueba ---

$title = 'Gestión de Sedes';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Sedes'],
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
            <h1 class="page-title">Gestión de Sedes</h1>
            <?php if ($rol === 'coordinador'): ?>
                <a href="crear.php" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Registrar Sede
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
                                    <a href="ver.php?id=<?php echo $sede['sede_id']; ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador'): ?>
                                        <a href="editar.php?id=<?php echo $sede['sede_id']; ?>" class="action-btn edit-btn" title="Editar sede">
                                            <i data-lucide="pencil-line"></i>
                                        </a>
                                        <button type="button" class="action-btn delete-btn" title="Eliminar sede" onclick="confirmDelete(<?php echo $sede['sede_id']; ?>, '<?php echo htmlspecialchars(addslashes($sede['sede_nombre']), ENT_QUOTES); ?>')">
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
                        <i data-lucide="building-2"></i>
                    </div>
                    <div class="table-empty-title">No hay sedes registradas</div>
                    <div class="table-empty-text">
                        <?php if ($rol === 'coordinador'): ?>
                            Haz clic en "Registrar Sede" para agregar la primera sede.
                        <?php
    else: ?>
                            No se encontraron sedes en el sistema.
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
