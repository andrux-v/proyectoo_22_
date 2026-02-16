<?php
/**
 * Script de depuración para el módulo de Ambiente
 * Este archivo ayuda a diagnosticar problemas con la conexión y el controlador
 */

error_reporting(E_ALL);
ini_display_errors = 1;

echo "<h2>Diagnóstico del Módulo Ambiente</h2>";
echo "<hr>";

// 1. Verificar la conexión
echo "<h3>1. Probando Conexión a la Base de Datos</h3>";
try {
    require_once __DIR__ . '/Conexion.php';
    $db = Conexion::getConnect();
    echo "✓ Conexión exitosa a la base de datos 'progsena'<br>";
} catch (Exception $e) {
    echo "✗ Error de conexión: " . $e->getMessage() . "<br>";
    die();
}

// 2. Verificar que existe la tabla ambiente
echo "<h3>2. Verificando tabla 'ambiente'</h3>";
try {
    $stmt = $db->query("SHOW TABLES LIKE 'ambiente'");
    if ($stmt->rowCount() > 0) {
        echo "✓ La tabla 'ambiente' existe<br>";
    } else {
        echo "✗ La tabla 'ambiente' NO existe<br>";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 3. Verificar estructura de la tabla
echo "<h3>3. Estructura de la tabla 'ambiente'</h3>";
try {
    $stmt = $db->query("DESCRIBE ambiente");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 4. Verificar que existe la tabla sede
echo "<h3>4. Verificando tabla 'sede'</h3>";
try {
    $stmt = $db->query("SHOW TABLES LIKE 'sede'");
    if ($stmt->rowCount() > 0) {
        echo "✓ La tabla 'sede' existe<br>";
        
        // Contar sedes
        $stmt = $db->query("SELECT COUNT(*) as total FROM sede");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✓ Total de sedes en la base de datos: " . $result['total'] . "<br>";
        
        // Listar sedes
        if ($result['total'] > 0) {
            $stmt = $db->query("SELECT sede_id, sede_nombre FROM sede");
            $sedes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<ul>";
            foreach ($sedes as $sede) {
                echo "<li>ID: " . $sede['sede_id'] . " - " . $sede['sede_nombre'] . "</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "✗ La tabla 'sede' NO existe<br>";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 5. Probar el modelo
echo "<h3>5. Probando AmbienteModel</h3>";
try {
    require_once __DIR__ . '/model/AmbienteModel.php';
    echo "✓ AmbienteModel cargado correctamente<br>";
} catch (Exception $e) {
    echo "✗ Error al cargar AmbienteModel: " . $e->getMessage() . "<br>";
    echo "✗ Archivo: " . $e->getFile() . "<br>";
    echo "✗ Línea: " . $e->getLine() . "<br>";
}

// 6. Probar el controlador
echo "<h3>6. Probando AmbienteController</h3>";
try {
    require_once __DIR__ . '/controller/AmbienteController.php';
    $controller = new AmbienteController();
    echo "✓ AmbienteController cargado correctamente<br>";
    
    // Probar método getSedes
    $sedes = $controller->getSedes();
    echo "✓ Método getSedes() funciona - Retorna " . count($sedes) . " sedes<br>";
    
    // Probar método index
    $ambientes = $controller->index();
    echo "✓ Método index() funciona - Retorna " . count($ambientes) . " ambientes<br>";
    
} catch (Exception $e) {
    echo "✗ Error al cargar AmbienteController: " . $e->getMessage() . "<br>";
    echo "✗ Archivo: " . $e->getFile() . "<br>";
    echo "✗ Línea: " . $e->getLine() . "<br>";
}

// 7. Información del sistema
echo "<h3>7. Información del Sistema</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Directorio actual: " . __DIR__ . "<br>";
echo "Archivo Conexion.php: " . (__DIR__ . '/Conexion.php') . "<br>";
echo "¿Existe Conexion.php? " . (file_exists(__DIR__ . '/Conexion.php') ? 'Sí' : 'No') . "<br>";

echo "<hr>";
echo "<h3>Diagnóstico Completado</h3>";
echo "<p><a href='views/ambiente/index.php'>Ir al módulo de Ambientes</a></p>";
?>
