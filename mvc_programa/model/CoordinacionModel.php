<?php
require_once __DIR__ . '/../Conexion.php';
class CoordinacionModel
{
    private $coord_id;
    private $coord_nombre;
    private $CENTRO_FORMACION_cent_id;
    private $db;

    public function __construct($coord_id, $coord_nombre, $CENTRO_FORMACION_cent_id)
    {
        $this->setCoordId($coord_id);
        $this->setCoordNombre($coord_nombre);
        $this->setCentroFormacionCentId($CENTRO_FORMACION_cent_id);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getCoordId()
    {
        return $this->coord_id;
    }
    public function getCoordNombre()
    {
        return $this->coord_nombre;
    }
    public function getCentroFormacionCentId()
    {
        return $this->CENTRO_FORMACION_cent_id;
    }

    //setters 
    public function setCoordId($coord_id)
    {
        $this->coord_id = $coord_id;
    }
    public function setCoordNombre($coord_nombre)
    {
        $this->coord_nombre = $coord_nombre;
    }
    public function setCentroFormacionCentId($CENTRO_FORMACION_cent_id)
    {
        $this->CENTRO_FORMACION_cent_id = $CENTRO_FORMACION_cent_id;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO coordinacion (coord_nombre, CENTRO_FORMACION_cent_id) 
        VALUES (:coord_nombre, :CENTRO_FORMACION_cent_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':coord_nombre', $this->coord_nombre);
        $stmt->bindParam(':CENTRO_FORMACION_cent_id', $this->CENTRO_FORMACION_cent_id);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    public function read()
    {
        $sql = "SELECT * FROM coordinacion WHERE coord_id = :coord_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':coord_id' => $this->coord_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM coordinacion";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE coordinacion SET coord_nombre = :coord_nombre, CENTRO_FORMACION_cent_id = :CENTRO_FORMACION_cent_id WHERE coord_id = :coord_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':coord_nombre', $this->coord_nombre);
        $stmt->bindParam(':CENTRO_FORMACION_cent_id', $this->CENTRO_FORMACION_cent_id);
        $stmt->bindParam(':coord_id', $this->coord_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM coordinacion WHERE coord_id = :coord_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':coord_id', $this->coord_id);
        $stmt->execute();
        return $stmt;
    }
}
