<?php
/**
 * Login de Centro de Formación
 */

session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['centro_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../../controller/CentroFormacionController.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($correo) && !empty($password)) {
        $controller = new CentroFormacionController();
        $resultado = $controller->autenticar($correo, $password);
        
        if ($resultado['success']) {
            $_SESSION['centro_id'] = $resultado['centro']['cent_id'];
            $_SESSION['centro_nombre'] = $resultado['centro']['cent_nombre'];
            $_SESSION['centro_correo'] = $resultado['centro']['cent_correo'];
            $_SESSION['rol'] = 'centro';
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $resultado['error'];
        }
    } else {
        $error = 'Por favor ingrese correo y contraseña';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Centro de Formación — SENA</title>
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css?v=2.1">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 48px 40px;
            max-width: 420px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #39A900, #007832);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 16px;
        }
        .login-icon i {
            width: 32px;
            height: 32px;
        }
        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 8px 0;
        }
        .login-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }
        .login-footer a {
            color: var(--green-primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i data-lucide="building-2"></i>
                </div>
                <h1 class="login-title">Centro de Formación</h1>
                <p class="login-subtitle">Ingrese sus credenciales</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i data-lucide="alert-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        class="form-input"
                        placeholder="centro@sena.edu.co"
                        required
                        autocomplete="email"
                        autofocus
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
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i data-lucide="log-in"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="login-footer">
                <a href="/proyectoo_22_/mvc_programa/index.php">← Volver al inicio</a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
