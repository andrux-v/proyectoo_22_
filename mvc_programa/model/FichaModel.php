<?php
require_once __DIR__ . '/../Conexion.php';
class FichaModel
{
    private $fich_id;
    private $PROGRAMA_prog_id;
    private $INSTRUCTOR_inst_id_lider;
    private $fich_jornada;
    private $COORDINACION_coord_id;
    private $fich_fecha_ini_lectiva;
    private $fich_fecha_fin_lectiva;
    private $db;

    public function __construct($fich_id, $PROGRAMA_prog_id, $INSTRUCTOR_inst_id_lider, $fich_jornada, $COORDINACION_coord_id, $fich_fecha_ini_lectiva = null, $fich_fecha_fin_lectiva = null)
    {
        $this->setFichId($fich_id);
        $this->setProgramaProgId($PROGRAMA_prog_id);
        $this->setInstructorInstIdLider($INSTRUCTOR_inst_id_lider);
        $this->setFichJornada($fich_jornada);
        $this->setCoordinacionCoordId($COORDINACION_coord_id);
        $this->setFichFechaIniLectiva($fich_fecha_ini_lectiva);
        $this->setFichFechaFinLectiva($fich_fecha_fin_lectiva);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getFichId()
    {
        return $this->fich_id;
    }
    public function getProgramaProgId()
    {
        return $this->PROGRAMA_prog_id;
    }
    public function getInstructorInstIdLider()
    {
        return $this->INSTRUCTOR_inst_id_lider;
    }
    public function getFichJornada()
    {
        return $this->fich_jornada;
    }
    public function getCoordinacionCoordId()
    {
        return $this->COORDINACION_coord_id;
    }
    public function getFichFechaIniLectiva()
    {
        return $this->fich_fecha_ini_lectiva;
    }
    public function getFichFechaFinLectiva()
    {
        return $this->fich_fecha_fin_lectiva;
    }

    //setters 
    public function setFichId($fich_id)
    {
        $this->fich_id = $fich_id;
    }
    public function setProgramaProgId($PROGRAMA_prog_id)
    {
        $this->PROGRAMA_prog_id = $PROGRAMA_prog_id;
    }
    public function setInstructorInstIdLider($INSTRUCTOR_inst_id_lider)
    {
        $this->INSTRUCTOR_inst_id_lider = $INSTRUCTOR_inst_id_lider;
    }
    public function setFichJornada($fich_jornada)
    {
        $this->fich_jornada = $fich_jornada;
    }
    public function setCoordinacionCoordId($COORDINACION_coord_id)
    {
        $this->COORDINACION_coord_id = $COORDINACION_coord_id;
    }
    public function setFichFechaIniLectiva($fich_fecha_ini_lectiva)
    {
        $this->fich_fecha_ini_lectiva = $fich_fecha_ini_lectiva;
    }
    public function setFichFechaFinLectiva($fich_fecha_fin_lectiva)
    {
        $this->fich_fecha_fin_lectiva = $fich_fecha_fin_lectiva;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO ficha (fich_id, programa_prog_id, instructor_inst_id_lider, fich_jornada, coordinacion_coord_id, fich_fecha_ini_lectiva, fich_fecha_fin_lectiva) 
        VALUES (:fich_id, :programa_prog_id, :instructor_inst_id_lider, :fich_jornada, :coordinacion_coord_id, :fich_fecha_ini_lectiva, :fich_fecha_fin_lectiva)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':fich_id', $this->fich_id);
        $stmt->bindParam(':programa_prog_id', $this->PROGRAMA_prog_id);
        $stmt->bindParam(':instructor_inst_id_lider', $this->INSTRUCTOR_inst_id_lider);
        $stmt->bindParam(':fich_jornada', $this->fich_jornada);
        $stmt->bindParam(':coordinacion_coord_id', $this->COORDINACION_coord_id);
        $stmt->bindParam(':fich_fecha_ini_lectiva', $this->fich_fecha_ini_lectiva);
        $stmt->bindParam(':fich_fecha_fin_lectiva', $this->fich_fecha_fin_lectiva);
        $stmt->execute();
        return $this->fich_id;
    }
    public function read()
    {
        $sql = "SELECT * FROM ficha WHERE fich_id = :fich_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':fich_id' => $this->fich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM ficha";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE ficha SET programa_prog_id = :programa_prog_id, instructor_inst_id_lider = :instructor_inst_id_lider, fich_jornada = :fich_jornada, coordinacion_coord_id = :coordinacion_coord_id, fich_fecha_ini_lectiva = :fich_fecha_ini_lectiva, fich_fecha_fin_lectiva = :fich_fecha_fin_lectiva WHERE fich_id = :fich_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':programa_prog_id', $this->PROGRAMA_prog_id);
        $stmt->bindParam(':instructor_inst_id_lider', $this->INSTRUCTOR_inst_id_lider);
        $stmt->bindParam(':fich_jornada', $this->fich_jornada);
        $stmt->bindParam(':coordinacion_coord_id', $this->COORDINACION_coord_id);
        $stmt->bindParam(':fich_fecha_ini_lectiva', $this->fich_fecha_ini_lectiva);
        $stmt->bindParam(':fich_fecha_fin_lectiva', $this->fich_fecha_fin_lectiva);
        $stmt->bindParam(':fich_id', $this->fich_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM ficha WHERE fich_id = :fich_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':fich_id', $this->fich_id);
        $stmt->execute();
        return $stmt;
    }
}
