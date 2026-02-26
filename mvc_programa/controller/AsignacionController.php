<?php
/**
 * Controlador de Asignación
 * Maneja todas las operaciones CRUD para asignaciones y sus detalles
 */

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../model/AsignacionModel.php';
require_once __DIR__ . '/../model/DetalleAsignacionModel.php';

class AsignacionController
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConnect();
    }

    /**
     * Listar todas las asignaciones con información relacionada
     */
    public function index()
    {
        try {
            $asignaciones = $this->getAllAsignaciones();
            $rol = $_GET['rol'] ?? 'coordinador';
            include __DIR__ . '/../views/asignacion/index.php';
        } catch (Exception $e) {
            error_log("Error en AsignacionController::index - " . $e->getMessage());
            $asignaciones = [];
            $error = 'Error al cargar las asignaciones';
            $rol = $_GET['rol'] ?? 'coordinador';
            include __DIR__ . '/../views/asignacion/index.php';
        }
    }

    /**
     * Obtener todas las asignaciones con información relacionada
     */
    public function getAllAsignaciones()
    {
        try {
            $query = "SELECT a.asig_id, a.instructor_inst_id, a.asig_fecha_ini, a.asig_fecha_fin,
                             a.ficha_fich_id, a.ambiente_amb_id, a.competencia_comp_id,
                             CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                             f.fich_id as ficha_numero,
                             amb.amb_nombre,
                             c.comp_nombre_corto
                      FROM asignacion a
                      LEFT JOIN instructor i ON a.instructor_inst_id = i.inst_id
                      LEFT JOIN ficha f ON a.ficha_fich_id = f.fich_id
                      LEFT JOIN ambiente amb ON a.ambiente_amb_id = amb.amb_id
                      LEFT JOIN competencia c ON a.competencia_comp_id = c.comp_id
                      ORDER BY a.asig_fecha_ini DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getAllAsignaciones - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener asignaciones de un instructor específico
     */
    public function getAsignacionesByInstructor($instructor_id)
    {
        try {
            $query = "SELECT a.asig_id, a.instructor_inst_id, a.asig_fecha_ini, a.asig_fecha_fin,
                             a.ficha_fich_id, a.ambiente_amb_id, a.competencia_comp_id,
                             CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                             f.fich_id as ficha_numero,
                             amb.amb_nombre,
                             c.comp_nombre_corto
                      FROM asignacion a
                      LEFT JOIN instructor i ON a.instructor_inst_id = i.inst_id
                      LEFT JOIN ficha f ON a.ficha_fich_id = f.fich_id
                      LEFT JOIN ambiente amb ON a.ambiente_amb_id = amb.amb_id
                      LEFT JOIN competencia c ON a.competencia_comp_id = c.comp_id
                      WHERE a.instructor_inst_id = :instructor_id
                      ORDER BY a.asig_fecha_ini DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':instructor_id' => $instructor_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getAsignacionesByInstructor - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener asignaciones en formato calendario
     */
    public function getAsignacionesCalendario()
    {
        try {
            $query = "SELECT a.asig_id, a.asig_fecha_ini, a.asig_fecha_fin,
                             CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                             f.fich_id as ficha_numero,
                             amb.amb_nombre,
                             c.comp_nombre_corto,
                             a.ambiente_amb_id
                      FROM asignacion a
                      LEFT JOIN instructor i ON a.instructor_inst_id = i.inst_id
                      LEFT JOIN ficha f ON a.ficha_fich_id = f.fich_id
                      LEFT JOIN ambiente amb ON a.ambiente_amb_id = amb.amb_id
                      LEFT JOIN competencia c ON a.competencia_comp_id = c.comp_id
                      ORDER BY a.asig_fecha_ini ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Obtener detalles para cada asignación
            foreach ($asignaciones as &$asig) {
                $asig['detalles'] = $this->getDetallesByAsignacion($asig['asig_id']);
            }

            return $asignaciones;
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getAsignacionesCalendario - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener detalles de una asignación
     */
    public function getDetallesByAsignacion($asig_id)
    {
        try {
            $query = "SELECT detasig_id, detasig_hora_ini, detasig_hora_fin
                      FROM detalle_asignacion
                      WHERE asignacion_asig_id = :asig_id
                      ORDER BY detasig_hora_ini ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':asig_id' => $asig_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getDetallesByAsignacion - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una asignación por ID con sus detalles
     */
    public function show($asig_id)
    {
        try {
            $query = "SELECT a.asig_id, a.instructor_inst_id, a.asig_fecha_ini, a.asig_fecha_fin,
                             a.ficha_fich_id, a.ambiente_amb_id, a.competencia_comp_id,
                             CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                             f.fich_id as ficha_numero,
                             amb.amb_nombre,
                             c.comp_nombre_corto
                      FROM asignacion a
                      LEFT JOIN instructor i ON a.instructor_inst_id = i.inst_id
                      LEFT JOIN ficha f ON a.ficha_fich_id = f.fich_id
                      LEFT JOIN ambiente amb ON a.ambiente_amb_id = amb.amb_id
                      LEFT JOIN competencia c ON a.competencia_comp_id = c.comp_id
                      WHERE a.asig_id = :asig_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':asig_id' => $asig_id]);
            $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($asignacion) {
                // Obtener los detalles de horarios
                $asignacion['detalles'] = $this->getDetallesByAsignacion($asig_id);
            }
            
            // Cargar la vista
            $rol = $_GET['rol'] ?? 'coordinador';
            include __DIR__ . '/../views/asignacion/ver.php';
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::show - " . $e->getMessage());
            $rol = $_GET['rol'] ?? 'coordinador';
            $error = 'Error al cargar la asignación';
            include __DIR__ . '/../views/asignacion/ver.php';
        }
    }

    /**
     * Obtener una asignación por ID (sin cargar vista)
     */
    public function getAsignacionById($asig_id)
    {
        try {
            $query = "SELECT a.asig_id, a.instructor_inst_id, a.asig_fecha_ini, a.asig_fecha_fin,
                             a.ficha_fich_id, a.ambiente_amb_id, a.competencia_comp_id,
                             CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                             f.fich_id as ficha_numero,
                             amb.amb_nombre,
                             c.comp_nombre_corto
                      FROM asignacion a
                      LEFT JOIN instructor i ON a.instructor_inst_id = i.inst_id
                      LEFT JOIN ficha f ON a.ficha_fich_id = f.fich_id
                      LEFT JOIN ambiente amb ON a.ambiente_amb_id = amb.amb_id
                      LEFT JOIN competencia c ON a.competencia_comp_id = c.comp_id
                      WHERE a.asig_id = :asig_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':asig_id' => $asig_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getAsignacionById - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear una nueva asignación con sus detalles
     */
    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            $errores = $this->validar($data);
            if (!empty($errores)) {
                $this->db->rollBack();
                return ['success' => false, 'errores' => $errores];
            }

            // Validar carga horaria antes de crear
            $validacionCarga = $this->validarCargaHoraria(
                $data['instructor_inst_id'],
                $data['competencia_comp_id'],
                $data['asig_fecha_ini'],
                $data['asig_fecha_fin']
            );
            
            if (!$validacionCarga['valido']) {
                $this->db->rollBack();
                return ['success' => false, 'errores' => ['carga_horaria' => $validacionCarga['mensaje']]];
            }

            // Establecer usuario para auditoría
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario = $_SESSION['user_correo'] ?? $_SESSION['coordinador_correo'] ?? $_SESSION['centro_correo'] ?? 'sistema';
            $this->db->exec("SET @usuario_actual = '$usuario'");

            // Crear la asignación principal
            $asignacion = new AsignacionModel(
                null,
                $data['instructor_inst_id'],
                $data['asig_fecha_ini'],
                $data['asig_fecha_fin'],
                $data['ficha_fich_id'],
                $data['ambiente_amb_id'],
                $data['competencia_comp_id']
            );

            $asig_id = $asignacion->create();

            // Crear los detalles de asignación si existen
            if (isset($data['detalles']) && is_array($data['detalles'])) {
                foreach ($data['detalles'] as $detalle) {
                    if (!empty($detalle['hora_ini']) && !empty($detalle['hora_fin'])) {
                        $detalleModel = new DetalleAsignacionModel(
                            $asig_id,
                            $detalle['hora_ini'],
                            $detalle['hora_fin'],
                            null
                        );
                        $detalleModel->create();
                    }
                }
            }

            $this->db->commit();
            return ['success' => true, 'mensaje' => 'Asignación creada exitosamente', 'asig_id' => $asig_id];
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en AsignacionController::create - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al crear la asignación']];
        }
    }

    /**
     * Actualizar una asignación existente con sus detalles
     */
    public function update($data)
    {
        try {
            $this->db->beginTransaction();

            $errores = $this->validar($data, true);
            if (!empty($errores)) {
                $this->db->rollBack();
                return ['success' => false, 'errores' => $errores];
            }

            // Validar carga horaria antes de actualizar (excluyendo la asignación actual)
            $validacionCarga = $this->validarCargaHoraria(
                $data['instructor_inst_id'],
                $data['competencia_comp_id'],
                $data['asig_fecha_ini'],
                $data['asig_fecha_fin'],
                $data['asig_id']
            );
            
            if (!$validacionCarga['valido']) {
                $this->db->rollBack();
                return ['success' => false, 'errores' => ['carga_horaria' => $validacionCarga['mensaje']]];
            }

            // Establecer usuario para auditoría
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario = $_SESSION['user_correo'] ?? $_SESSION['coordinador_correo'] ?? $_SESSION['centro_correo'] ?? 'sistema';
            $this->db->exec("SET @usuario_actual = '$usuario'");

            // Actualizar la asignación principal
            $asignacion = new AsignacionModel(
                $data['asig_id'],
                $data['instructor_inst_id'],
                $data['asig_fecha_ini'],
                $data['asig_fecha_fin'],
                $data['ficha_fich_id'],
                $data['ambiente_amb_id'],
                $data['competencia_comp_id']
            );

            $asignacion->update();

            // Eliminar detalles existentes
            $query = "DELETE FROM detalle_asignacion WHERE asignacion_asig_id = :asig_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':asig_id' => $data['asig_id']]);

            // Crear nuevos detalles
            if (isset($data['detalles']) && is_array($data['detalles'])) {
                foreach ($data['detalles'] as $detalle) {
                    if (!empty($detalle['hora_ini']) && !empty($detalle['hora_fin'])) {
                        $detalleModel = new DetalleAsignacionModel(
                            $data['asig_id'],
                            $detalle['hora_ini'],
                            $detalle['hora_fin'],
                            null
                        );
                        $detalleModel->create();
                    }
                }
            }

            $this->db->commit();
            return ['success' => true, 'mensaje' => 'Asignación actualizada exitosamente'];
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en AsignacionController::update - " . $e->getMessage());
            return ['success' => false, 'errores' => ['general' => 'Error al actualizar la asignación']];
        }
    }

    /**
     * Eliminar una asignación y sus detalles
     */
    public function delete($asig_id)
    {
        try {
            $this->db->beginTransaction();

            // Establecer usuario para auditoría
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario = $_SESSION['user_correo'] ?? $_SESSION['coordinador_correo'] ?? $_SESSION['centro_correo'] ?? 'sistema';
            $this->db->exec("SET @usuario_actual = '$usuario'");

            // Eliminar detalles primero (por la clave foránea)
            $query = "DELETE FROM detalle_asignacion WHERE asignacion_asig_id = :asig_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':asig_id' => $asig_id]);

            // Eliminar la asignación (el trigger de auditoría se ejecutará automáticamente)
            $asignacion = new AsignacionModel($asig_id, '', '', '', '', '', '');
            $asignacion->delete();

            $this->db->commit();
            return ['success' => true, 'mensaje' => 'Asignación eliminada exitosamente'];
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en AsignacionController::delete - " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar la asignación'];
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
            error_log("Error en AsignacionController::getInstructores - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todas las fichas para el select
     */
    public function getFichas()
    {
        try {
            $query = "SELECT fich_id FROM ficha ORDER BY fich_id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getFichas - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todos los ambientes para el select
     */
    public function getAmbientes()
    {
        try {
            $query = "SELECT amb_id, amb_nombre FROM ambiente ORDER BY amb_nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getAmbientes - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todas las competencias para el select
     */
    public function getCompetencias()
    {
        try {
            $query = "SELECT comp_id, comp_nombre_corto, comp_nombre_unidad_competencia 
                      FROM competencia 
                      ORDER BY comp_nombre_corto ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en AsignacionController::getCompetencias - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar el formulario
            $data = [
                'instructor_inst_id' => $_POST['INSTRUCTOR_inst_id'] ?? '',
                'ficha_fich_id' => $_POST['FICHA_fich_id'] ?? '',
                'ambiente_amb_id' => $_POST['AMBIENTE_id_ambiente'] ?? '',
                'competencia_comp_id' => $_POST['COMPETENCIA_comp_id'] ?? '',
                'asig_fecha_ini' => $_POST['asig_fecha_ini'] ?? '',
                'asig_fecha_fin' => $_POST['asig_fecha_fin'] ?? '',
                'detalles' => []
            ];

            // Procesar detalles de horarios
            if (isset($_POST['detalle_hora_ini']) && is_array($_POST['detalle_hora_ini'])) {
                foreach ($_POST['detalle_hora_ini'] as $index => $hora_ini) {
                    if (!empty($hora_ini) && !empty($_POST['detalle_hora_fin'][$index])) {
                        $data['detalles'][] = [
                            'hora_ini' => $hora_ini,
                            'hora_fin' => $_POST['detalle_hora_fin'][$index]
                        ];
                    }
                }
            }

            $resultado = $this->create($data);

            if ($resultado['success']) {
                $rol = $_GET['rol'] ?? 'coordinador';
                header('Location: /proyectoo_22_/mvc_programa/?url=/asignacion&rol=' . $rol . '&mensaje=' . urlencode($resultado['mensaje']));
                exit;
            } else {
                $errores = $resultado['errores'];
                $old = $data;
            }
        }

        $instructores = $this->getInstructores();
        $fichas = $this->getFichas();
        $ambientes = $this->getAmbientes();
        $competencias = $this->getCompetencias();
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/asignacion/crear.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function showEdit($asig_id)
    {
        $asignacion = $this->show($asig_id);
        
        if (!$asignacion) {
            $rol = $_GET['rol'] ?? 'coordinador';
            header('Location: /proyectoo_22_/mvc_programa/?url=/asignacion&rol=' . $rol . '&error=' . urlencode('Asignación no encontrada'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'asig_id' => $asig_id,
                'instructor_inst_id' => $_POST['INSTRUCTOR_inst_id'] ?? '',
                'ficha_fich_id' => $_POST['FICHA_fich_id'] ?? '',
                'ambiente_amb_id' => $_POST['AMBIENTE_id_ambiente'] ?? '',
                'competencia_comp_id' => $_POST['COMPETENCIA_comp_id'] ?? '',
                'asig_fecha_ini' => $_POST['asig_fecha_ini'] ?? '',
                'asig_fecha_fin' => $_POST['asig_fecha_fin'] ?? '',
                'detalles' => []
            ];

            // Procesar detalles de horarios
            if (isset($_POST['detalle_hora_ini']) && is_array($_POST['detalle_hora_ini'])) {
                foreach ($_POST['detalle_hora_ini'] as $index => $hora_ini) {
                    if (!empty($hora_ini) && !empty($_POST['detalle_hora_fin'][$index])) {
                        $data['detalles'][] = [
                            'hora_ini' => $hora_ini,
                            'hora_fin' => $_POST['detalle_hora_fin'][$index]
                        ];
                    }
                }
            }

            $resultado = $this->update($data);

            if ($resultado['success']) {
                $rol = $_GET['rol'] ?? 'coordinador';
                header('Location: /proyectoo_22_/mvc_programa/?url=/asignacion&rol=' . $rol . '&mensaje=' . urlencode($resultado['mensaje']));
                exit;
            } else {
                $errores = $resultado['errores'];
                $old = $data;
            }
        }

        // Obtener detalles existentes
        $detalles = $this->getDetallesByAsignacion($asig_id);
        
        $instructores = $this->getInstructores();
        $fichas = $this->getFichas();
        $ambientes = $this->getAmbientes();
        $competencias = $this->getCompetencias();
        $rol = $_GET['rol'] ?? 'coordinador';
        include __DIR__ . '/../views/asignacion/editar.php';
    }

    /**
     * Validar datos de la asignación
     */
    private function validar($data, $esActualizacion = false)
    {
        $errores = [];

        if ($esActualizacion && empty($data['asig_id'])) {
            $errores['asig_id'] = 'El ID de la asignación es requerido';
        }

        if (empty($data['instructor_inst_id'])) {
            $errores['instructor_inst_id'] = 'Debe seleccionar un instructor';
        }

        if (empty($data['ficha_fich_id'])) {
            $errores['ficha_fich_id'] = 'Debe seleccionar una ficha';
        }

        if (empty($data['ambiente_amb_id'])) {
            $errores['ambiente_amb_id'] = 'Debe seleccionar un ambiente';
        }

        if (empty($data['competencia_comp_id'])) {
            $errores['competencia_comp_id'] = 'Debe seleccionar una competencia';
        }

        if (empty($data['asig_fecha_ini'])) {
            $errores['asig_fecha_ini'] = 'La fecha de inicio es requerida';
        }

        if (empty($data['asig_fecha_fin'])) {
            $errores['asig_fecha_fin'] = 'La fecha de fin es requerida';
        }

        // Validar que la fecha de fin sea posterior a la de inicio
        if (!empty($data['asig_fecha_ini']) && !empty($data['asig_fecha_fin'])) {
            $fecha_ini = strtotime($data['asig_fecha_ini']);
            $fecha_fin = strtotime($data['asig_fecha_fin']);
            
            if ($fecha_ini >= $fecha_fin) {
                $errores['asig_fecha_fin'] = 'La fecha de fin debe ser posterior a la fecha de inicio';
            }
        }

        return $errores;
    }

    /**
     * Validar carga horaria del instructor
     * Verifica que no se superen las 20 horas semanales por competencia
     * y las 40 horas semanales totales del instructor
     */
    private function validarCargaHoraria($instructor_id, $competencia_id, $fecha_ini, $fecha_fin, $asig_id_excluir = null)
    {
        try {
            // Obtener horas de la competencia
            $query = "SELECT comp_horas FROM competencia WHERE comp_id = :comp_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':comp_id' => $competencia_id]);
            $competencia = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$competencia) {
                return ['valido' => false, 'mensaje' => 'Competencia no encontrada'];
            }
            
            $horas_competencia = $competencia['comp_horas'];
            
            // Calcular semanas de la asignación
            $fecha_inicio = new DateTime($fecha_ini);
            $fecha_final = new DateTime($fecha_fin);
            $dias = $fecha_inicio->diff($fecha_final)->days;
            $semanas = max(1, $dias / 7); // Mínimo 1 semana
            
            // Calcular horas por semana de esta asignación
            $horas_por_semana = $horas_competencia / $semanas;
            
            // Validar que no supere 20 horas semanales por competencia
            if ($horas_por_semana > 20) {
                return [
                    'valido' => false,
                    'mensaje' => sprintf(
                        'Esta asignación requiere %.2f horas semanales, superando el límite de 20 horas por competencia. Considere extender el período de la asignación.',
                        $horas_por_semana
                    )
                ];
            }
            
            // Calcular total de horas semanales del instructor en el mismo período
            $query = "SELECT a.asig_id, a.asig_fecha_ini, a.asig_fecha_fin, c.comp_horas
                      FROM asignacion a
                      INNER JOIN competencia c ON a.competencia_comp_id = c.comp_id
                      WHERE a.instructor_inst_id = :instructor_id
                      AND (
                          (:fecha_ini BETWEEN a.asig_fecha_ini AND a.asig_fecha_fin)
                          OR (:fecha_fin BETWEEN a.asig_fecha_ini AND a.asig_fecha_fin)
                          OR (a.asig_fecha_ini BETWEEN :fecha_ini2 AND :fecha_fin2)
                      )";
            
            if ($asig_id_excluir) {
                $query .= " AND a.asig_id != :asig_id_excluir";
            }
            
            $stmt = $this->db->prepare($query);
            $params = [
                ':instructor_id' => $instructor_id,
                ':fecha_ini' => $fecha_ini,
                ':fecha_fin' => $fecha_fin,
                ':fecha_ini2' => $fecha_ini,
                ':fecha_fin2' => $fecha_fin
            ];
            
            if ($asig_id_excluir) {
                $params[':asig_id_excluir'] = $asig_id_excluir;
            }
            
            $stmt->execute($params);
            $asignaciones_existentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $total_horas_semanales = 0;
            foreach ($asignaciones_existentes as $asig) {
                $fecha_ini_asig = new DateTime($asig['asig_fecha_ini']);
                $fecha_fin_asig = new DateTime($asig['asig_fecha_fin']);
                $dias_asig = $fecha_ini_asig->diff($fecha_fin_asig)->days;
                $semanas_asig = max(1, $dias_asig / 7);
                $total_horas_semanales += $asig['comp_horas'] / $semanas_asig;
            }
            
            // Validar que el total no supere 40 horas semanales
            if (($total_horas_semanales + $horas_por_semana) > 40) {
                return [
                    'valido' => false,
                    'mensaje' => sprintf(
                        'El instructor ya tiene %.2f horas semanales asignadas en este período. Esta nueva asignación (%.2f horas/semana) superaría el límite de 40 horas semanales.',
                        $total_horas_semanales,
                        $horas_por_semana
                    )
                ];
            }
            
            return [
                'valido' => true,
                'mensaje' => 'Validación exitosa',
                'horas_por_semana' => $horas_por_semana,
                'total_horas_semanales' => $total_horas_semanales + $horas_por_semana
            ];
            
        } catch (PDOException $e) {
            error_log("Error en validarCargaHoraria: " . $e->getMessage());
            return ['valido' => false, 'mensaje' => 'Error al validar la carga horaria'];
        }
    }
}
?>
