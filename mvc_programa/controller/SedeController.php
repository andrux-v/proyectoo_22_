<?php
/**
 * Controlador de Sede
 * Maneja todas las operaciones CRUD para sedes
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/SedeModel.php';

class SedeController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todas las sedes
     */
    public function index()
    {
        try {
            $query = "SELECT * FROM sede ORDER BY sede_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en SedeController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una sede por ID
     */
    public function show($sede_id)
    {
        try {
            $query = "SELECT * FROM sede WHERE sede_id = :sede_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':sede_id' => $sede_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en SedeController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear una nueva sede
     */
    public function create($data)
    {
        try {
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            $sede = new SedeModel(null, $data['sede_nombre']);
            $sede_id = $sede->create();

            return ['success' => true, 'mensaje' => 'Sede creada exitosamente', 'sede_id' => $sede_id];
        } catch (PDOException $e) {
            error_log("Error en SedeController::create - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al crear la sede']];
        }
    }

    /**
     * Actualizar una sede existente
     */
    public function update($data)
    {
        try {
            $errores = $this->validar($data, true);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            $sede = new SedeModel($data['sede_id'], $data['sede_nombre']);
            $sede->update();

            return ['success' => true, 'mensaje' => 'Sede actualizada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en SedeController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar la sede']];
        }
    }

    /**
     * Eliminar una sede
     */
    public function delete($sede_id)
    {
        try {
            // Verificar si tiene ambientes asociados
            $query = "SELECT COUNT(*) as count FROM ambiente WHERE sede_sede_id = :sede_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':sede_id' => $sede_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar la sede porque tiene ambientes asociados'];
            }

            $sede = new SedeModel($sede_id, '');
            $sede->delete();

            return ['success' => true, 'mensaje' => 'Sede eliminada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en SedeController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar la sede'];
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreate()
    {
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/sede/crear.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function showEdit($sede_id)
    {
        $sede = $this->show($sede_id);
        
        if (!$sede) {
            header('Location: index.php?error=' . urlencode('Sede no encontrada'));
            exit;
        }
        
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/sede/editar.php';
    }

    /**
     * Validar datos de la sede
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        if ($esActualizacion && empty($data['sede_id'])) {
            $errores['sede_id'] = 'El ID de la sede es requerido';
        }

        if (empty($data['sede_nombre'])) {
            $errores['sede_nombre'] = 'El nombre de la sede es requerido';
        } elseif (strlen($data['sede_nombre']) > 100) {
            $errores['sede_nombre'] = 'El nombre no puede tener más de 100 caracteres';
        }

        return $errores;
    }
}
?>
