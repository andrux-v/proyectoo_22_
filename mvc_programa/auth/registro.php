<?php
/**
 * Página de Registro
 */

session_start();

require_once __DIR__ . '/../Conexion.php';

$error = '';
$success = '';
$rol_seleccionado = $_GET['rol'] ?? 'coordinador';

// El instructor no puede registrarse por sí mismo
if ($rol_seleccionado === 'instructor') {
    header('Location: login.php?rol=instructor');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol = $_POST['rol'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validaciones
    if (!$rol || !$nombre || !$correo || !$password || !$password_confirm) {
        $error = 'Por favor complete todos los campos';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            $db = Conexion::getConnect();
            
            // Verificar si el correo ya existe
            $check_query = '';
            switch ($rol) {
                case 'centro':
                    $check_query = "SELECT COUNT(*) as count FROM centro_formacion WHERE cent_correo = :correo";
                    break;
                case 'coordinador':
                    $check_query = "SELECT COUNT(*) as count FROM coordinacion WHERE coord_correo = :correo";
                    break;
            }
            
            $stmt = $db->prepare($check_query);
            $stmt->execute([':correo' => $correo]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                $error = 'El correo electrónico ya está registrado';
            } else {
                // Insertar nuevo usuario
                switch ($rol) {
                    case 'centro':
                        $query = "INSERT INTO centro_formacion (cent_nombre, cent_correo, cent_password) 
                                  VALUES (:nombre, :correo, :password)";
                        break;
                    case 'coordinador':
                        $query = "INSERT INTO coordinacion (coord_nombre_coordinador, coord_correo, coord_password) 
                                  VALUES (:nombre, :correo, :password)";
                        break;
                }
                
                $stmt = $db->prepare($query);
                $params = [
                    ':nombre' => $nombre,
                    ':correo' => $correo,
                    ':password' => $password
                ];
                
                $stmt->execute($params);
                
                $success = 'Registro exitoso. Redirigiendo al login...';
                header('refresh:2;url=login.php?rol=' . $rol);
            }
        } catch (PDOException $e) {
            $error = 'Error al registrar usuario';
            error_log("Error en registro: " . $e->getMessage());
        }
    }
}

// Obtener centros de formación para el select
$centros = [];
try {
    $db = Conexion::getConnect();
    $stmt = $db->query("SELECT cent_id, cent_nombre FROM centro_formacion ORDER BY cent_nombre");
    $centros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener centros: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — SENA</title>
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
        .role-selector {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .role-option {
            padding: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }
        .role-option:hover {
            border-color: #39A900;
            background: #f0fdf4;
        }
        .role-option.active {
            border-color: #39A900;
            background: #f0fdf4;
            font-weight: 600;
        }
        .role-option input[type="radio"] {
            display: none;
        }
        .role-icon-small {
            width: 40px;
            height: 40px;
            margin: 0 auto 8px;
            color: #39A900;
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
        .auth-links .separator {
            margin: 0 12px;
            color: #cbd5e0;
        }

    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i data-lucide="user-plus" style="width: 40px; height: 40px;"></i>
                </div>
                <h1>Crear Cuenta</h1>
                <p>Sistema Académico SENA</p>
            </div>
            <div class="auth-body">
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
                
                <form method="POST" action="" id="registerForm">
                    <div class="form-group">
                        <label class="form-label">Seleccione su rol</label>
                        <div class="role-selector">
                            <label class="role-option <?php echo $rol_seleccionado === 'centro' ? 'active' : ''; ?>" onclick="selectRole('centro')">
                                <input type="radio" name="rol" value="centro" <?php echo $rol_seleccionado === 'centro' ? 'checked' : ''; ?>>
                                <div class="role-icon-small">
                                    <i data-lucide="building-2" style="width: 100%; height: 100%;"></i>
                                </div>
                                <div style="font-size: 13px;">Centro de Formación</div>
                            </label>
                            <label class="role-option <?php echo $rol_seleccionado === 'coordinador' ? 'active' : ''; ?>" onclick="selectRole('coordinador')">
                                <input type="radio" name="rol" value="coordinador" <?php echo $rol_seleccionado === 'coordinador' ? 'checked' : ''; ?>>
                                <div class="role-icon-small">
                                    <i data-lucide="shield-check" style="width: 100%; height: 100%;"></i>
                                </div>
                                <div style="font-size: 13px;">Coordinador</div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            <span id="nombreLabel">Nombre</span>
                        </label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            class="form-input" 
                            placeholder="Ingrese su nombre completo"
                            required
                            value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input 
                            type="email" 
                            id="correo" 
                            name="correo" 
                            class="form-input" 
                            placeholder="ejemplo@sena.edu.co"
                            required
                            value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Mínimo 6 caracteres"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                        <input 
                            type="password" 
                            id="password_confirm" 
                            name="password_confirm" 
                            class="form-input" 
                            placeholder="Repita su contraseña"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn-auth">
                        Crear Cuenta
                    </button>
                </form>
                
                <div class="auth-links">
                    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
                    <span class="separator">|</span>
                    <a href="/proyectoo_22_/mvc_programa/index.php">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectRole(role) {
            document.querySelectorAll('.role-option').forEach(opt => {
                opt.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Actualizar label según el rol
            const nombreLabel = document.getElementById('nombreLabel');
            
            if (role === 'coordinador') {
                nombreLabel.textContent = 'Nombre del Coordinador';
            } else {
                nombreLabel.textContent = 'Nombre del Centro de Formación';
            }
            
            lucide.createIcons();
        }
        
        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRole = document.querySelector('input[name="rol"]:checked').value;
            if (selectedRole === 'coordinador') {
                document.getElementById('nombreLabel').textContent = 'Nombre del Coordinador';
            }
            lucide.createIcons();
        });
    </script>
</body>
</html>
