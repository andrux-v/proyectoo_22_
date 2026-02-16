<?php
require_once __DIR__ . '/../Conexion.php';
class CompetenciaProgramaModel
{
    private $programa_prog_id;
    private $competencia_comp_id;
    private $db;

    public function __construct($programa_prog_id, $competencia_comp_id)
    {
        $this->setProgramaProgId($programa_prog_id);
        $this->setCompetenciaCompId($competencia_comp_id);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getProgramaProgId()
    {
        return $this->programa_prog_id;
    }
    public function getCompetenciaCompId()
    {
        return $this->competencia_comp_id;
    }

    //setters 
    public function setProgramaProgId($programa_prog_id)
    {
        $this->programa_prog_id = $programa_prog_id;
    }
    public function setCompetenciaCompId($competencia_comp_id)
    {
        $this->competencia_comp_id = $competencia_comp_id;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO competxprograma (PROGRAMA_prog_id, COMPETENCIA_comp_id) 
        VALUES (:programa_prog_id, :competencia_comp_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':programa_prog_id', $this->programa_prog_id);
        $stmt->bindParam(':competencia_comp_id', $this->competencia_comp_id);
        $stmt->execute();
        return $stmt;
    }
    public function read()
    {
        $sql = "SELECT * FROM competxprograma WHERE PROGRAMA_prog_id = :programa_prog_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':programa_prog_id' => $this->programa_prog_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM competxprograma";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE competxprograma SET COMPETENCIA_comp_id = :competencia_comp_id WHERE PROGRAMA_prog_id = :programa_prog_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':competencia_comp_id', $this->competencia_comp_id);
        $stmt->bindParam(':programa_prog_id', $this->programa_prog_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM competxprograma WHERE PROGRAMA_prog_id = :programa_prog_id AND COMPETENCIA_comp_id = :competencia_comp_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':programa_prog_id', $this->programa_prog_id);
        $stmt->bindParam(':competencia_comp_id', $this->competencia_comp_id);
        $stmt->execute();
        return $stmt;
    }
}
