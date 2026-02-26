<?php
require_once __DIR__ . '/../Conexion.php';
class InstructorModel
{
    private $inst_id;
    private $inst_nombres;
    private $inst_apellidos;
    private $inst_correo;
    private $inst_telefono;
    private $CENTRO_FORMACION_cent_id;
    private $inst_password;
    private $db;

    public function __construct($inst_id, $inst_nombres, $inst_apellidos, $inst_correo, $inst_telefono, $CENTRO_FORMACION_cent_id, $inst_password = null)
    {
        $this->setInstId($inst_id);
        $this->setInstNombres($inst_nombres);
        $this->setInstApellidos($inst_apellidos);
        $this->setInstCorreo($inst_correo);
        $this->setInstTelefono($inst_telefono);
        $this->setCentroFormacionCentId($CENTRO_FORMACION_cent_id);
        $this->setInstPassword($inst_password);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getInstId()
    {
        return $this->inst_id;
    }
    public function getInstNombres()
    {
        return $this->inst_nombres;
    }
    public function getInstApellidos()
    {
        return $this->inst_apellidos;
    }
    public function getInstCorreo()
    {
        return $this->inst_correo;
    }
    public function getInstTelefono()
    {
        return $this->inst_telefono;
    }
    public function getCentroFormacionCentId()
    {
        return $this->CENTRO_FORMACION_cent_id;
    }
    public function getInstPassword()
    {
        return $this->inst_password;
    }

    //setters 
    public function setInstId($inst_id)
    {
        $this->inst_id = $inst_id;
    }
    public function setInstNombres($inst_nombres)
    {
        $this->inst_nombres = $inst_nombres;
    }
    public function setInstApellidos($inst_apellidos)
    {
        $this->inst_apellidos = $inst_apellidos;
    }
    public function setInstCorreo($inst_correo)
    {
        $this->inst_correo = $inst_correo;
    }
    public function setInstTelefono($inst_telefono)
    {
        $this->inst_telefono = $inst_telefono;
    }
    public function setCentroFormacionCentId($CENTRO_FORMACION_cent_id)
    {
        $this->CENTRO_FORMACION_cent_id = $CENTRO_FORMACION_cent_id;
    }
    public function setInstPassword($inst_password)
    {
        $this->inst_password = $inst_password;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO instructor (inst_nombres, inst_apellidos, inst_correo, inst_telefono, CENTRO_FORMACION_cent_id, inst_password) 
        VALUES (:inst_nombres, :inst_apellidos, :inst_correo, :inst_telefono, :CENTRO_FORMACION_cent_id, :inst_password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':inst_nombres', $this->inst_nombres);
        $stmt->bindParam(':inst_apellidos', $this->inst_apellidos);
        $stmt->bindParam(':inst_correo', $this->inst_correo);
        $stmt->bindParam(':inst_telefono', $this->inst_telefono);
        $stmt->bindParam(':CENTRO_FORMACION_cent_id', $this->CENTRO_FORMACION_cent_id);
        $stmt->bindParam(':inst_password', $this->inst_password);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    public function read()
    {
        $sql = "SELECT * FROM instructor WHERE inst_id = :inst_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':inst_id' => $this->inst_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM instructor";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE instructor SET inst_nombres = :inst_nombres, inst_apellidos = :inst_apellidos, inst_correo = :inst_correo, inst_telefono = :inst_telefono, CENTRO_FORMACION_cent_id = :CENTRO_FORMACION_cent_id, inst_password = :inst_password WHERE inst_id = :inst_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':inst_nombres', $this->inst_nombres);
        $stmt->bindParam(':inst_apellidos', $this->inst_apellidos);
        $stmt->bindParam(':inst_correo', $this->inst_correo);
        $stmt->bindParam(':inst_telefono', $this->inst_telefono);
        $stmt->bindParam(':CENTRO_FORMACION_cent_id', $this->CENTRO_FORMACION_cent_id);
        $stmt->bindParam(':inst_password', $this->inst_password);
        $stmt->bindParam(':inst_id', $this->inst_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM instructor WHERE inst_id = :inst_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':inst_id', $this->inst_id);
        $stmt->execute();
        return $stmt;
    }
}
