<?php
/**
 * Logout de Instructor
 */

session_start();
session_destroy();

header('Location: /proyectoo_22_/mvc_programa/index.php');
exit;
?>
