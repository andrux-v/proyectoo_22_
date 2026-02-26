<?php
/**
 * Layout Header — Panel de Instructor (Solo Lectura)
 *
 * Variables expected:
 *   $title       — Page title (e.g. "Mis Asignaciones")
 *   $breadcrumb  — Array of breadcrumb items: [['label' => 'Inicio', 'url' => '/'], ...]
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el instructor esté logueado
if (!isset($_SESSION['instructor_id'])) {
    header('Location: /proyectoo_22_/mvc_programa/views/instructor/login.php');
    exit;
}

$title = $title ?? 'Panel de Instructor';
$breadcrumb = $breadcrumb ?? [];
// NO definir $rol aquí, debe venir de la vista
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> — SENA Instructor</title>
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css?v=2.1">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body>

<!-- Mobile sidebar toggle -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Abrir menú">
    <i data-lucide="menu"></i>
</button>

<!-- Sidebar overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-layout">
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">I</div>
                <div>
                    <div class="sidebar-logo-text">SENA</div>
                    <div class="sidebar-logo-subtitle">Panel Instructor</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Principal</div>
            <a href="/proyectoo_22_/mvc_programa/views/instructor/dashboard.php" class="sidebar-link">
                <i data-lucide="layout-dashboard"></i>
                Mi Dashboard
            </a>

            <div class="sidebar-section-title">Mi Información</div>
            <a href="/proyectoo_22_/mvc_programa/views/instructor/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/instructor/index.php') !== false) ? 'active' : ''; ?>">
                <i data-lucide="user"></i>
                Mis Datos
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/asignacion/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/asignacion') !== false) ? 'active' : ''; ?>">
                <i data-lucide="calendar"></i>
                Mis Asignaciones
            </a>
            
            <div class="sidebar-section-title">Configuración</div>
            <a href="/proyectoo_22_/mvc_programa/auth/cambiar_password.php" class="sidebar-link">
                <i data-lucide="key"></i>
                Cambiar Contraseña
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <?php echo strtoupper(substr($_SESSION['instructor_nombre'] ?? 'I', 0, 1)); ?>
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">
                        <?php echo htmlspecialchars($_SESSION['instructor_nombre'] ?? 'Instructor'); ?>
                    </div>
                    <div class="sidebar-user-role">
                        Instructor
                    </div>
                </div>
            </div>
            <a href="/proyectoo_22_/mvc_programa/auth/logout.php" class="sidebar-logout-btn">
                <i data-lucide="log-out"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <?php if (!empty($breadcrumb)): ?>
        <nav class="breadcrumb">
            <?php foreach ($breadcrumb as $i => $item): ?>
                <?php if ($i > 0): ?>
                    <span class="breadcrumb-separator">/</span>
                <?php endif; ?>
                <?php if (isset($item['url'])): ?>
                    <a href="<?php echo htmlspecialchars($item['url']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
                <?php else: ?>
                    <span class="breadcrumb-current"><?php echo htmlspecialchars($item['label']); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>
