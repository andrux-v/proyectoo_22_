<?php
require_once __DIR__ . '/../Conexion.php';
class ProgramaModel
{
    private $prog_codigo;
    private $prog_denominacion;
    private $TIT_PROGRAMA_titpro_id;
    private $prog_tipo;
    private $db;

    public function __construct($prog_codigo, $prog_denominacion, $TIT_PROGRAMA_titpro_id, $prog_tipo)
    {
        $this->setProgCodigo($prog_codigo);
        $this->setProgDenominacion($prog_denominacion);
        $this->setTitProgramaTitproId($TIT_PROGRAMA_titpro_id);
        $this->setProgTipo($prog_tipo);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getProgCodigo()
    {
        return $this->prog_codigo;
    }
    public function getProgDenominacion()
    {
        return $this->prog_denominacion;
    }
    public function getTitProgramaTitproId()
    {
        return $this->TIT_PROGRAMA_titpro_id;
    }
    public function getProgTipo()
    {
        return $this->prog_tipo;
    }

    //setters 
    public function setProgCodigo($prog_codigo)
    {
        $this->prog_codigo = $prog_codigo;
    }
    public function setProgDenominacion($prog_denominacion)
    {
        $this->prog_denominacion = $prog_denominacion;
    }
    public function setTitProgramaTitproId($TIT_PROGRAMA_titpro_id)
    {
        $this->TIT_PROGRAMA_titpro_id = $TIT_PROGRAMA_titpro_id;
    }
    public function setProgTipo($prog_tipo)
    {
        $this->prog_tipo = $prog_tipo;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO programa (prog_denominación, TIT_PROGRAMA_titpro_id, prog_tipo) 
        VALUES (:prog_denominacion, :TIT_PROGRAMA_titpro_id, :prog_tipo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':prog_denominacion', $this->prog_denominacion);
        $stmt->bindParam(':TIT_PROGRAMA_titpro_id', $this->TIT_PROGRAMA_titpro_id);
        $stmt->bindParam(':prog_tipo', $this->prog_tipo);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    public function read()
    {
        $sql = "SELECT * FROM programa WHERE prog_codigo = :prog_codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':prog_codigo' => $this->prog_codigo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM programa";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE programa SET prog_denominación = :prog_denominacion, TIT_PROGRAMA_titpro_id = :TIT_PROGRAMA_titpro_id, prog_tipo = :prog_tipo WHERE prog_codigo = :prog_codigo";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':prog_denominacion', $this->prog_denominacion);
        $stmt->bindParam(':TIT_PROGRAMA_titpro_id', $this->TIT_PROGRAMA_titpro_id);
        $stmt->bindParam(':prog_tipo', $this->prog_tipo);
        $stmt->bindParam(':prog_codigo', $this->prog_codigo);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM programa WHERE prog_codigo = :prog_codigo";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':prog_codigo', $this->prog_codigo);
        $stmt->execute();
        return $stmt;
    }
}
