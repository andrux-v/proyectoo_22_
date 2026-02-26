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
            $query = "SELECT p.prog_codigo, p.prog_denominacion, p.tit_programa_titpro_id, 
                             p.prog_tipo
                      FROM programa p 
                      ORDER BY p.prog_denominacion ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $programas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtener niveles de formación
            $niveles = $this->getTitulosPrograma();
            $nivelesMap = [];
            foreach ($niveles as $nivel) {
                $nivelesMap[$nivel['titpro_id']] = $nivel['titpro_nombre'];
            }
            
            // Agregar el nombre del nivel a cada programa
            foreach ($programas as &$programa) {
                $programa['titpro_nombre'] = $nivelesMap[$programa['tit_programa_titpro_id']] ?? 'N/A';
            }
            
            return $programas;
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
            $query = "SELECT p.prog_codigo, p.prog_denominacion, p.tit_programa_titpro_id, 
                             p.prog_tipo
                      FROM programa p 
                      WHERE p.prog_codigo = :prog_codigo";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':prog_codigo' => $prog_codigo]);
            $programa = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($programa) {
                // Obtener niveles de formación
                $niveles = $this->getTitulosPrograma();
                foreach ($niveles as $nivel) {
                    if ($nivel['titpro_id'] == $programa['tit_programa_titpro_id']) {
                        $programa['titpro_nombre'] = $nivel['titpro_nombre'];
                        break;
                    }
                }
            }
            
            return $programa;
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
                $data['tit_programa_titpro_id'],
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
                $data['tit_programa_titpro_id'],
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
            $query = "SELECT COUNT(*) as count FROM ficha WHERE programa_prog_id = :prog_codigo";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':prog_codigo' => $prog_codigo]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar el programa porque tiene fichas asociadas'];
            }

            // Verificar si tiene competencias asociadas
            $query = "SELECT COUNT(*) as count FROM competxprograma WHERE programa_prog_id = :prog_codigo";
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
     * Obtener todos los títulos de programa (niveles de formación SENA)
     */
    public function getTitulosPrograma()
    {
        // Niveles de formación fijos del SENA
        return [
            ['titpro_id' => 1, 'titpro_nombre' => 'Auxiliar'],
            ['titpro_id' => 2, 'titpro_nombre' => 'Operario'],
            ['titpro_id' => 3, 'titpro_nombre' => 'Técnico'],
            ['titpro_id' => 4, 'titpro_nombre' => 'Tecnólogo'],
            ['titpro_id' => 5, 'titpro_nombre' => 'Especialización Tecnológica']
        ];
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
        if (empty($data['tit_programa_titpro_id'])) {
            $errores['tit_programa_titpro_id'] = 'Debe seleccionar un título de programa';
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
