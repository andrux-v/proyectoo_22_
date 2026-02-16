<?php
// Archivo de prueba para verificar rutas
echo "<h2>Información de Rutas</h2>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>HTTP_HOST:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";

$scriptName = $_SERVER['SCRIPT_NAME'];
$baseUrl = dirname(dirname($scriptName));
if ($baseUrl === '/' || $baseUrl === '\\') {
    $baseUrl = '';
}

echo "<p><strong>Base URL calculada:</strong> " . $baseUrl . "</p>";
echo "<p><strong>Ruta CSS:</strong> " . $baseUrl . "/assets/css/styles.css</p>";

echo "<hr>";
echo "<h3>Prueba de carga de CSS:</h3>";
echo '<link rel="stylesheet" href="' . $baseUrl . '/assets/css/styles.css">';
echo '<p style="color: var(--green-primary); font-size: 20px;">Si ves este texto en verde, el CSS está cargando correctamente.</p>';
?>
