<?php
/**
 * Controlador de Ambiente
 * Maneja todas las operaciones CRUD para ambientes
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/AmbienteModel.php';

class AmbienteController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todos los ambientes con información de la sede
     */
    public function index()
    {
        try {
            $query = "SELECT a.amb_id, a.amb_nombre, a.SEDE_sede_id, s.sede_nombre 
                      FROM ambiente a 
                      LEFT JOIN sede s ON a.SEDE_sede_id = s.sede_id 
                      ORDER BY a.amb_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AmbienteController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un ambiente por ID
     */
    public function show($amb_id)
    {
        try {
            $query = "SELECT a.amb_id, a.amb_nombre, a.SEDE_sede_id, s.sede_nombre 
                      FROM ambiente a 
                      LEFT JOIN sede s ON a.SEDE_sede_id = s.sede_id 
                      WHERE a.amb_id = :amb_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':amb_id' => $amb_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AmbienteController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear un nuevo ambiente
     */
    public function create($data)
    {
        try {
            // Validar datos
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Crear el ambiente usando el modelo
            $ambiente = new AmbienteModel(
                $data['amb_id'],
                $data['amb_nombre'],
                $data['SEDE_sede_id']
            );

            $ambiente->create();

            return ['success' => true, 'mensaje' => 'Ambiente creado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en AmbienteController::create - " . $e->getMessage());
            
            // Verificar si es error de clave duplicada
            if ($e->getCode() == 23000) {
                return ['success' => false, 'errores' => ['amb_id' => 'El ID del ambiente ya existe']];
            }
            
            return ['success' => false, 'errores' => ['general' => 'Error al crear el ambiente']];
        }
    }

    /**
     * Actualizar un ambiente existente
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
            $ambiente = new AmbienteModel(
                $data['amb_id'],
                $data['amb_nombre'],
                $data['SEDE_sede_id']
            );

            $ambiente->update();

            return ['success' => true, 'mensaje' => 'Ambiente actualizado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en AmbienteController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar el ambiente']];
        }
    }

    /**
     * Eliminar un ambiente
     */
    public function delete($amb_id)
    {
        try {
            // Verificar si el ambiente está siendo usado en asignaciones
            $query = "SELECT COUNT(*) as count FROM asignacion WHERE AMBIENTE_amb_id = :amb_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':amb_id' => $amb_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el ambiente porque tiene asignaciones asociadas'];
            }

            // Eliminar usando el modelo
            $ambiente = new AmbienteModel($amb_id, '', '');
            $ambiente->delete();

            return ['success' => true, 'mensaje' => 'Ambiente eliminado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en AmbienteController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar el ambiente'];
        }
    }

    /**
     * Obtener todas las sedes para el select
     */
    public function getSedes()
    {
        try {
            $query = "SELECT sede_id, sede_nombre FROM sede ORDER BY sede_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AmbienteController::getSedes - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Validar datos del ambiente
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        // Validar ID del ambiente (solo en creación)
        if (!$esActualizacion) {
            if (empty($data['amb_id'])) {
                $errores['amb_id'] = 'El ID del ambiente es requerido';
            } elseif (strlen($data['amb_id']) > 5) {
                $errores['amb_id'] = 'El ID del ambiente no puede tener más de 5 caracteres';
            }
        }

        // Validar nombre del ambiente
        if (empty($data['amb_nombre'])) {
            $errores['amb_nombre'] = 'El nombre del ambiente es requerido';
        } elseif (strlen($data['amb_nombre']) > 45) {
            $errores['amb_nombre'] = 'El nombre del ambiente no puede tener más de 45 caracteres';
        }

        // Validar sede
        if (empty($data['SEDE_sede_id'])) {
            $errores['SEDE_sede_id'] = 'Debe seleccionar una sede';
        }

        return $errores;
    }
}
?>
