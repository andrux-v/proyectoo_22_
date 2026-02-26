<?php
/**
 * Dashboard del Coordinador
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el coordinador esté logueado
if (!isset($_SESSION['coordinador_id'])) {
    header('Location: /proyectoo_22_/mvc_programa/auth/login.php');
    exit;
}

$coordinador_nombre = $_SESSION['coordinador_nombre'] ?? 'Coordinador';

$title = 'Dashboard Coordinador';
$breadcrumb = [
    ['label' => 'Dashboard'],
];

include __DIR__ . '/../layout/header_coordinador.php';
?>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }
    .dashboard-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 2px solid transparent;
    }
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        border-color: #39A900;
    }
    .dashboard-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        background: linear-gradient(135deg, #39A900 0%, #007832 100%);
        color: white;
    }
    .dashboard-card-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: #1a202c;
    }
    .dashboard-card-description {
        font-size: 14px;
        color: #718096;
        margin: 0;
        line-height: 1.5;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #39A900 0%, #007832 100%);
        color: white;
        padding: 32px;
        border-radius: 12px;
        margin-bottom: 32px;
    }
    .welcome-banner h1 {
        margin: 0 0 8px 0;
        font-size: 28px;
    }
    .welcome-banner p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
    }
    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin: 32px 0 16px 0;
        color: #1a202c;
    }
</style>

<div class="welcome-banner">
    <h1>Bienvenido, <?php echo htmlspecialchars($coordinador_nombre); ?></h1>
    <p>Panel de coordinación académica del sistema SENA</p>
</div>

<h2 class="section-title">Gestión Académica</h2>
<div class="dashboard-grid">
    <a href="/proyectoo_22_/mvc_programa/views/titulo_programa/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="award" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Comp x Programa</h3>
        <p class="dashboard-card-description">Gestionar competencias por programa de formación</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/ficha/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="book-open" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Fichas</h3>
        <p class="dashboard-card-description">Gestionar fichas de formación</p>
    </a>
</div>

<h2 class="section-title">Asignaciones</h2>
<div class="dashboard-grid">
    <a href="/proyectoo_22_/mvc_programa/views/asignacion/index.php?rol=coordinador" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="calendar" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Calendario de Asignaciones</h3>
        <p class="dashboard-card-description">Administrar programación, horarios y asignaciones</p>
    </a>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
