<?php
/**
 * Dashboard del Instructor
 */

$title = 'Dashboard Instructor';
$breadcrumb = [
    ['label' => 'Mi Dashboard'],
];

include __DIR__ . '/../layout/header_instructor.php';
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
    .info-banner {
        background: #edf2f7;
        border-left: 4px solid #4299e1;
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .info-banner-icon {
        color: #4299e1;
    }
    .info-banner-text {
        color: #2d3748;
        font-size: 14px;
        margin: 0;
    }
</style>

<div class="welcome-banner">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['instructor_nombre'] ?? 'Instructor'); ?></h1>
    <p>Panel de consulta de información académica</p>
</div>

<div class="dashboard-grid">
    <a href="/proyectoo_22_/mvc_programa/views/instructor/index.php?rol=instructor" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="user" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Mis Datos</h3>
        <p class="dashboard-card-description">Ver y consultar mi información personal</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/asignacion/index.php?rol=instructor" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="calendar" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Mis Asignaciones</h3>
        <p class="dashboard-card-description">Ver mi calendario de asignaciones académicas</p>
    </a>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
