<?php
/**
 * Dashboard del Coordinador
 */

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
        border-color: #667eea;
    }
    .dashboard-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    <h1>Bienvenido, Coordinador</h1>
    <p>Panel de administración del sistema académico SENA</p>
</div>

<h2 class="section-title">Gestión Académica</h2>
<div class="dashboard-grid">
    <a href="/proyectoo_22_/mvc_programa/views/centro_formacion/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="building" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Centros de Formación</h3>
        <p class="dashboard-card-description">Administrar centros de formación del SENA</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/coordinacion/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="users-2" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Coordinaciones</h3>
        <p class="dashboard-card-description">Gestionar coordinaciones académicas</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/sede/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="building-2" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Sedes</h3>
        <p class="dashboard-card-description">Administrar sedes académicas</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/ambiente/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="monitor" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Ambientes</h3>
        <p class="dashboard-card-description">Gestionar ambientes de formación</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/programa/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="graduation-cap" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Programas</h3>
        <p class="dashboard-card-description">Administrar programas de formación</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/competencia/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="award" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Competencias</h3>
        <p class="dashboard-card-description">Gestionar competencias académicas</p>
    </a>
</div>

<h2 class="section-title">Gestión de Personal y Asignaciones</h2>
<div class="dashboard-grid">
    <a href="/proyectoo_22_/mvc_programa/views/instructor/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="users" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Instructores</h3>
        <p class="dashboard-card-description">Administrar instructores</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/ficha/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="book-open" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Fichas</h3>
        <p class="dashboard-card-description">Gestionar fichas de formación</p>
    </a>

    <a href="/proyectoo_22_/mvc_programa/views/asignacion/index.php" class="dashboard-card">
        <div class="dashboard-card-icon">
            <i data-lucide="clipboard-list" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="dashboard-card-title">Asignaciones</h3>
        <p class="dashboard-card-description">Administrar asignaciones de instructores</p>
    </a>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
