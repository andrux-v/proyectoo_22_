<?php
/**
 * Controlador de Título de Programa
 * Maneja todas las operaciones CRUD para títulos de programa
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/TituloProgramaModel.php';

class TituloProgramaController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todos los títulos de programa
     */
    public function index()
    {
        try {
            $query = "SELECT * FROM titulo_programa ORDER BY titpro_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en TituloProgramaController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un título de programa por ID
     */
    public function show($titpro_id)
    {
        try {
            $query = "SELECT * FROM titulo_programa WHERE titpro_id = :titpro_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':titpro_id' => $titpro_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en TituloProgramaController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear un nuevo título de programa
     */
    public function create($data)
    {
        try {
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            $titulo = new TituloProgramaModel(null, $data['titpro_nombre']);
            $titpro_id = $titulo->create();

            return ['success' => true, 'mensaje' => 'Título de programa creado exitosamente', 'titpro_id' => $titpro_id];
        } catch (PDOException $e) {
            error_log("Error en TituloProgramaController::create - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al crear el título de programa']];
        }
    }

    /**
     * Actualizar un título de programa existente
     */
    public function update($data)
    {
        try {
            $errores = $this->validar($data, true);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            $titulo = new TituloProgramaModel($data['titpro_id'], $data['titpro_nombre']);
            $titulo->update();

            return ['success' => true, 'mensaje' => 'Título de programa actualizado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en TituloProgramaController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar el título de programa']];
        }
    }

    /**
     * Eliminar un título de programa
     */
    public function delete($titpro_id)
    {
        try {
            // Verificar si tiene programas asociados
            $query = "SELECT COUNT(*) as count FROM programa WHERE tit_programa_titpro_id = :titpro_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':titpro_id' => $titpro_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el título porque tiene programas asociados'];
            }

            $titulo = new TituloProgramaModel($titpro_id, '');
            $titulo->delete();

            return ['success' => true, 'mensaje' => 'Título de programa eliminado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en TituloProgramaController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar el título de programa'];
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreate()
    {
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/titulo_programa/crear.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function showEdit($titpro_id)
    {
        $titulo = $this->show($titpro_id);
        
        if (!$titulo) {
            header('Location: index.php?error=' . urlencode('Título de programa no encontrado'));
            exit;
        }
        
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/titulo_programa/editar.php';
    }

    /**
     * Validar datos del título de programa
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        if ($esActualizacion && empty($data['titpro_id'])) {
            $errores['titpro_id'] = 'El ID del título es requerido';
        }

        if (empty($data['titpro_nombre'])) {
            $errores['titpro_nombre'] = 'El nombre del título es requerido';
        } elseif (strlen($data['titpro_nombre']) > 100) {
            $errores['titpro_nombre'] = 'El nombre no puede tener más de 100 caracteres';
        }

        return $errores;
    }
}
?>
