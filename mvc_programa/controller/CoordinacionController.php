<?php
/**
 * Controlador de Coordinación
 * Maneja todas las operaciones CRUD para coordinaciones
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/CoordinacionModel.php';

class CoordinacionController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todas las coordinaciones con información del centro
     */
    public function index()
    {
        try {
            $query = "SELECT c.coord_id, c.coord_descripcion, c.centro_formacion_cent_id,
                             c.coord_nombre_coordinador, c.coord_correo,
                             cf.cent_nombre
                      FROM coordinacion c
                      LEFT JOIN centro_formacion cf ON c.centro_formacion_cent_id = cf.cent_id
                      ORDER BY c.coord_descripcion ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CoordinacionController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una coordinación por ID
     */
    public function show($coord_id)
    {
        try {
            $query = "SELECT c.coord_id, c.coord_descripcion, c.centro_formacion_cent_id,
                             c.coord_nombre_coordinador, c.coord_correo,
                             cf.cent_nombre
                      FROM coordinacion c
                      LEFT JOIN centro_formacion cf ON c.centro_formacion_cent_id = cf.cent_id
                      WHERE c.coord_id = :coord_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':coord_id' => $coord_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CoordinacionController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear una nueva coordinación
     */
    public function create($data)
    {
        try {
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Hash de la contraseña si se proporciona
            $password = !empty($data['coord_password']) ? password_hash($data['coord_password'], PASSWORD_DEFAULT) : null;

            $coordinacion = new CoordinacionModel(
                null,
                $data['coord_descripcion'],
                $data['centro_formacion_cent_id'],
                $data['coord_nombre_coordinador'] ?? null,
                $data['coord_correo'] ?? null,
                $password
            );

            $coord_id = $coordinacion->create();

            return ['success' => true, 'mensaje' => 'Coordinación creada exitosamente', 'coord_id' => $coord_id];
        } catch (PDOException $e) {
            error_log("Error en CoordinacionController::create - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al crear la coordinación']];
        }
    }

    /**
     * Actualizar una coordinación existente
     */
    public function update($data)
    {
        try {
            $errores = $this->validar($data, true);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Si se proporciona nueva contraseña, hashearla
            $password = null;
            if (!empty($data['coord_password'])) {
                $password = password_hash($data['coord_password'], PASSWORD_DEFAULT);
            } else {
                // Mantener la contraseña actual
                $current = $this->show($data['coord_id']);
                $password = $current['coord_password'] ?? null;
            }

            $coordinacion = new CoordinacionModel(
                $data['coord_id'],
                $data['coord_descripcion'],
                $data['centro_formacion_cent_id'],
                $data['coord_nombre_coordinador'] ?? null,
                $data['coord_correo'] ?? null,
                $password
            );

            $coordinacion->update();

            return ['success' => true, 'mensaje' => 'Coordinación actualizada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en CoordinacionController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar la coordinación']];
        }
    }

    /**
     * Eliminar una coordinación
     */
    public function delete($coord_id)
    {
        try {
            // Verificar si tiene fichas asociadas
            $query = "SELECT COUNT(*) as count FROM ficha WHERE coordinacion_coord_id = :coord_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':coord_id' => $coord_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar la coordinación porque tiene fichas asociadas'];
            }

            $coordinacion = new CoordinacionModel($coord_id, '', '');
            $coordinacion->delete();

            return ['success' => true, 'mensaje' => 'Coordinación eliminada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en CoordinacionController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar la coordinación'];
        }
    }

    /**
     * Obtener todos los centros de formación para el select
     */
    public function getCentrosFormacion()
    {
        try {
            $query = "SELECT cent_id, cent_nombre FROM centro_formacion ORDER BY cent_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CoordinacionController::getCentrosFormacion - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreate()
    {
        $centros = $this->getCentrosFormacion();
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/coordinacion/crear.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function showEdit($coord_id)
    {
        $coordinacion = $this->show($coord_id);
        
        if (!$coordinacion) {
            header('Location: index.php?error=' . urlencode('Coordinación no encontrada'));
            exit;
        }
        
        $centros = $this->getCentrosFormacion();
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/coordinacion/editar.php';
    }

    /**
     * Validar datos de la coordinación
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        if ($esActualizacion && empty($data['coord_id'])) {
            $errores['coord_id'] = 'El ID de la coordinación es requerido';
        }

        if (empty($data['coord_descripcion'])) {
            $errores['coord_descripcion'] = 'La descripción es requerida';
        } elseif (strlen($data['coord_descripcion']) > 45) {
            $errores['coord_descripcion'] = 'La descripción no puede tener más de 45 caracteres';
        }

        if (empty($data['centro_formacion_cent_id'])) {
            $errores['centro_formacion_cent_id'] = 'Debe seleccionar un centro de formación';
        }

        // Validar nombre del coordinador
        if (empty($data['coord_nombre_coordinador'])) {
            $errores['coord_nombre_coordinador'] = 'El nombre del coordinador es requerido';
        } elseif (strlen($data['coord_nombre_coordinador']) > 100) {
            $errores['coord_nombre_coordinador'] = 'El nombre no puede tener más de 100 caracteres';
        }

        // Validar correo del coordinador
        if (empty($data['coord_correo'])) {
            $errores['coord_correo'] = 'El correo del coordinador es requerido';
        } elseif (!filter_var($data['coord_correo'], FILTER_VALIDATE_EMAIL)) {
            $errores['coord_correo'] = 'El correo electrónico no es válido';
        } elseif (strlen($data['coord_correo']) > 100) {
            $errores['coord_correo'] = 'El correo no puede tener más de 100 caracteres';
        } else {
            // Verificar que el correo no esté duplicado
            $query = "SELECT coord_id FROM coordinacion WHERE coord_correo = :correo";
            if ($esActualizacion) {
                $query .= " AND coord_id != :coord_id";
            }
            $stmt = $this->db->prepare($query);
            $params = [':correo' => $data['coord_correo']];
            if ($esActualizacion) {
                $params[':coord_id'] = $data['coord_id'];
            }
            $stmt->execute($params);
            if ($stmt->fetch()) {
                $errores['coord_correo'] = 'Este correo ya está registrado';
            }
        }

        // Validar contraseña (solo en creación o si se proporciona en actualización)
        if (!$esActualizacion) {
            if (empty($data['coord_password'])) {
                $errores['coord_password'] = 'La contraseña es requerida';
            } elseif (strlen($data['coord_password']) < 6) {
                $errores['coord_password'] = 'La contraseña debe tener al menos 6 caracteres';
            }
        } else {
            // En actualización, solo validar si se proporciona
            if (!empty($data['coord_password']) && strlen($data['coord_password']) < 6) {
                $errores['coord_password'] = 'La contraseña debe tener al menos 6 caracteres';
            }
        }

        return $errores;
    }
}
?>
