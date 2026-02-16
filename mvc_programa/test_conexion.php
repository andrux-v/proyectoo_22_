<?php
/**
 * Script de prueba de conexión a la base de datos
 * Ejecutar este archivo para verificar que la conexión funciona correctamente
 */

require_once 'Conexion.php';

echo "<h2>Prueba de Conexión a MySQL</h2>";
echo "<hr>";

try {
    // Intentar obtener la conexión
    $db = Conexion::getConnect();
    
    echo "<p style='color: green;'><strong>✓ Conexión exitosa a la base de datos progsena</strong></p>";
    
    // Probar una consulta simple
    $stmt = $db->query("SELECT DATABASE() as db_name");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<p><strong>Base de datos actual:</strong> " . $result['db_name'] . "</p>";
    
    // Listar las tablas disponibles
    echo "<h3>Tablas disponibles en la base de datos:</h3>";
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'><strong>⚠ No hay tablas en la base de datos.</strong></p>";
        echo "<p>Asegúrate de importar el archivo <code>progFormacion2.sql</code> en phpMyAdmin.</p>";
    }
    
    echo "<hr>";
    echo "<h3>Información de la conexión:</h3>";
    echo "<ul>";
    echo "<li><strong>Host:</strong> localhost</li>";
    echo "<li><strong>Base de datos:</strong> progsena</li>";
    echo "<li><strong>Usuario:</strong> root</li>";
    echo "<li><strong>Charset:</strong> utf8mb4</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>✗ Error de conexión:</strong></p>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<h3>Posibles soluciones:</h3>";
    echo "<ol>";
    echo "<li>Verifica que XAMPP esté ejecutándose (Apache y MySQL)</li>";
    echo "<li>Asegúrate de que la base de datos <strong>progsena</strong> exista en phpMyAdmin</li>";
    echo "<li>Importa el archivo <code>progFormacion2.sql</code> en phpMyAdmin si no lo has hecho</li>";
    echo "<li>Verifica que el usuario sea <strong>root</strong> y la contraseña esté vacía (por defecto en XAMPP)</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Volver al inicio</a></p>";
?>
