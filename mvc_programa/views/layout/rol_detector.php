<?php
/**
 * Detector de Rol
 * Este archivo detecta si el usuario es centro, coordinador o instructor
 * y debe ser incluido al inicio de cada vista
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detectar el rol con prioridad: sesión > GET > referer > defecto
$rol = 'coordinador'; // Por defecto coordinador

// 1. Prioridad máxima: sesión activa
if (isset($_SESSION['rol'])) {
    $rol = $_SESSION['rol'];
}
// 2. Si no hay sesión pero hay parámetro GET, usarlo
elseif (isset($_GET['rol']) && in_array($_GET['rol'], ['instructor', 'centro', 'coordinador'])) {
    $rol = $_GET['rol'];
}
// 3. Detectar desde el referer como último recurso
elseif (isset($_SERVER['HTTP_REFERER'])) {
    if (strpos($_SERVER['HTTP_REFERER'], '/instructor/dashboard.php') !== false ||
        strpos($_SERVER['HTTP_REFERER'], '?rol=instructor') !== false ||
        strpos($_SERVER['HTTP_REFERER'], '&rol=instructor') !== false) {
        $rol = 'instructor';
    } elseif (strpos($_SERVER['HTTP_REFERER'], '/centro_formacion/dashboard.php') !== false ||
              strpos($_SERVER['HTTP_REFERER'], '?rol=centro') !== false ||
              strpos($_SERVER['HTTP_REFERER'], '&rol=centro') !== false) {
        $rol = 'centro';
    }
}

// Si hay sesión de centro pero no se detectó el rol, forzarlo
if (isset($_SESSION['centro_id']) && !isset($_SESSION['rol'])) {
    $_SESSION['rol'] = 'centro';
    $rol = 'centro';
}

// Si hay sesión de coordinador pero no se detectó el rol, forzarlo
if (isset($_SESSION['coordinador_id']) && !isset($_SESSION['rol'])) {
    $_SESSION['rol'] = 'coordinador';
    $rol = 'coordinador';
}

// Si hay sesión de instructor pero no se detectó el rol, forzarlo
if (isset($_SESSION['instructor_id']) && !isset($_SESSION['rol'])) {
    $_SESSION['rol'] = 'instructor';
    $rol = 'instructor';
}

// Función helper para agregar el parámetro rol a las URLs
function addRolParam($url, $rol) {
    if ($rol === 'instructor' || $rol === 'centro') {
        $separator = (strpos($url, '?') !== false) ? '&' : '?';
        return $url . $separator . 'rol=' . $rol;
    }
    return $url;
}

// Función helper para incluir el header correcto según el rol
function includeRoleHeader($rol) {
    if ($rol === 'instructor') {
        include __DIR__ . '/header_instructor.php';
    } elseif ($rol === 'centro') {
        include __DIR__ . '/header_centro.php';
    } else {
        include __DIR__ . '/header_coordinador.php';
    }
}
?>
