<?php
/**
 * Layout Header — Panel de Centro de Formación
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el centro esté logueado
if (!isset($_SESSION['centro_id'])) {
    header('Location: /proyectoo_22_/mvc_programa/views/centro_formacion/login.php');
    exit;
}

$title = $title ?? 'Panel Centro de Formación';
$breadcrumb = $breadcrumb ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> — SENA Centro</title>
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
                <div class="sidebar-logo-icon">CF</div>
                <div>
                    <div class="sidebar-logo-text">SENA</div>
                    <div class="sidebar-logo-subtitle">Centro de Formación</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Principal</div>
            <a href="/proyectoo_22_/mvc_programa/views/centro_formacion/dashboard.php" class="sidebar-link">
                <i data-lucide="layout-dashboard"></i>
                Dashboard
            </a>

            <div class="sidebar-section-title">Gestión</div>
            <a href="/proyectoo_22_/mvc_programa/views/sede/index.php?rol=centro" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/sede/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="building-2"></i>
                Sedes
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/ambiente/index.php?rol=centro" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/ambiente/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="monitor"></i>
                Ambientes
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/programa/index.php?rol=centro" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/programa/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="graduation-cap"></i>
                Programas
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/instructor/index.php?rol=centro" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/instructor/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="users"></i>
                Instructores
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/competencia/index.php?rol=centro" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/competencia/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="award"></i>
                Competencias
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/coordinacion/index.php?rol=centro" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/coordinacion/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="users-2"></i>
                Coordinaciones
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    CF
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">
                        <?php echo htmlspecialchars($_SESSION['centro_nombre'] ?? 'Centro'); ?>
                    </div>
                    <div class="sidebar-user-role">
                        Centro de Formación
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
