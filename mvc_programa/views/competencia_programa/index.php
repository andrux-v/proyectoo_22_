<?php
/**
 * Vista: Listado de Competencias por Programa (index.php)
 */


// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';
// --- Datos de prueba ---
$rol = $rol ?? 'coordinador';
$relaciones = $relaciones ?? [
    ['prog_codigo' => '228106', 'prog_denominacion' => 'Análisis y Desarrollo de Software', 'comp_id' => 1, 'comp_nombre_corto' => 'Promover salud'],
];
$mensaje = $mensaje ?? null;
$error = $error ?? null;
// --- Fin datos de prueba ---

$title = 'Competencias por Programa';
$breadcrumb = [
    ['label' => 'Inicio', 'url' => addRolParam('/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php', $rol)],
    ['label' => 'Competencias por Programa'],
];

// Incluir el header seg�n el rol
if ($rol === 'instructor') {
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    include __DIR__ . '/../layout/header_coordinador.php';
}
?>

        <div class="page-header">
            <h1 class="page-title">Competencias por Programa</h1>
            <?php if ($rol === 'coordinador'): ?>
                <a href="crear.php" class="btn btn-primary">
                    <i data-lucide="link"></i>
                    Asociar Competencia
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
            <?php if (!empty($relaciones)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Programa</th>
                            <th>Competencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($relaciones as $rel): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($rel['prog_denominacion']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($rel['prog_codigo']); ?></small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($rel['comp_nombre_corto']); ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <?php if ($rol === 'coordinador'): ?>
                                        <button type="button" class="action-btn delete-btn" title="Desvincular" 
                                            onclick="confirmDelete('<?php echo $rel['prog_codigo']; ?>', <?php echo $rel['comp_id']; ?>)">
                                            <i data-lucide="unlink"></i>
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
                        <i data-lucide="link-2-off"></i>
                    </div>
                    <div class="table-empty-title">No hay asociaciones registradas</div>
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
            <h3 class="modal-title">Desvincular Competencia</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas quitar esta competencia del programa?
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="PROGRAMA_prog_id" id="deleteModalProgId">
                <input type="hidden" name="COMPETENCIA_comp_id" id="deleteModalCompId">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                    <i data-lucide="unlink"></i>
                    Desvincular
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(progId, compId) {
        document.getElementById('deleteModalProgId').value = progId;
        document.getElementById('deleteModalCompId').value = compId;
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
