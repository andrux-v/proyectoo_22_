<?php
require_once __DIR__ . '/../Conexion.php';
class TituloProgramaModel
{
    private $titpro_id;
    private $titpro_nombre;
    private $db;

    public function __construct($titpro_id, $titpro_nombre)
    {
        $this->setTitproId($titpro_id);
        $this->setTitproNombre($titpro_nombre);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getTitproId()
    {
        return $this->titpro_id;
    }
    public function getTitproNombre()
    {
        return $this->titpro_nombre;
    }

    //setters 
    public function setTitproId($titpro_id)
    {
        $this->titpro_id = $titpro_id;
    }
    public function setTitproNombre($titpro_nombre)
    {
        $this->titpro_nombre = $titpro_nombre;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO titulo_programa (titpro_nombre) 
        VALUES (:titpro_nombre)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titpro_nombre', $this->titpro_nombre);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    public function read()
    {
        $sql = "SELECT * FROM titulo_programa WHERE titpro_id = :titpro_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':titpro_id' => $this->titpro_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM titulo_programa";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE titulo_programa SET titpro_nombre = :titpro_nombre WHERE titpro_id = :titpro_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titpro_nombre', $this->titpro_nombre);
        $stmt->bindParam(':titpro_id', $this->titpro_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM titulo_programa WHERE titpro_id = :titpro_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titpro_id', $this->titpro_id);
        $stmt->execute();
        return $stmt;
    }
}
