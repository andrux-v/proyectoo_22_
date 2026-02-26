<?php
/**
 * Controlador de Centro de Formación
 * Maneja todas las operaciones CRUD para centros de formación
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/CentroFormacionModel.php';

class CentroFormacionController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todos los centros de formación
     */
    public function index()
    {
        try {
            $query = "SELECT * FROM centro_formacion ORDER BY cent_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CentroFormacionController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un centro de formación por ID
     */
    public function show($cent_id)
    {
        try {
            $query = "SELECT * FROM centro_formacion WHERE cent_id = :cent_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':cent_id' => $cent_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CentroFormacionController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear un nuevo centro de formación
     */
    public function create($data)
    {
        try {
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Hashear la contraseña
            $password = isset($data['cent_password']) && !empty($data['cent_password']) 
                ? password_hash($data['cent_password'], PASSWORD_DEFAULT) 
                : null;

            $centro = new CentroFormacionModel(
                null, 
                $data['cent_nombre'],
                $data['cent_correo'],
                $password
            );
            $cent_id = $centro->create();

            return ['success' => true, 'mensaje' => 'Centro de formación creado exitosamente', 'cent_id' => $cent_id];
        } catch (PDOException $e) {
            error_log("Error en CentroFormacionController::create - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Verificar si es error de clave duplicada
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'cent_correo') !== false) {
                    return ['success' => false, 'errores' => ['cent_correo' => 'El correo ya está registrado']];
                }
                return ['success' => false, 'errores' => ['cent_nombre' => 'Ya existe un centro con este nombre']];
            }
            
            return ['success' => false, 'errores' => ['general' => 'Error al crear el centro de formación: ' . $e->getMessage()]];
        } catch (Exception $e) {
            error_log("Error general en CentroFormacionController::create - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error inesperado: ' . $e->getMessage()]];
        }
    }

    /**
     * Actualizar un centro de formación existente
     */
    public function update($data)
    {
        try {
            $errores = $this->validar($data, true);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            $centro = new CentroFormacionModel($data['cent_id'], $data['cent_nombre']);
            $centro->update();

            return ['success' => true, 'mensaje' => 'Centro de formación actualizado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en CentroFormacionController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar el centro de formación']];
        }
    }

    /**
     * Eliminar un centro de formación
     */
    public function delete($cent_id)
    {
        try {
            // Verificar si tiene coordinaciones asociadas
            $query = "SELECT COUNT(*) as count FROM coordinacion WHERE centro_formacion_cent_id = :cent_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':cent_id' => $cent_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el centro porque tiene coordinaciones asociadas'];
            }

            $centro = new CentroFormacionModel($cent_id, '');
            $centro->delete();

            return ['success' => true, 'mensaje' => 'Centro de formación eliminado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en CentroFormacionController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar el centro de formación'];
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreate()
    {
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/centro_formacion/crear.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function showEdit($cent_id)
    {
        $centro = $this->show($cent_id);
        
        if (!$centro) {
            header('Location: index.php?error=' . urlencode('Centro de formación no encontrado'));
            exit;
        }
        
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/centro_formacion/editar.php';
    }

    /**
     * Validar datos del centro de formación
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        if ($esActualizacion && empty($data['cent_id'])) {
            $errores['cent_id'] = 'El ID del centro es requerido';
        }

        if (empty($data['cent_nombre'])) {
            $errores['cent_nombre'] = 'El nombre del centro es requerido';
        } elseif (strlen($data['cent_nombre']) > 100) {
            $errores['cent_nombre'] = 'El nombre no puede tener más de 100 caracteres';
        }

        // Validar correo
        if (empty($data['cent_correo'])) {
            $errores['cent_correo'] = 'El correo es requerido';
        } elseif (!filter_var($data['cent_correo'], FILTER_VALIDATE_EMAIL)) {
            $errores['cent_correo'] = 'El correo no es válido';
        } elseif (strlen($data['cent_correo']) > 100) {
            $errores['cent_correo'] = 'El correo no puede tener más de 100 caracteres';
        }

        // Validar contraseña (solo si se proporciona)
        if (!$esActualizacion) {
            // En creación, la contraseña es obligatoria
            if (empty($data['cent_password'])) {
                $errores['cent_password'] = 'La contraseña es requerida';
            } elseif (strlen($data['cent_password']) < 6) {
                $errores['cent_password'] = 'La contraseña debe tener al menos 6 caracteres';
            }
        } else {
            // En actualización, solo validar si se proporciona
            if (isset($data['cent_password']) && !empty($data['cent_password']) && strlen($data['cent_password']) < 6) {
                $errores['cent_password'] = 'La contraseña debe tener al menos 6 caracteres';
            }
        }

        return $errores;
    }

    /**
     * Autenticar centro de formación
     */
    public function autenticar($correo, $password)
    {
        try {
            $query = "SELECT cent_id, cent_nombre, cent_correo, cent_password 
                      FROM centro_formacion WHERE cent_correo = :correo";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':correo' => $correo]);
            $centro = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($centro && password_verify($password, $centro['cent_password'])) {
                // No devolver la contraseña
                unset($centro['cent_password']);
                return ['success' => true, 'centro' => $centro];
            }

            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        } catch (PDOException $e) {
            error_log("Error en CentroFormacionController::autenticar - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al autenticar'];
        }
    }
}
?>
