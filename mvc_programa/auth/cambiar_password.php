<?php
/**
 * Cambiar Contraseña - Instructor
 */

session_start();

// Verificar que sea un instructor logueado
if (!isset($_SESSION['instructor_id'])) {
    header('Location: login.php?rol=instructor');
    exit;
}

require_once __DIR__ . '/../Conexion.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_actual = $_POST['password_actual'] ?? '';
    $password_nueva = $_POST['password_nueva'] ?? '';
    $password_confirmar = $_POST['password_confirmar'] ?? '';
    
    if (!$password_actual || !$password_nueva || !$password_confirmar) {
        $error = 'Por favor complete todos los campos';
    } elseif ($password_nueva !== $password_confirmar) {
        $error = 'Las contraseñas nuevas no coinciden';
    } elseif (strlen($password_nueva) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            $db = Conexion::getConnect();
            
            // Verificar contraseña actual
            $query = "SELECT inst_password FROM instructor WHERE inst_id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute([':id' => $_SESSION['instructor_id']]);
            $instructor = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($instructor) {
                // Verificar contraseña actual (puede ser hash o texto plano)
                $password_correcta = false;
                if (password_verify($password_actual, $instructor['inst_password'])) {
                    $password_correcta = true;
                } elseif ($password_actual === $instructor['inst_password']) {
                    $password_correcta = true;
                }
                
                if ($password_correcta) {
                    // Actualizar contraseña con hash
                    $password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
                    $query = "UPDATE instructor SET inst_password = :password WHERE inst_id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        ':password' => $password_hash,
                        ':id' => $_SESSION['instructor_id']
                    ]);
                    
                    // Eliminar flag de cambio obligatorio
                    unset($_SESSION['debe_cambiar_password']);
                    
                    $success = 'Contraseña actualizada exitosamente. Redirigiendo...';
                    header('refresh:2;url=/proyectoo_22_/mvc_programa/views/instructor/dashboard.php');
                } else {
                    $error = 'La contraseña actual es incorrecta';
                }
            } else {
                $error = 'Instructor no encontrado';
            }
        } catch (PDOException $e) {
            $error = 'Error al actualizar la contraseña';
            error_log("Error al cambiar password: " . $e->getMessage());
        }
    }
}

$es_obligatorio = isset($_SESSION['debe_cambiar_password']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña — SENA</title>
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            padding: 20px;
        }
        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .auth-header {
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .auth-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .auth-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .auth-body {
            padding: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2d3748;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #39A900;
        }
        .btn-auth {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(57, 169, 0, 0.3);
        }
        .alert-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .auth-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .auth-links a {
            color: #39A900;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        .password-requirements {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #4a5568;
        }
        .password-requirements ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }
        .password-requirements li {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i data-lucide="key" style="width: 40px; height: 40px;"></i>
                </div>
                <h1>Cambiar Contraseña</h1>
                <p><?php echo htmlspecialchars($_SESSION['instructor_nombre'] ?? 'Instructor'); ?></p>
            </div>
            <div class="auth-body">
                <?php if ($es_obligatorio): ?>
                    <div class="alert-warning">
                        <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
                        Por seguridad, debe cambiar su contraseña temporal antes de continuar.
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert-error">
                        <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert-success">
                        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <div class="password-requirements">
                    <strong>Requisitos de la contraseña:</strong>
                    <ul>
                        <li>Mínimo 6 caracteres</li>
                        <li>Se recomienda usar letras, números y símbolos</li>
                    </ul>
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="password_actual" class="form-label">Contraseña Actual</label>
                        <input 
                            type="password" 
                            id="password_actual" 
                            name="password_actual" 
                            class="form-input" 
                            placeholder="Ingrese su contraseña actual"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                        <input 
                            type="password" 
                            id="password_nueva" 
                            name="password_nueva" 
                            class="form-input" 
                            placeholder="Mínimo 6 caracteres"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                        <input 
                            type="password" 
                            id="password_confirmar" 
                            name="password_confirmar" 
                            class="form-input" 
                            placeholder="Repita la nueva contraseña"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn-auth">
                        Actualizar Contraseña
                    </button>
                </form>
                
                <?php if (!$es_obligatorio): ?>
                <div class="auth-links">
                    <a href="/proyectoo_22_/mvc_programa/views/instructor/dashboard.php">Cancelar y volver al dashboard</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
