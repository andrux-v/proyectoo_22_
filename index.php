<?php
/**
 * Página de inicio del proyecto
 * Redirige automáticamente al login del sistema
 */

// Redirigir directamente al login
header('Location: mvc_programa/auth/login.php');
exit;
?>