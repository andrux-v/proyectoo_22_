<?php
/**
 * Controlador de Competencia
 * Maneja todas las operaciones CRUD para competencias
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/CompetenciaModel.php';

class CompetenciaController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todas las competencias
     */
    public function index()
    {
        try {
            $query = "SELECT comp_id, comp_nombre_corto, comp_horas, comp_nombre_unidad_competencia 
                      FROM competencia 
                      ORDER BY comp_nombre_corto ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CompetenciaController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una competencia por ID
     */
    public function show($comp_id)
    {
        try {
            $query = "SELECT comp_id, comp_nombre_corto, comp_horas, comp_nombre_unidad_competencia 
                      FROM competencia 
                      WHERE comp_id = :comp_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':comp_id' => $comp_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CompetenciaController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear una nueva competencia
     */
    public function create($data)
    {
        try {
            // Validar datos
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Crear la competencia usando el modelo
            $competencia = new CompetenciaModel(
                null, // El ID se genera automáticamente
                $data['comp_nombre_corto'],
                $data['comp_horas'],
                $data['comp_nombre_unidad_competencia']
            );

            $competencia->create();

            return ['success' => true, 'mensaje' => 'Competencia creada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en CompetenciaController::create - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al crear la competencia']];
        }
    }

    /**
     * Actualizar una competencia existente
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
            $competencia = new CompetenciaModel(
                $data['comp_id'],
                $data['comp_nombre_corto'],
                $data['comp_horas'],
                $data['comp_nombre_unidad_competencia']
            );

            $competencia->update();

            return ['success' => true, 'mensaje' => 'Competencia actualizada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en CompetenciaController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar la competencia']];
        }
    }

    /**
     * Eliminar una competencia
     */
    public function delete($comp_id)
    {
        try {
            // Verificar si la competencia está siendo usada en asignaciones
            $query = "SELECT COUNT(*) as count FROM asignacion WHERE COMPETENCIA_comp_id = :comp_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':comp_id' => $comp_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar la competencia porque tiene asignaciones asociadas'];
            }

            // Verificar si está asociada a programas
            $query = "SELECT COUNT(*) as count FROM competxprograma WHERE COMPETENCIA_comp_id = :comp_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':comp_id' => $comp_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar la competencia porque está asociada a programas'];
            }

            // Eliminar usando el modelo
            $competencia = new CompetenciaModel($comp_id, '', '', '');
            $competencia->delete();

            return ['success' => true, 'mensaje' => 'Competencia eliminada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en CompetenciaController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar la competencia'];
        }
    }

    /**
     * Validar datos de la competencia
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        // Validar ID (solo en actualización)
        if ($esActualizacion) {
            if (empty($data['comp_id'])) {
                $errores['comp_id'] = 'El ID de la competencia es requerido';
            }
        }

        // Validar nombre corto
        if (empty($data['comp_nombre_corto'])) {
            $errores['comp_nombre_corto'] = 'El nombre corto es requerido';
        } elseif (strlen($data['comp_nombre_corto']) > 30) {
            $errores['comp_nombre_corto'] = 'El nombre corto no puede tener más de 30 caracteres';
        }

        // Validar horas
        if (empty($data['comp_horas'])) {
            $errores['comp_horas'] = 'Las horas son requeridas';
        } elseif (!is_numeric($data['comp_horas']) || $data['comp_horas'] <= 0) {
            $errores['comp_horas'] = 'Las horas deben ser un número mayor a 0';
        }

        // Validar nombre de unidad de competencia
        if (empty($data['comp_nombre_unidad_competencia'])) {
            $errores['comp_nombre_unidad_competencia'] = 'El nombre de la unidad de competencia es requerido';
        } elseif (strlen($data['comp_nombre_unidad_competencia']) > 150) {
            $errores['comp_nombre_unidad_competencia'] = 'El nombre de la unidad de competencia no puede tener más de 150 caracteres';
        }

        return $errores;
    }
}
?>
