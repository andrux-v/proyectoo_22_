<?php
require_once __DIR__ . '/../Conexion.php';
class InstruCompetenciaModel
{
    private $inscomp_id;
    private $instructor_inst_id;
    private $competxprograma_programa_prog_id;
    private $competxprograma_competencia_comp_id;
    private $inscomp_vigencia;
    private $db;

    public function __construct($inscomp_id, $instructor_inst_id, $competxprograma_programa_prog_id, $competxprograma_competencia_comp_id, $inscomp_vigencia = null)
    {
        $this->setInscompId($inscomp_id);
        $this->setInstructorInstId($instructor_inst_id);
        $this->setCompetxprogramaProgramaProgId($competxprograma_programa_prog_id);
        $this->setCompetxprogramaCompetenciaCompId($competxprograma_competencia_comp_id);
        $this->setInscompVigencia($inscomp_vigencia);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getInscompId()
    {
        return $this->inscomp_id;
    }
    public function getInstructorInstId()
    {
        return $this->instructor_inst_id;
    }
    public function getCompetxprogramaProgramaProgId()
    {
        return $this->competxprograma_programa_prog_id;
    }
    public function getCompetxprogramaCompetenciaCompId()
    {
        return $this->competxprograma_competencia_comp_id;
    }
    public function getInscompVigencia()
    {
        return $this->inscomp_vigencia;
    }

    //setters 
    public function setInscompId($inscomp_id)
    {
        $this->inscomp_id = $inscomp_id;
    }
    public function setInstructorInstId($instructor_inst_id)
    {
        $this->instructor_inst_id = $instructor_inst_id;
    }
    public function setCompetxprogramaProgramaProgId($competxprograma_programa_prog_id)
    {
        $this->competxprograma_programa_prog_id = $competxprograma_programa_prog_id;
    }
    public function setCompetxprogramaCompetenciaCompId($competxprograma_competencia_comp_id)
    {
        $this->competxprograma_competencia_comp_id = $competxprograma_competencia_comp_id;
    }
    public function setInscompVigencia($inscomp_vigencia)
    {
        $this->inscomp_vigencia = $inscomp_vigencia;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO instru_competencia (instructor_inst_id, competxprograma_programa_prog_id, competxprograma_competencia_comp_id, inscomp_vigencia) 
        VALUES (:instructor_inst_id, :competxprograma_programa_prog_id, :competxprograma_competencia_comp_id, :inscomp_vigencia)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':instructor_inst_id', $this->instructor_inst_id);
        $stmt->bindParam(':competxprograma_programa_prog_id', $this->competxprograma_programa_prog_id);
        $stmt->bindParam(':competxprograma_competencia_comp_id', $this->competxprograma_competencia_comp_id);
        $stmt->bindParam(':inscomp_vigencia', $this->inscomp_vigencia);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    public function read()
    {
        $sql = "SELECT * FROM instru_competencia WHERE inscomp_id = :inscomp_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':inscomp_id' => $this->inscomp_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM instru_competencia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE instru_competencia SET instructor_inst_id = :instructor_inst_id, competxprograma_programa_prog_id = :competxprograma_programa_prog_id, competxprograma_competencia_comp_id = :competxprograma_competencia_comp_id, inscomp_vigencia = :inscomp_vigencia WHERE inscomp_id = :inscomp_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':instructor_inst_id', $this->instructor_inst_id);
        $stmt->bindParam(':competxprograma_programa_prog_id', $this->competxprograma_programa_prog_id);
        $stmt->bindParam(':competxprograma_competencia_comp_id', $this->competxprograma_competencia_comp_id);
        $stmt->bindParam(':inscomp_vigencia', $this->inscomp_vigencia);
        $stmt->bindParam(':inscomp_id', $this->inscomp_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM instru_competencia WHERE inscomp_id = :inscomp_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':inscomp_id', $this->inscomp_id);
        $stmt->execute();
        return $stmt;
    }
}
