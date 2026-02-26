<?php
/**
 * Controlador de Instructor
 * Maneja todas las operaciones CRUD para instructores
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/InstructorModel.php';

class InstructorController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todos los instructores con información del centro de formación
     */
    public function index()
    {
        try {
            $query = "SELECT i.inst_id, i.inst_nombres, i.inst_apellidos, i.inst_correo, 
                             i.inst_telefono, i.CENTRO_FORMACION_cent_id, c.cent_nombre 
                      FROM instructor i 
                      LEFT JOIN centro_formacion c ON i.CENTRO_FORMACION_cent_id = c.cent_id 
                      ORDER BY i.inst_apellidos ASC, i.inst_nombres ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en InstructorController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un instructor por ID
     */
    public function show($inst_id)
    {
        try {
            $query = "SELECT i.inst_id, i.inst_nombres, i.inst_apellidos, i.inst_correo, 
                             i.inst_telefono, i.CENTRO_FORMACION_cent_id, c.cent_nombre 
                      FROM instructor i 
                      LEFT JOIN centro_formacion c ON i.CENTRO_FORMACION_cent_id = c.cent_id 
                      WHERE i.inst_id = :inst_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':inst_id' => $inst_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en InstructorController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear un nuevo instructor
     */
    public function create($data)
    {
        try {
            // Validar datos
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Debug: Log de la contraseña recibida
            error_log("=== DEBUG CREATE INSTRUCTOR ===");
            error_log("Password recibida: " . ($data['inst_password'] ?? 'NO RECIBIDA'));
            
            // Hashear la contraseña si se proporciona
            $password = isset($data['inst_password']) && !empty($data['inst_password']) 
                ? password_hash($data['inst_password'], PASSWORD_DEFAULT) 
                : null;
            
            error_log("Password hasheada: " . ($password ?? 'NULL'));

            // Crear el instructor usando el modelo
            $instructor = new InstructorModel(
                null, // El ID se genera automáticamente
                $data['inst_nombres'],
                $data['inst_apellidos'],
                $data['inst_correo'],
                $data['inst_telefono'],
                $data['CENTRO_FORMACION_cent_id'],
                $password
            );

            $inst_id = $instructor->create();
            
            error_log("Instructor creado con ID: $inst_id");
            error_log("=== FIN DEBUG ===");

            return ['success' => true, 'mensaje' => 'Instructor creado exitosamente', 'inst_id' => $inst_id];
        } catch (PDOException $e) {
            error_log("Error en InstructorController::create - " . $e->getMessage());
            
            // Verificar si es error de clave duplicada
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'inst_correo') !== false) {
                    return ['success' => false, 'errores' => ['inst_correo' => 'El correo ya está registrado']];
                }
                return ['success' => false, 'errores' => ['general' => 'El instructor ya existe']];
            }
            
            return ['success' => false, 'errores' => ['general' => 'Error al crear el instructor']];
        }
    }

    /**
     * Actualizar un instructor existente
     */
    public function update($data)
    {
        try {
            // Validar datos
            $errores = $this->validar($data, true);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Obtener el instructor actual para mantener la contraseña si no se cambia
            $instructorActual = $this->show($data['inst_id']);
            
            // Si se proporciona nueva contraseña, hashearla; si no, mantener la actual
            $password = null;
            if (isset($data['inst_password']) && !empty($data['inst_password'])) {
                $password = password_hash($data['inst_password'], PASSWORD_DEFAULT);
            } elseif ($instructorActual) {
                // Obtener la contraseña actual de la base de datos
                $query = "SELECT inst_password FROM instructor WHERE inst_id = :inst_id";
                $stmt = $this->db->prepare($query);
                $stmt->execute([':inst_id' => $data['inst_id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $password = $result['inst_password'];
            }

            // Actualizar usando el modelo
            $instructor = new InstructorModel(
                $data['inst_id'],
                $data['inst_nombres'],
                $data['inst_apellidos'],
                $data['inst_correo'],
                $data['inst_telefono'],
                $data['CENTRO_FORMACION_cent_id'],
                $password
            );

            $instructor->update();

            return ['success' => true, 'mensaje' => 'Instructor actualizado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en InstructorController::update - " . $e->getMessage());
            
            // Verificar si es error de clave duplicada
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'inst_correo') !== false) {
                    return ['success' => false, 'errores' => ['inst_correo' => 'El correo ya está registrado']];
                }
            }
            
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar el instructor']];
        }
    }

    /**
     * Eliminar un instructor
     */
    public function delete($inst_id)
    {
        try {
            // Verificar si el instructor está siendo usado como líder en fichas
            $query = "SELECT COUNT(*) as count FROM ficha WHERE INSTRUCTOR_inst_id_lider = :inst_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':inst_id' => $inst_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el instructor porque es líder de una o más fichas'];
            }

            // Verificar si tiene asignaciones
            $query = "SELECT COUNT(*) as count FROM asignacion WHERE INSTRUCTOR_inst_id = :inst_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':inst_id' => $inst_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el instructor porque tiene asignaciones asociadas'];
            }

            // Verificar si tiene competencias asociadas
            $query = "SELECT COUNT(*) as count FROM instru_competencia WHERE INSTRUCTOR_inst_id = :inst_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':inst_id' => $inst_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el instructor porque tiene competencias asociadas'];
            }

            // Eliminar usando el modelo
            $instructor = new InstructorModel($inst_id, '', '', '', '', '', '');
            $instructor->delete();

            return ['success' => true, 'mensaje' => 'Instructor eliminado exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en InstructorController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar el instructor'];
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
            error_log("Error en InstructorController::getCentrosFormacion - " . $e->getMessage());
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
        include __DIR__ . '/../views/instructor/crear.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function showEdit($inst_id)
    {
        $instructor = $this->show($inst_id);
        
        if (!$instructor) {
            header('Location: index.php?error=' . urlencode('Instructor no encontrado'));
            exit;
        }
        
        $centros = $this->getCentrosFormacion();
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/instructor/editar.php';
    }

    /**
     * Validar datos del instructor
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        // Validar nombres
        if (empty($data['inst_nombres'])) {
            $errores['inst_nombres'] = 'Los nombres son requeridos';
        } elseif (strlen($data['inst_nombres']) > 45) {
            $errores['inst_nombres'] = 'Los nombres no pueden tener más de 45 caracteres';
        }

        // Validar apellidos
        if (empty($data['inst_apellidos'])) {
            $errores['inst_apellidos'] = 'Los apellidos son requeridos';
        } elseif (strlen($data['inst_apellidos']) > 45) {
            $errores['inst_apellidos'] = 'Los apellidos no pueden tener más de 45 caracteres';
        }

        // Validar correo
        if (empty($data['inst_correo'])) {
            $errores['inst_correo'] = 'El correo es requerido';
        } elseif (!filter_var($data['inst_correo'], FILTER_VALIDATE_EMAIL)) {
            $errores['inst_correo'] = 'El correo no es válido';
        } elseif (strlen($data['inst_correo']) > 45) {
            $errores['inst_correo'] = 'El correo no puede tener más de 45 caracteres';
        }

        // Validar teléfono
        if (empty($data['inst_telefono'])) {
            $errores['inst_telefono'] = 'El teléfono es requerido';
        } elseif (!is_numeric($data['inst_telefono'])) {
            $errores['inst_telefono'] = 'El teléfono debe ser numérico';
        } elseif (strlen($data['inst_telefono']) > 10) {
            $errores['inst_telefono'] = 'El teléfono no puede tener más de 10 dígitos';
        }

        // Validar centro de formación
        if (empty($data['CENTRO_FORMACION_cent_id'])) {
            $errores['CENTRO_FORMACION_cent_id'] = 'Debe seleccionar un centro de formación';
        }

        // Validar contraseña (solo si se proporciona)
        if (!$esActualizacion) {
            // En creación, la contraseña es obligatoria
            if (empty($data['inst_password'])) {
                $errores['inst_password'] = 'La contraseña es requerida';
            } elseif (strlen($data['inst_password']) < 6) {
                $errores['inst_password'] = 'La contraseña debe tener al menos 6 caracteres';
            }
        } else {
            // En actualización, solo validar si se proporciona
            if (isset($data['inst_password']) && !empty($data['inst_password']) && strlen($data['inst_password']) < 6) {
                $errores['inst_password'] = 'La contraseña debe tener al menos 6 caracteres';
            }
        }

        return $errores;
    }

    /**
     * Autenticar instructor
     */
    public function autenticar($correo, $password)
    {
        try {
            $query = "SELECT inst_id, inst_nombres, inst_apellidos, inst_correo, inst_password 
                      FROM instructor WHERE inst_correo = :correo";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':correo' => $correo]);
            $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($instructor && password_verify($password, $instructor['inst_password'])) {
                // No devolver la contraseña
                unset($instructor['inst_password']);
                return ['success' => true, 'instructor' => $instructor];
            }

            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        } catch (PDOException $e) {
            error_log("Error en InstructorController::autenticar - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al autenticar'];
        }
    }
}
?>
