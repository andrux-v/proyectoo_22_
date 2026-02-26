<?php
require_once __DIR__ . '/../Conexion.php';
class CoordinacionModel
{
    private $coord_id;
    private $coord_descripcion;
    private $CENTRO_FORMACION_cent_id;
    private $coord_nombre_coordinador;
    private $coord_correo;
    private $coord_password;
    private $db;

    public function __construct($coord_id, $coord_descripcion, $CENTRO_FORMACION_cent_id, $coord_nombre_coordinador = null, $coord_correo = null, $coord_password = null)
    {
        $this->setCoordId($coord_id);
        $this->setCoordDescripcion($coord_descripcion);
        $this->setCentroFormacionCentId($CENTRO_FORMACION_cent_id);
        $this->setCoordNombreCoordinador($coord_nombre_coordinador);
        $this->setCoordCorreo($coord_correo);
        $this->setCoordPassword($coord_password);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getCoordId()
    {
        return $this->coord_id;
    }
    public function getCoordDescripcion()
    {
        return $this->coord_descripcion;
    }
    public function getCentroFormacionCentId()
    {
        return $this->CENTRO_FORMACION_cent_id;
    }
    public function getCoordNombreCoordinador()
    {
        return $this->coord_nombre_coordinador;
    }
    public function getCoordCorreo()
    {
        return $this->coord_correo;
    }
    public function getCoordPassword()
    {
        return $this->coord_password;
    }

    //setters 
    public function setCoordId($coord_id)
    {
        $this->coord_id = $coord_id;
    }
    public function setCoordDescripcion($coord_descripcion)
    {
        $this->coord_descripcion = $coord_descripcion;
    }
    public function setCentroFormacionCentId($CENTRO_FORMACION_cent_id)
    {
        $this->CENTRO_FORMACION_cent_id = $CENTRO_FORMACION_cent_id;
    }
    public function setCoordNombreCoordinador($coord_nombre_coordinador)
    {
        $this->coord_nombre_coordinador = $coord_nombre_coordinador;
    }
    public function setCoordCorreo($coord_correo)
    {
        $this->coord_correo = $coord_correo;
    }
    public function setCoordPassword($coord_password)
    {
        $this->coord_password = $coord_password;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO coordinacion (coord_descripcion, centro_formacion_cent_id, coord_nombre_coordinador, coord_correo, coord_password) 
        VALUES (:coord_descripcion, :centro_formacion_cent_id, :coord_nombre_coordinador, :coord_correo, :coord_password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':coord_descripcion', $this->coord_descripcion);
        $stmt->bindParam(':centro_formacion_cent_id', $this->CENTRO_FORMACION_cent_id);
        $stmt->bindParam(':coord_nombre_coordinador', $this->coord_nombre_coordinador);
        $stmt->bindParam(':coord_correo', $this->coord_correo);
        $stmt->bindParam(':coord_password', $this->coord_password);
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
        $query = "UPDATE coordinacion SET coord_descripcion = :coord_descripcion, centro_formacion_cent_id = :centro_formacion_cent_id, coord_nombre_coordinador = :coord_nombre_coordinador, coord_correo = :coord_correo, coord_password = :coord_password WHERE coord_id = :coord_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':coord_descripcion', $this->coord_descripcion);
        $stmt->bindParam(':centro_formacion_cent_id', $this->CENTRO_FORMACION_cent_id);
        $stmt->bindParam(':coord_nombre_coordinador', $this->coord_nombre_coordinador);
        $stmt->bindParam(':coord_correo', $this->coord_correo);
        $stmt->bindParam(':coord_password', $this->coord_password);
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
