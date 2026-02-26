<?php
/**
 * Página de Login Unificada
 */

session_start();

// Si ya está logueado, mostrar opción de cerrar sesión o ir al dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['rol'])) {
    $rol_actual = $_SESSION['rol'];
    $nombre_rol = $rol_actual === 'centro' ? 'Centro de Formación' : ($rol_actual === 'coordinador' ? 'Coordinador' : 'Instructor');
    
    // Si viene con parámetro force_logout, cerrar sesión
    if (isset($_GET['force_logout'])) {
        session_destroy();
        header('Location: /proyectoo_22_/mvc_programa/auth/login.php');
        exit;
    }
    
    // Mostrar página de ya logueado
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ya has iniciado sesión — SENA</title>
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
                padding: 40px;
                text-align: center;
            }
            .auth-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 20px;
                background: linear-gradient(135deg, #39A900 0%, #007832 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }
            h1 { margin: 0 0 10px 0; font-size: 24px; color: #1a202c; }
            p { color: #718096; margin: 0 0 30px 0; }
            .btn-group { display: flex; flex-direction: column; gap: 12px; }
            .btn { padding: 14px; border-radius: 8px; font-weight: 600; text-decoration: none; display: block; }
            .btn-primary { background: linear-gradient(135deg, #39A900 0%, #007832 100%); color: white; }
            .btn-secondary { background: white; color: #39A900; border: 2px solid #39A900; }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-icon">
                    <i data-lucide="check-circle" style="width: 40px; height: 40px;"></i>
                </div>
                <h1>Ya has iniciado sesión</h1>
                <p>Estás conectado como <strong><?php echo $nombre_rol; ?></strong></p>
                <div class="btn-group">
                    <a href="<?php 
                        switch($rol_actual) {
                            case 'centro': echo '/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php'; break;
                            case 'coordinador': echo '/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php'; break;
                            case 'instructor': echo '/proyectoo_22_/mvc_programa/views/instructor/dashboard.php'; break;
                        }
                    ?>" class="btn btn-primary">
                        Ir al Dashboard
                    </a>
                    <a href="/proyectoo_22_/mvc_programa/auth/logout.php" class="btn btn-secondary">
                        Cerrar Sesión e Iniciar con Otro Rol
                    </a>
                </div>
            </div>
        </div>
        <script>lucide.createIcons();</script>
    </body>
    </html>
    <?php
    exit;
}

require_once __DIR__ . '/../Conexion.php';

$error = '';
$rol_seleccionado = $_GET['rol'] ?? 'coordinador';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';
    
    if ($correo && $password && $rol) {
        try {
            $db = Conexion::getConnect();
            
            // Determinar tabla y campos según el rol
            switch ($rol) {
                case 'centro':
                    $query = "SELECT cent_id as id, cent_nombre as nombre, cent_correo as correo, cent_password as password 
                              FROM centro_formacion 
                              WHERE cent_correo = :correo";
                    break;
                case 'coordinador':
                    $query = "SELECT coord_id as id, coord_nombre_coordinador as nombre, coord_correo as correo, coord_password as password 
                              FROM coordinacion 
                              WHERE coord_correo = :correo";
                    break;
                case 'instructor':
                    $query = "SELECT inst_id as id, CONCAT(inst_nombres, ' ', inst_apellidos) as nombre, 
                              inst_correo as correo, inst_password as password
                              FROM instructor 
                              WHERE inst_correo = :correo";
                    break;
                default:
                    $error = 'Rol no válido';
                    break;
            }
            
            if (!$error) {
                $stmt = $db->prepare($query);
                $stmt->execute([':correo' => $correo]);
                
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($usuario) {
                    // Verificar contraseña
                    $password_valido = false;
                    
                    // Para centro, coordinador e instructor, verificar con password_verify (contraseñas hasheadas)
                    if ($rol === 'centro' || $rol === 'instructor' || $rol === 'coordinador') {
                        $password_limpia = trim($password);
                        $hash_limpio = trim($usuario['password']);
                        
                        if (password_verify($password_limpia, $hash_limpio)) {
                            $password_valido = true;
                        }
                    } else {
                        // Fallback para otros roles (si existen)
                        $password_valido = (trim($password) === trim($usuario['password']));
                    }
                    
                    if ($password_valido) {
                        // Guardar en sesión
                        $_SESSION['user_id'] = $usuario['id'];
                        $_SESSION['user_nombre'] = $usuario['nombre'];
                        $_SESSION['user_correo'] = $usuario['correo'];
                        $_SESSION['rol'] = $rol;
                        
                        // Para centro, guardar datos específicos
                        if ($rol === 'centro') {
                            $_SESSION['centro_id'] = $usuario['id'];
                            $_SESSION['centro_nombre'] = $usuario['nombre'];
                            $_SESSION['centro_correo'] = $usuario['correo'];
                        }
                        
                        // Para coordinador, guardar datos específicos
                        if ($rol === 'coordinador') {
                            $_SESSION['coordinador_id'] = $usuario['id'];
                            $_SESSION['coordinador_nombre'] = $usuario['nombre'];
                            $_SESSION['coordinador_correo'] = $usuario['correo'];
                        }
                        
                        // Para instructor, guardar datos específicos
                        if ($rol === 'instructor') {
                            $_SESSION['instructor_id'] = $usuario['id'];
                            $_SESSION['instructor_nombre'] = $usuario['nombre'];
                            $_SESSION['instructor_correo'] = $usuario['correo'];
                            
                            // Si la contraseña es temporal, redirigir a cambio de contraseña
                            if (strpos($password, 'temp') !== false || $password === 'temporal123') {
                                $_SESSION['debe_cambiar_password'] = true;
                                header('Location: /proyectoo_22_/mvc_programa/auth/cambiar_password.php');
                                exit;
                            }
                        }
                        
                        // Redirigir al dashboard correspondiente
                        switch ($rol) {
                            case 'centro':
                                header('Location: /proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php');
                                break;
                            case 'coordinador':
                                header('Location: /proyectoo_22_/mvc_programa/views/coordinador/dashboard.php');
                                break;
                            case 'instructor':
                                header('Location: /proyectoo_22_/mvc_programa/views/instructor/dashboard.php');
                                break;
                        }
                        exit;
                    } else {
                        $error = 'Correo o contraseña incorrectos';
                    }
                } else {
                    $error = 'Correo o contraseña incorrectos';
                }
            }
        } catch (PDOException $e) {
            $error = 'Error al iniciar sesión';
            error_log("Error en login: " . $e->getMessage());
        }
    } else {
        $error = 'Por favor complete todos los campos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — SENA</title>
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(57, 169, 0, 0.9) 0%, rgba(0, 120, 50, 0.9) 100%),
                        url('/proyectoo_22_/mvc_programa/sena_doble_titulacion-e1754918865796.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
            position: relative;
        }
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            position: relative;
            z-index: 1;
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
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .role-option {
            padding: 12px;
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
            width: 32px;
            height: 32px;
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
                    <i data-lucide="log-in" style="width: 40px; height: 40px;"></i>
                </div>
                <h1>Iniciar Sesión</h1>
                <p>Sistema Académico SENA</p>
            </div>
            <div class="auth-body">
                <?php if ($error): ?>
                    <div class="alert-error">
                        <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Seleccione su rol</label>
                        <div class="role-selector">
                            <label class="role-option <?php echo $rol_seleccionado === 'centro' ? 'active' : ''; ?>" onclick="selectRole('centro')">
                                <input type="radio" name="rol" value="centro" <?php echo $rol_seleccionado === 'centro' ? 'checked' : ''; ?>>
                                <div class="role-icon-small">
                                    <i data-lucide="building-2" style="width: 100%; height: 100%;"></i>
                                </div>
                                <div style="font-size: 12px;">Centro</div>
                            </label>
                            <label class="role-option <?php echo $rol_seleccionado === 'coordinador' ? 'active' : ''; ?>" onclick="selectRole('coordinador')">
                                <input type="radio" name="rol" value="coordinador" <?php echo $rol_seleccionado === 'coordinador' ? 'checked' : ''; ?>>
                                <div class="role-icon-small">
                                    <i data-lucide="shield-check" style="width: 100%; height: 100%;"></i>
                                </div>
                                <div style="font-size: 12px;">Coordinador</div>
                            </label>
                            <label class="role-option <?php echo $rol_seleccionado === 'instructor' ? 'active' : ''; ?>" onclick="selectRole('instructor')">
                                <input type="radio" name="rol" value="instructor" <?php echo $rol_seleccionado === 'instructor' ? 'checked' : ''; ?>>
                                <div class="role-icon-small">
                                    <i data-lucide="user" style="width: 100%; height: 100%;"></i>
                                </div>
                                <div style="font-size: 12px;">Instructor</div>
                            </label>
                        </div>
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
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn-auth">
                        Iniciar Sesión
                    </button>
                </form>
                
                <div class="auth-links">
                    <span id="registerLink">
                        ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
                    </span>
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
            
            // Limpiar el campo de correo al cambiar de rol
            const correoInput = document.getElementById('correo');
            if (correoInput) {
                correoInput.value = '';
            }
            
            // Actualizar texto del enlace de registro
            const registerLink = document.getElementById('registerLink');
            if (role === 'instructor') {
                registerLink.innerHTML = '<span style="color: #718096;">Los instructores son registrados por el Centro de Formación</span>';
            } else {
                registerLink.innerHTML = '¿No tienes cuenta? <a href="registro.php?rol=' + role + '">Regístrate aquí</a>';
            }
            
            lucide.createIcons();
        }
        
        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRole = document.querySelector('input[name="rol"]:checked').value;
            if (selectedRole === 'instructor') {
                document.getElementById('registerLink').innerHTML = '<span style="color: #718096;">Los instructores son registrados por el Centro de Formación</span>';
            }
            lucide.createIcons();
        });
    </script>
</body>
</html>
