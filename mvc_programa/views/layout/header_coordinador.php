<?php
/**
 * Layout Header — Panel de Coordinador
 *
 * Variables expected:
 *   $title       — Page title (e.g. "Gestión de Sedes")
 *   $breadcrumb  — Array of breadcrumb items: [['label' => 'Inicio', 'url' => '/'], ...]
 */

$title = $title ?? 'Panel de Coordinador';
$breadcrumb = $breadcrumb ?? [];
// NO definir $rol aquí, debe venir de la vista
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> — SENA Coordinador</title>
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
                <div class="sidebar-logo-icon">C</div>
                <div>
                    <div class="sidebar-logo-text">SENA</div>
                    <div class="sidebar-logo-subtitle">Panel Coordinador</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Principal</div>
            <a href="/proyectoo_22_/mvc_programa/views/coordinador/dashboard.php" class="sidebar-link">
                <i data-lucide="layout-dashboard"></i>
                Dashboard
            </a>

            <div class="sidebar-section-title">Gestión Académica</div>
            <a href="/proyectoo_22_/mvc_programa/views/titulo_programa/index.php" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/titulo_programa/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="award"></i>
                Comp x Programa
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/ficha/index.php" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/ficha/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="book-open"></i>
                Fichas
            </a>

            <div class="sidebar-section-title">Asignaciones</div>
            <a href="/proyectoo_22_/mvc_programa/views/asignacion/index.php?rol=coordinador" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/asignacion') !== false) ? 'active' : ''; ?>">
                <i data-lucide="calendar"></i>
                Calendario de Asignaciones
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    C
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">
                        <?php echo htmlspecialchars($_SESSION['coordinador_nombre'] ?? 'Coordinador'); ?>
                    </div>
                    <div class="sidebar-user-role">
                        Coordinador Académico
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
