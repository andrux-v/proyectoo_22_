<?php
/**
 * Login de Instructor
 */

session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['instructor_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../../Conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($correo && $password) {
        try {
            $db = Conexion::getConnect();
            $query = "SELECT inst_id, inst_nombres, inst_apellidos, inst_correo 
                      FROM instructor 
                      WHERE inst_correo = :correo AND inst_password = :password";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':correo' => $correo,
                ':password' => $password
            ]);
            
            $instructor = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($instructor) {
                // Guardar en sesión
                $_SESSION['instructor_id'] = $instructor['inst_id'];
                $_SESSION['instructor_nombre'] = $instructor['inst_nombres'] . ' ' . $instructor['inst_apellidos'];
                $_SESSION['instructor_correo'] = $instructor['inst_correo'];
                $_SESSION['rol'] = 'instructor';
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Correo o contraseña incorrectos';
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
    <title>Login Instructor — SENA</title>
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .login-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .login-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .login-body {
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
        .btn-login {
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
        .btn-login:hover {
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
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #39A900;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i data-lucide="user" style="width: 40px; height: 40px;"></i>
                </div>
                <h1>Login Instructor</h1>
                <p>Ingrese sus credenciales para continuar</p>
            </div>
            <div class="login-body">
                <?php if ($error): ?>
                    <div class="alert-error">
                        <i data-lucide="alert-circle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
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
                    
                    <button type="submit" class="btn-login">
                        Iniciar Sesión
                    </button>
                </form>
                
                <div class="back-link">
                    <a href="/proyectoo_22_/mvc_programa/index.php">
                        <i data-lucide="arrow-left"></i>
                        Volver a selección de rol
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
