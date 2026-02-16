<?php
/**
 * Clase Conexion - Patrón Singleton para conexión a MySQL
 * 
 * INSTRUCCIONES:
 * 1. Copia este archivo y renómbralo a "Conexion.php"
 * 2. Configura los valores de conexión según tu entorno
 * 3. NO subas el archivo Conexion.php a GitHub (está en .gitignore)
 */

class Conexion
{
    private static $instance = NULL;

    // Configuración de la base de datos - MODIFICA ESTOS VALORES
    private static $host = 'localhost';
    private static $dbname = 'progsena';
    private static $username = 'root';
    private static $password = '';
    private static $charset = 'utf8mb4';

    private function __construct()
    {
    }

    public static function getConnect()
    {
        if (!isset(self::$instance)) {
            try {
                $pdo_options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::$charset
                ];
                
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
                
                self::$instance = new PDO($dsn, self::$username, self::$password, $pdo_options);
                
            } catch (PDOException $e) {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    /**
     * Método para cambiar la configuración de la base de datos si es necesario
     */
    public static function setConfig($host, $dbname, $username, $password, $charset = 'utf8mb4')
    {
        self::$host = $host;
        self::$dbname = $dbname;
        self::$username = $username;
        self::$password = $password;
        self::$charset = $charset;
        self::$instance = NULL; // Resetear la instancia para que se reconecte
    }
}

?>
