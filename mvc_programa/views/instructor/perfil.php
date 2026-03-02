<?php
/**
 * Vista: Perfil del Instructor - Editar Datos Personales
 */

session_start();

// Verificar que el instructor esté logueado
if (!isset($_SESSION['instructor_id'])) {
    header('Location: /proyectoo_22_/mvc_programa/views/instructor/login.php');
    exit;
}

require_once __DIR__ . '/../../controller/InstructorController.php';

$controller = new InstructorController();
$instructor_id = $_SESSION['instructor_id'];

$errores = [];
$mensaje = '';

// Obtener datos actuales del instructor
$instructor = $controller->getInstructorById($instructor_id);

if (!$instructor) {
    header('Location: dashboard.php?error=' . urlencode('No se pudo cargar la información del instructor'));
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'inst_id' => $instructor_id,
        'inst_nombres' => trim($_POST['inst_nombres'] ?? ''),
        'inst_apellidos' => trim($_POST['inst_apellidos'] ?? ''),
        'inst_correo' => trim($_POST['inst_correo'] ?? ''),
        'inst_telefono' => trim($_POST['inst_telefono'] ?? ''),
        'password_actual' => $_POST['password_actual'] ?? '',
        'nueva_password' => $_POST['nueva_password'] ?? '',
        'confirmar_password' => $_POST['confirmar_password'] ?? ''
    ];

    // Validaciones básicas
    if (empty($data['inst_nombres'])) {
        $errores['inst_nombres'] = 'Los nombres son requeridos';
    }
    
    if (empty($data['inst_apellidos'])) {
        $errores['inst_apellidos'] = 'Los apellidos son requeridos';
    }
    
    if (empty($data['inst_correo'])) {
        $errores['inst_correo'] = 'El correo es requerido';
    } elseif (!filter_var($data['inst_correo'], FILTER_VALIDATE_EMAIL)) {
        $errores['inst_correo'] = 'El correo no tiene un formato válido';
    }
    
    if (!empty($data['inst_telefono']) && !preg_match('/^[0-9]{10}$/', $data['inst_telefono'])) {
        $errores['inst_telefono'] = 'El teléfono debe tener 10 dígitos';
    }

    // Validar cambio de contraseña si se proporcionó
    if (!empty($data['nueva_password'])) {
        if (empty($data['password_actual'])) {
            $errores['password_actual'] = 'Debe ingresar su contraseña actual';
        } else {
            // Verificar contraseña actual
            if (!password_verify($data['password_actual'], $instructor['inst_password'])) {
                $errores['password_actual'] = 'La contraseña actual es incorrecta';
            }
        }
        
        if (strlen($data['nueva_password']) < 6) {
            $errores['nueva_password'] = 'La nueva contraseña debe tener al menos 6 caracteres';
        }
        
        if ($data['nueva_password'] !== $data['confirmar_password']) {
            $errores['confirmar_password'] = 'Las contraseñas no coinciden';
        }
    }

    // Si no hay errores, actualizar
    if (empty($errores)) {
        $resultado = $controller->updatePerfil($data);
        
        if ($resultado['success']) {
            // Actualizar datos de sesión
            $_SESSION['instructor_nombre'] = $data['inst_nombres'] . ' ' . $data['inst_apellidos'];
            $_SESSION['instructor_correo'] = $data['inst_correo'];
            
            $mensaje = $resultado['mensaje'];
            
            // Recargar datos actualizados
            $instructor = $controller->getInstructorById($instructor_id);
        } else {
            $errores = $resultado['errores'] ?? ['general' => $resultado['error'] ?? 'Error al actualizar'];
        }
    }
}

$title = 'Mi Perfil';
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => 'dashboard.php'],
    ['label' => 'Mi Perfil'],
];

include __DIR__ . '/../layout/header_instructor.php';
?>

<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
}

.profile-card {
    background: white;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 24px;
}

.profile-header {
    text-align: center;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 2px solid #e5e7eb;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #39A900 0%, #007832 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: white;
    font-size: 32px;
    font-weight: 700;
}

.profile-name {
    font-size: 24px;
    font-weight: 600;
    color: #1a202c;
    margin: 0 0 8px 0;
}

.profile-role {
    color: #6b7280;
    font-size: 14px;
}

.form-section {
    margin-bottom: 32px;
}

.form-section-title {
    font-size: 18px;
    font-weight: 600;
    color: #1a202c;
    margin: 0 0 16px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.password-section {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 24px;
    margin-top: 24px;
}

.alert-info {
    background: #dbeafe;
    border: 1px solid #3b82f6;
    color: #1e40af;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
</style>

<div class="profile-container">
    <div class="page-header">
        <h1 class="page-title">Mi Perfil</h1>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success">
            <i data-lucide="check-circle-2"></i>
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($instructor['inst_nombres'], 0, 1) . substr($instructor['inst_apellidos'], 0, 1)); ?>
            </div>
            <h2 class="profile-name"><?php echo htmlspecialchars($instructor['inst_nombres'] . ' ' . $instructor['inst_apellidos']); ?></h2>
            <p class="profile-role">Instructor SENA</p>
        </div>

        <form method="POST" action="" novalidate>
            <!-- Información Personal -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i data-lucide="user"></i>
                    Información Personal
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="inst_nombres" class="form-label">
                            Nombres <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="inst_nombres"
                            name="inst_nombres"
                            class="form-input <?php echo isset($errores['inst_nombres']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($instructor['inst_nombres']); ?>"
                            required
                        >
                        <?php if (isset($errores['inst_nombres'])): ?>
                            <div class="form-error visible">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_nombres']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="inst_apellidos" class="form-label">
                            Apellidos <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="inst_apellidos"
                            name="inst_apellidos"
                            class="form-input <?php echo isset($errores['inst_apellidos']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($instructor['inst_apellidos']); ?>"
                            required
                        >
                        <?php if (isset($errores['inst_apellidos'])): ?>
                            <div class="form-error visible">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_apellidos']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="inst_correo" class="form-label">
                            Correo Electrónico <span class="required">*</span>
                        </label>
                        <input
                            type="email"
                            id="inst_correo"
                            name="inst_correo"
                            class="form-input <?php echo isset($errores['inst_correo']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($instructor['inst_correo']); ?>"
                            required
                        >
                        <?php if (isset($errores['inst_correo'])): ?>
                            <div class="form-error visible">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_correo']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="inst_telefono" class="form-label">
                            Teléfono
                        </label>
                        <input
                            type="tel"
                            id="inst_telefono"
                            name="inst_telefono"
                            class="form-input <?php echo isset($errores['inst_telefono']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($instructor['inst_telefono'] ?? ''); ?>"
                            placeholder="3001234567"
                            maxlength="10"
                        >
                        <?php if (isset($errores['inst_telefono'])): ?>
                            <div class="form-error visible">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['inst_telefono']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Cambio de Contraseña -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i data-lucide="lock"></i>
                    Cambiar Contraseña
                </h3>
                
                <div class="alert-info">
                    <i data-lucide="info"></i>
                    Deje estos campos vacíos si no desea cambiar su contraseña
                </div>

                <div class="password-section">
                    <div class="form-group">
                        <label for="password_actual" class="form-label">
                            Contraseña Actual
                        </label>
                        <input
                            type="password"
                            id="password_actual"
                            name="password_actual"
                            class="form-input <?php echo isset($errores['password_actual']) ? 'input-error' : ''; ?>"
                            placeholder="••••••••"
                        >
                        <?php if (isset($errores['password_actual'])): ?>
                            <div class="form-error visible">
                                <i data-lucide="alert-circle"></i>
                                <span><?php echo htmlspecialchars($errores['password_actual']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nueva_password" class="form-label">
                                Nueva Contraseña
                            </label>
                            <input
                                type="password"
                                id="nueva_password"
                                name="nueva_password"
                                class="form-input <?php echo isset($errores['nueva_password']) ? 'input-error' : ''; ?>"
                                placeholder="••••••••"
                                minlength="6"
                            >
                            <?php if (isset($errores['nueva_password'])): ?>
                                <div class="form-error visible">
                                    <i data-lucide="alert-circle"></i>
                                    <span><?php echo htmlspecialchars($errores['nueva_password']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="confirmar_password" class="form-label">
                                Confirmar Nueva Contraseña
                            </label>
                            <input
                                type="password"
                                id="confirmar_password"
                                name="confirmar_password"
                                class="form-input <?php echo isset($errores['confirmar_password']) ? 'input-error' : ''; ?>"
                                placeholder="••••••••"
                                minlength="6"
                            >
                            <?php if (isset($errores['confirmar_password'])): ?>
                                <div class="form-error visible">
                                    <i data-lucide="alert-circle"></i>
                                    <span><?php echo htmlspecialchars($errores['confirmar_password']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i>
                    Guardar Cambios
                </button>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i data-lucide="arrow-left"></i>
                    Volver al Dashboard
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>