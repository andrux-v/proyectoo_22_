<?php
require_once __DIR__ . '/../Conexion.php';
class CentroFormacionModel
{
    private $cent_id;
    private $cent_nombre;
    private $db;

    public function __construct($cent_id, $cent_nombre)
    {
        $this->setCentId($cent_id);
        $this->setCentNombre($cent_nombre);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getCentId()
    {
        return $this->cent_id;
    }
    public function getCentNombre()
    {
        return $this->cent_nombre;
    }

    //setters 
    public function setCentId($cent_id)
    {
        $this->cent_id = $cent_id;
    }
    public function setCentNombre($cent_nombre)
    {
        $this->cent_nombre = $cent_nombre;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO centro_formacion (cent_nombre) 
        VALUES (:cent_nombre)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cent_nombre', $this->cent_nombre);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    public function read()
    {
        $sql = "SELECT * FROM centro_formacion WHERE cent_id = :cent_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cent_id' => $this->cent_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM centro_formacion";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE centro_formacion SET cent_nombre = :cent_nombre WHERE cent_id = :cent_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cent_nombre', $this->cent_nombre);
        $stmt->bindParam(':cent_id', $this->cent_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM centro_formacion WHERE cent_id = :cent_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cent_id', $this->cent_id);
        $stmt->execute();
        return $stmt;
    }
}
