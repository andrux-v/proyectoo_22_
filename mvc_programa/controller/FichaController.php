<?php
/**
 * Controlador de Ficha
 * Maneja todas las operaciones CRUD para fichas
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/FichaModel.php';

class FichaController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todas las fichas con información relacionada
     */
    public function index()
    {
        try {
            $query = "SELECT f.fich_id, f.programa_prog_id, f.instructor_inst_id_lider, 
                             f.fich_jornada, f.coordinacion_coord_id, 
                             f.fich_fecha_ini_lectiva, f.fich_fecha_fin_lectiva,
                             p.prog_denominacion,
                             CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_lider,
                             c.coord_descripcion as coord_nombre
                      FROM ficha f 
                      LEFT JOIN programa p ON f.programa_prog_id = p.prog_codigo
                      LEFT JOIN instructor i ON f.instructor_inst_id_lider = i.inst_id
                      LEFT JOIN coordinacion c ON f.coordinacion_coord_id = c.coord_id
                      ORDER BY f.fich_id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en FichaController::index - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una ficha por ID
     */
    public function show($fich_id)
    {
        try {
            $query = "SELECT f.fich_id, f.programa_prog_id, f.instructor_inst_id_lider, 
                             f.fich_jornada, f.coordinacion_coord_id, 
                             f.fich_fecha_ini_lectiva, f.fich_fecha_fin_lectiva,
                             p.prog_denominacion,
                             CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_lider,
                             c.coord_descripcion as coord_nombre
                      FROM ficha f 
                      LEFT JOIN programa p ON f.programa_prog_id = p.prog_codigo
                      LEFT JOIN instructor i ON f.instructor_inst_id_lider = i.inst_id
                      LEFT JOIN coordinacion c ON f.coordinacion_coord_id = c.coord_id
                      WHERE f.fich_id = :fich_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':fich_id' => $fich_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en FichaController::show - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear una nueva ficha
     */
    public function create($data)
    {
        try {
            // Validar datos
            $errores = $this->validar($data);
            if (!empty($errores)) {
                return ['success' => false, 'errores' => $errores];
            }

            // Crear la ficha usando el modelo
            $ficha = new FichaModel(
                $data['fich_id'],
                $data['PROGRAMA_prog_id'],
                $data['INSTRUCTOR_inst_id_lider'],
                $data['fich_jornada'],
                $data['COORDINACION_coord_id'],
                $data['fich_fecha_ini_lectiva'] ?? null,
                $data['fich_fecha_fin_lectiva'] ?? null
            );

            $fich_id = $ficha->create();

            return ['success' => true, 'mensaje' => 'Ficha creada exitosamente', 'fich_id' => $fich_id];
        } catch (PDOException $e) {
            error_log("Error en FichaController::create - " . $e->getMessage());
            
            // Verificar si es error de clave duplicada
            if ($e->getCode() == 23000) {
                return ['success' => false, 'errores' => ['fich_id' => 'El número de ficha ya existe']];
            }
            
            return ['success' => false, 'errores' => ['general' => 'Error al crear la ficha']];
        }
    }

    /**
     * Actualizar una ficha existente
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
            $ficha = new FichaModel(
                $data['fich_id'],
                $data['PROGRAMA_prog_id'],
                $data['INSTRUCTOR_inst_id_lider'],
                $data['fich_jornada'],
                $data['COORDINACION_coord_id'],
                $data['fich_fecha_ini_lectiva'] ?? null,
                $data['fich_fecha_fin_lectiva'] ?? null
            );

            $ficha->update();

            return ['success' => true, 'mensaje' => 'Ficha actualizada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en FichaController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar la ficha']];
        }
    }

    /**
     * Eliminar una ficha
     */
    public function delete($fich_id)
    {
        try {
            // Verificar si la ficha tiene asignaciones
            $query = "SELECT COUNT(*) as count FROM asignacion WHERE ficha_fich_id = :fich_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':fich_id' => $fich_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar la ficha porque tiene asignaciones asociadas'];
            }

            // Eliminar usando el modelo
            $ficha = new FichaModel($fich_id, '', '', '', '');
            $ficha->delete();

            return ['success' => true, 'mensaje' => 'Ficha eliminada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error en FichaController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar la ficha'];
        }
    }

    /**
     * Obtener todos los programas para el select
     */
    public function getProgramas()
    {
        try {
            $query = "SELECT prog_codigo, prog_denominacion FROM programa ORDER BY prog_denominacion ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en FichaController::getProgramas - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todos los instructores para el select
     */
    public function getInstructores()
    {
        try {
            $query = "SELECT inst_id, CONCAT(inst_nombres, ' ', inst_apellidos) as nombre_completo 
                      FROM instructor 
                      ORDER BY inst_nombres ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en FichaController::getInstructores - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todas las coordinaciones para el select
     */
    public function getCoordinaciones()
    {
        try {
            $query = "SELECT coord_id, coord_descripcion as coord_nombre FROM coordinacion ORDER BY coord_descripcion ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en FichaController::getCoordinaciones - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreate()
    {
        $programas = $this->getProgramas();
        $instructores = $this->getInstructores();
        $coordinaciones = $this->getCoordinaciones();
        
        // Detectar rol
        $rol = $_GET['rol'] ?? 'coordinador';
        
        // Incluir la vista
        include __DIR__ . '/../views/ficha/crear.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function showEdit($fich_id)
    {
        $ficha = $this->show($fich_id);
        
        if (!$ficha) {
            header('Location: index.php?error=' . urlencode('Ficha no encontrada'));
            exit;
        }
        
        $programas = $this->getProgramas();
        $instructores = $this->getInstructores();
        $coordinaciones = $this->getCoordinaciones();
        
        // Detectar rol
        $rol = $_GET['rol'] ?? 'coordinador';
        
        // Incluir la vista
        include __DIR__ . '/../views/ficha/editar.php';
    }

    /**
     * Validar datos de la ficha
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        // Validar ID de ficha (solo en actualización)
        if ($esActualizacion) {
            if (empty($data['fich_id'])) {
                $errores['fich_id'] = 'El ID de la ficha es requerido';
            }
        } else {
            // En creación, validar que se proporcione el ID
            if (empty($data['fich_id'])) {
                $errores['fich_id'] = 'El número de ficha es requerido';
            }
        }

        // Validar programa
        if (empty($data['PROGRAMA_prog_id'])) {
            $errores['PROGRAMA_prog_id'] = 'Debe seleccionar un programa';
        }

        // Validar instructor líder
        if (empty($data['INSTRUCTOR_inst_id_lider'])) {
            $errores['INSTRUCTOR_inst_id_lider'] = 'Debe seleccionar un instructor líder';
        }

        // Validar jornada
        if (empty($data['fich_jornada'])) {
            $errores['fich_jornada'] = 'La jornada es requerida';
        } elseif (!in_array($data['fich_jornada'], ['Diurna', 'Nocturna', 'Mixta', 'Fin de semana'])) {
            $errores['fich_jornada'] = 'La jornada debe ser: Diurna, Nocturna, Mixta o Fin de semana';
        }

        // Validar coordinación
        if (empty($data['COORDINACION_coord_id'])) {
            $errores['COORDINACION_coord_id'] = 'Debe seleccionar una coordinación';
        }

        // Validar fechas si están presentes
        if (!empty($data['fich_fecha_ini_lectiva']) && !empty($data['fich_fecha_fin_lectiva'])) {
            $fecha_ini = strtotime($data['fich_fecha_ini_lectiva']);
            $fecha_fin = strtotime($data['fich_fecha_fin_lectiva']);
            
            if ($fecha_ini >= $fecha_fin) {
                $errores['fich_fecha_fin_lectiva'] = 'La fecha de fin debe ser posterior a la fecha de inicio';
            }
        }

        return $errores;
    }
}
?>
