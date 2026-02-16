<?php
/**
 * Detector de Rol
 * Este archivo detecta si el usuario es coordinador o instructor
 * y debe ser incluido al inicio de cada vista
 */

// Detectar el rol desde el parámetro GET o desde el referer
$rol = 'coordinador'; // Por defecto coordinador

if (isset($_GET['rol']) && $_GET['rol'] === 'instructor') {
    $rol = 'instructor';
} elseif (isset($_SERVER['HTTP_REFERER'])) {
    if (strpos($_SERVER['HTTP_REFERER'], '/instructor/dashboard.php') !== false ||
        strpos($_SERVER['HTTP_REFERER'], '?rol=instructor') !== false) {
        $rol = 'instructor';
    }
}

// Función helper para agregar el parámetro rol a las URLs
function addRolParam($url, $rol) {
    if ($rol === 'instructor') {
        $separator = (strpos($url, '?') !== false) ? '&' : '?';
        return $url . $separator . 'rol=instructor';
    }
    return $url;
}
?>
