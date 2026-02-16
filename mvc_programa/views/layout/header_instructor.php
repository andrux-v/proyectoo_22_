<?php
/**
 * Layout Header — Panel de Instructor (Solo Lectura)
 *
 * Variables expected:
 *   $title       — Page title (e.g. "Mis Asignaciones")
 *   $breadcrumb  — Array of breadcrumb items: [['label' => 'Inicio', 'url' => '/'], ...]
 */

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
    <link rel="stylesheet" href="/proyectoo_22_/mvc_programa/assets/css/styles.css?v=2.0">
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

            <div class="sidebar-section-title">Gestión Académica</div>
            <a href="/proyectoo_22_/mvc_programa/views/sede/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/sede/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="building-2"></i>
                Sedes
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/ambiente/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/ambiente/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="monitor"></i>
                Ambientes
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/programa/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/programa/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="graduation-cap"></i>
                Programas
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/competencia/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/competencia/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="award"></i>
                Competencias
            </a>

            <div class="sidebar-section-title">Mis Asignaciones</div>
            <a href="/proyectoo_22_/mvc_programa/views/ficha/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/ficha/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="book-open"></i>
                Fichas
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/asignacion/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/asignacion/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="clipboard-list"></i>
                Asignaciones
            </a>
            <a href="/proyectoo_22_/mvc_programa/views/instructor/index.php?rol=instructor" class="sidebar-link <?php echo(strpos($_SERVER['REQUEST_URI'] ?? '', '/instructor/') !== false) ? 'active' : ''; ?>">
                <i data-lucide="users"></i>
                Instructores
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    I
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">
                        Instructor
                    </div>
                    <div class="sidebar-user-role">
                        Solo Lectura
                    </div>
                </div>
            </div>
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
