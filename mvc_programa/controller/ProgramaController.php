<?php
/**
 * Controlador de Programa
 * Maneja todas las operaciones CRUD para programas
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/ProgramaModel.php';

class ProgramaController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todos los programas con información del título de programa
     */
    public function index()
    {
        try {
            $query = "SELECT p.prog_codigo, p.prog_denominacion, p.TIT_PROGRAMA_titpro_id, 
                             p.prog_tipo, t.titpro_nombre 
                      FROM programa p 
                      LEFT JOIN titulo_programa t ON p.TIT_PROGRAMA_titpro_id = t.titpro_id 
                      ORDER BY p.prog_denominacion ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProgramaController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un programa por código
     */
    public function show($prog_codigo)
    {
        try {
            $query = "SELECT p.prog_codigo, p.prog_denominacion, p.TIT_PROGRAMA_titpro_id, 
                             p.prog_tipo, t.titpro_nombre 
                      FROM programa p 
                      LEFT JOIN titulo_programa t ON p.TIT_PROGRAMA_titpro_id = t.titpro_id 
                      WHERE p.prog_codigo = :prog_codigo";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':prog_codigo' => $prog_codigo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProgramaController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear un nuevo programa
     */
    public function create($data)
    {
        try {
            // Validar datos
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Crear el programa usando el modelo
            $programa = new ProgramaModel(
                $data['prog_codigo'],
                $data['prog_denominacion'],
                $data['TIT_PROGRAMA_titpro_id'],
                $data['prog_tipo']
            );

            $programa->create();

            return ['success' => true, 'mensaje' => 'Programa creado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en ProgramaController::create - " . $e->getMessage());
            
            // Verificar si es error de clave duplicada
            if ($e->getCode() == 23000) {
                return ['success' => false, 'errores' => ['prog_codigo' => 'El código del programa ya existe']];
            }
            
            return ['success' => false, 'errores' => ['general' => 'Error al crear el programa']];
        }
    }

    /**
     * Actualizar un programa existente
     */
    public function update($data)
    {
        try {
            // Validar datos
            $errores = $this->validar($data, true);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Actualizar usando el modelo
            $programa = new ProgramaModel(
                $data['prog_codigo'],
                $data['prog_denominacion'],
                $data['TIT_PROGRAMA_titpro_id'],
                $data['prog_tipo']
            );

            $programa->update();

            return ['success' => true, 'mensaje' => 'Programa actualizado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en ProgramaController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar el programa']];
        }
    }

    /**
     * Eliminar un programa
     */
    public function delete($prog_codigo)
    {
        try {
            // Verificar si el programa está siendo usado en fichas
            $query = "SELECT COUNT(*) as count FROM ficha WHERE PROGRAMA_prog_id = :prog_codigo";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':prog_codigo' => $prog_codigo]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el programa porque tiene fichas asociadas'];
            }

            // Verificar si tiene competencias asociadas
            $query = "SELECT COUNT(*) as count FROM competxprograma WHERE PROGRAMA_prog_id = :prog_codigo";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':prog_codigo' => $prog_codigo]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el programa porque tiene competencias asociadas'];
            }

            // Eliminar usando el modelo
            $programa = new ProgramaModel($prog_codigo, '', '', '');
            $programa->delete();

            return ['success' => true, 'mensaje' => 'Programa eliminado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en ProgramaController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar el programa'];
        }
    }

    /**
     * Obtener todos los títulos de programa para el select
     */
    public function getTitulosPrograma()
    {
        try {
            $query = "SELECT titpro_id, titpro_nombre FROM titulo_programa ORDER BY titpro_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProgramaController::getTitulosPrograma - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Validar datos del programa
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        // Validar código del programa (solo en creación)
        if (!$esActualizacion) {
            if (empty($data['prog_codigo'])) {
                $errores['prog_codigo'] = 'El código del programa es requerido';
            } elseif (!is_numeric($data['prog_codigo'])) {
                $errores['prog_codigo'] = 'El código del programa debe ser numérico';
            }
        }

        // Validar denominación del programa
        if (empty($data['prog_denominacion'])) {
            $errores['prog_denominacion'] = 'La denominación del programa es requerida';
        } elseif (strlen($data['prog_denominacion']) > 100) {
            $errores['prog_denominacion'] = 'La denominación del programa no puede tener más de 100 caracteres';
        }

        // Validar título de programa
        if (empty($data['TIT_PROGRAMA_titpro_id'])) {
            $errores['TIT_PROGRAMA_titpro_id'] = 'Debe seleccionar un título de programa';
        }

        // Validar tipo de programa
        if (empty($data['prog_tipo'])) {
            $errores['prog_tipo'] = 'El tipo de programa es requerido';
        } elseif (strlen($data['prog_tipo']) > 30) {
            $errores['prog_tipo'] = 'El tipo de programa no puede tener más de 30 caracteres';
        }

        return $errores;
    }
}
?>
