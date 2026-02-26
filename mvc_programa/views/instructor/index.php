<?php
/**
 * Vista: Listado de Instructores (index.php) / Mis Datos (para instructores)
 */

require_once __DIR__ . '/../../controller/InstructorController.php';

// Detectar rol
include __DIR__ . '/../layout/rol_detector.php';

$controller = new InstructorController();
$mensaje = $_GET['mensaje'] ?? null;
$error = $_GET['error'] ?? null;

// Si es instructor, mostrar solo sus datos personales
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
    
    // Obtener datos del instructor logueado
    $instructor = $controller->show($_SESSION['instructor_id']);
    
    if (!$instructor) {
        $error = 'No se pudieron cargar tus datos';
    }
    
    $title = 'Mis Datos Personales';
    $breadcrumb = [
        ['label' => 'Inicio', 'url' => '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php'],
        ['label' => 'Mis Datos'],
    ];
    
    include __DIR__ . '/../layout/header_instructor.php';
} else {
    // Para coordinador y centro, mostrar lista completa
    
    // Manejar acciones POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'delete' && isset($_POST['inst_id'])) {
            $resultado = $controller->delete($_POST['inst_id']);
            if ($resultado['success']) {
                $mensaje = $resultado['mensaje'];
            } else {
                $error = $resultado['error'];
            }
        }
    }
    
    // Obtener lista de instructores
    $instructores = $controller->index();
    
    $title = 'Gestión de Instructores';
    
    // Determinar URL del dashboard según el rol
    $dashboard_url = '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php';
    if ($rol === 'centro') {
        $dashboard_url = '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php';
    }
    
    $breadcrumb = [
        ['label' => 'Inicio', 'url' => $dashboard_url],
        ['label' => 'Instructores'],
    ];
    
    // Incluir el header según el rol
    includeRoleHeader($rol);
}
?>

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

        <?php if ($rol === 'instructor'): ?>
            <!-- Vista de Datos Personales para Instructor -->
            <?php if ($instructor): ?>
            <div class="instructor-profile-wrapper">
                <!-- Header con Avatar y Nombre -->
                <div class="profile-hero">
                    <div class="profile-hero-avatar">
                        <?php echo strtoupper(substr($instructor['inst_nombres'], 0, 1) . substr($instructor['inst_apellidos'], 0, 1)); ?>
                    </div>
                    <h1 class="profile-hero-name"><?php echo htmlspecialchars($instructor['inst_nombres'] . ' ' . $instructor['inst_apellidos']); ?></h1>
                    <p class="profile-hero-role">Instructor SENA</p>
                </div>

                <!-- Grid 2x2 de Tarjetas -->
                <div class="profile-cards-grid">
                    <!-- Tarjeta 1: Correo -->
                    <div class="profile-card">
                        <div class="profile-card-icon">
                            <i data-lucide="mail"></i>
                        </div>
                        <h3 class="profile-card-title">Correo Electrónico</h3>
                        <p class="profile-card-text"><?php echo htmlspecialchars($instructor['inst_correo']); ?></p>
                    </div>

                    <!-- Tarjeta 2: Teléfono -->
                    <div class="profile-card">
                        <div class="profile-card-icon">
                            <i data-lucide="phone"></i>
                        </div>
                        <h3 class="profile-card-title">Teléfono</h3>
                        <p class="profile-card-text"><?php echo htmlspecialchars($instructor['inst_telefono']); ?></p>
                    </div>

                    <!-- Tarjeta 3: Centro de Formación -->
                    <div class="profile-card">
                        <div class="profile-card-icon">
                            <i data-lucide="building-2"></i>
                        </div>
                        <h3 class="profile-card-title">Centro de Formación</h3>
                        <p class="profile-card-text"><?php echo htmlspecialchars($instructor['cent_nombre'] ?? 'No asignado'); ?></p>
                    </div>

                    <!-- Tarjeta 4: Seguridad (clickeable) -->
                    <a href="/proyectoo_22_/mvc_programa/auth/cambiar_password.php" class="profile-card profile-card-clickable">
                        <div class="profile-card-icon">
                            <i data-lucide="key"></i>
                        </div>
                        <h3 class="profile-card-title">Seguridad</h3>
                        <p class="profile-card-text">Cambiar contraseña</p>
                        <div class="profile-card-arrow">
                            <i data-lucide="arrow-right"></i>
                        </div>
                    </a>
                </div>

                <!-- Botón Volver -->
                <div class="profile-footer-action">
                    <a href="/proyectoo_22_/mvc_programa/views/instructor/dashboard.php" class="btn btn-secondary btn-wide">
                        <i data-lucide="arrow-left"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Vista de Lista para Coordinador y Centro -->
            <div class="page-header">
                <h1 class="page-title">Instructores</h1>
                <a href="<?php echo addRolParam('crear.php', $rol); ?>" class="btn btn-primary">
                    <i data-lucide="plus"></i>
                    Registrar Instructor
                </a>
            </div>

            <div class="table-container">
            <?php if (!empty($instructores)): ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($instructores as $inst): ?>
                        <tr>
                            <td><span class="table-id"><?php echo htmlspecialchars($inst['inst_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($inst['inst_nombres'] . ' ' . $inst['inst_apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($inst['inst_correo']); ?></td>
                            <td><?php echo htmlspecialchars($inst['inst_telefono']); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?php echo addRolParam('ver.php?id=' . $inst['inst_id'], $rol); ?>" class="action-btn view-btn" title="Ver detalle">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <a href="<?php echo addRolParam('editar.php?id=' . $inst['inst_id'], $rol); ?>" class="action-btn edit-btn" title="Editar instructor">
                                        <i data-lucide="pencil-line"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn" title="Eliminar instructor" onclick="confirmDelete(<?php echo $inst['inst_id']; ?>, '<?php echo htmlspecialchars(addslashes($inst['inst_nombres'] . ' ' . $inst['inst_apellidos']), ENT_QUOTES); ?>')">
                                        <i data-lucide="trash-2"></i>
                                    </button>
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
                    <div class="table-empty-title">No hay instructores registrados</div>
                    <div class="table-empty-text">
                        Haz clic en "Registrar Instructor" para agregar el primero.
                    </div>
                </div>
            <?php endif; ?>
            </div>

        <?php endif; ?>

<!-- Delete Confirmation Modal -->
<?php if ($rol === 'coordinador'): ?>
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-body">
            <div class="modal-icon">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h3 class="modal-title">Eliminar Instructor</h3>
            <p class="modal-text">
                ¿Estás seguro de que deseas eliminar al instructor
                <strong id="deleteModalName"></strong>?
                Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="" style="flex:1;">
                <input type="hidden" name="inst_id" id="deleteModalId">
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
