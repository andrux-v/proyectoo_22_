<?php
/**
 * Vista: Listado de Programas (index.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$programas = $programas ?? [
    ['prog_id' => 1, 'prog_codigo' => '228106', 'prog_denominacion' => 'Análisis y Desarrollo de Software', 'prog_version' => '2', 'prog_duracion' => '2640'],
    ['prog_id' => 2, 'prog_codigo' => '233104', 'prog_denominacion' => 'Gestión Contable y Financiera', 'prog_version' => '1', 'prog_duracion' => '1980'],
];
$mensaje = $mensaje ?? null;
$error = $error ?? null;
// --- Fin datos de prueba ---

$title = 'Gestión de Programas';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => $rol === 'instructor' ? '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php' : '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'],
    ['label' => 'Programas'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Programas de Formación</h1>
            <?php if ($rol === 'coordinador'): ?>
                <a href="crear.php" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Registrar Programa
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

        <div class="table-container">
            <?php if (!empty($programas)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Denominación</th>
                            <th>Versión</th>
                            <th>Duración (horas)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programas as $prog): ?>
                        <tr>
                            <td><span class="table-id"><?php echo htmlspecialchars($prog['prog_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($prog['prog_codigo']); ?></td>
                            <td><?php echo htmlspecialchars($prog['prog_denominacion']); ?></td>
                            <td><?php echo htmlspecialchars($prog['prog_version']); ?></td>
                            <td><?php echo htmlspecialchars($prog['prog_duracion']); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="ver.php?id=<?php echo $prog['prog_id']; ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <?php if ($rol === 'coordinador'): ?>
                                        <a href="editar.php?id=<?php echo $prog['prog_id']; ?>" class="action-btn edit-btn" title="Editar programa">
                                            <i data-lucide="pencil-line"></i>
                                        </a>
                                        <button type="button" class="action-btn delete-btn" title="Eliminar programa" onclick="confirmDelete(<?php echo $prog['prog_id']; ?>, '<?php echo htmlspecialchars(addslashes($prog['prog_denominacion']), ENT_QUOTES); ?>')">
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
                        <i data-lucide="graduation-cap"></i>
                    </div>
                    <div class="table-empty-title">No hay programas registrados</div>
                    <div class="table-empty-text">
                        <?php if ($rol === 'coordinador'): ?>
                            Haz clic en "Registrar Programa" para agregar el primero.
                        <?php else: ?>
                            No se encontraron programas en el sistema.
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

<!-- Delete Confirmation Modal -->
<?php if ($rol === 'coordinador'): ?>
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-body">
            <div class="modal-icon">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h3 class="modal-title">Eliminar Programa</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar el programa
                <strong id="deleteModalName"></strong>?
                Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="prog_id" id="deleteModalId">
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
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
