<?php
/**
 * Dashboard de Centro de Formación
 */

session_start();

// Verificar que el centro esté logueado
if (!isset($_SESSION['centro_id'])) {
    header('Location: /proyectoo_22_/mvc_programa/views/centro_formacion/login.php');
    exit;
}

$title = 'Dashboard Centro de Formación';
$breadcrumb = [
    ['label' => 'Dashboard'],
];
$rol = 'centro';

include __DIR__ . '/../layout/header_centro.php';
?>

<div class="page-header">
    <h1 class="page-title">Bienvenido, <?php echo htmlspecialchars($_SESSION['centro_nombre']); ?></h1>
</div>

<div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
    <!-- Tarjeta Sedes -->
    <a href="/proyectoo_22_/mvc_programa/views/sede/index.php?rol=centro" class="dashboard-card" style="text-decoration: none; color: inherit;">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
            <i data-lucide="building-2"></i>
        </div>
        <h3 class="dashboard-card-title">Sedes</h3>
        <p class="dashboard-card-text">Gestionar sedes del centro</p>
    </a>

    <!-- Tarjeta Ambientes -->
    <a href="/proyectoo_22_/mvc_programa/views/ambiente/index.php?rol=centro" class="dashboard-card" style="text-decoration: none; color: inherit;">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
            <i data-lucide="monitor"></i>
        </div>
        <h3 class="dashboard-card-title">Ambientes</h3>
        <p class="dashboard-card-text">Gestionar ambientes de formación</p>
    </a>

    <!-- Tarjeta Programas -->
    <a href="/proyectoo_22_/mvc_programa/views/programa/index.php?rol=centro" class="dashboard-card" style="text-decoration: none; color: inherit;">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i data-lucide="graduation-cap"></i>
        </div>
        <h3 class="dashboard-card-title">Programas</h3>
        <p class="dashboard-card-text">Gestionar programas de formación</p>
    </a>

    <!-- Tarjeta Instructores -->
    <a href="/proyectoo_22_/mvc_programa/views/instructor/index.php?rol=centro" class="dashboard-card" style="text-decoration: none; color: inherit;">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i data-lucide="users"></i>
        </div>
        <h3 class="dashboard-card-title">Instructores</h3>
        <p class="dashboard-card-text">Gestionar instructores</p>
    </a>

    <!-- Tarjeta Competencias -->
    <a href="/proyectoo_22_/mvc_programa/views/competencia/index.php?rol=centro" class="dashboard-card" style="text-decoration: none; color: inherit;">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #ec4899, #db2777);">
            <i data-lucide="award"></i>
        </div>
        <h3 class="dashboard-card-title">Competencias</h3>
        <p class="dashboard-card-text">Gestionar competencias</p>
    </a>

    <!-- Tarjeta Coordinaciones -->
    <a href="/proyectoo_22_/mvc_programa/views/coordinacion/index.php?rol=centro" class="dashboard-card" style="text-decoration: none; color: inherit;">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
            <i data-lucide="users-2"></i>
        </div>
        <h3 class="dashboard-card-title">Coordinaciones</h3>
        <p class="dashboard-card-text">Gestionar coordinaciones</p>
    </a>
</div>

<style>
.dashboard-card {
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    padding: 24px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.dashboard-card:hover {
    border-color: var(--green-primary);
    box-shadow: 0 8px 24px rgba(57, 169, 0, 0.15);
    transform: translateY(-4px);
}

.dashboard-card-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-bottom: 16px;
}

.dashboard-card-icon i {
    width: 28px;
    height: 28px;
    stroke-width: 2;
}

.dashboard-card-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0 0 8px 0;
}

.dashboard-card-text {
    font-size: 14px;
    color: var(--gray-600);
    margin: 0;
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>
