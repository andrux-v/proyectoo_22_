<?php
require_once __DIR__ . '/../Conexion.php';
class DetalleAsignacionModel
{
    private $asignacion_asig_id;
    private $detasig_hora_ini;
    private $detasig_hora_fin;
    private $detasig_id;
    private $db;

    public function __construct($asignacion_asig_id, $detasig_hora_ini, $detasig_hora_fin, $detasig_id)
    {
        $this->setAsignacionAsigId($asignacion_asig_id);
        $this->setDetasigHoraIni($detasig_hora_ini);
        $this->setDetasigHoraFin($detasig_hora_fin);
        $this->setDetasigId($detasig_id);
        $this->db = Conexion::getConnect();
    }
    //getters 

    public function getAsignacionAsigId()
    {
        return $this->asignacion_asig_id;
    }
    public function getDetasigHoraIni()
    {
        return $this->detasig_hora_ini;
    }
    public function getDetasigHoraFin()
    {
        return $this->detasig_hora_fin;
    }
    public function getDetasigId()
    {
        return $this->detasig_id;
    }

    //setters 
    public function setAsignacionAsigId($asignacion_asig_id)
    {
        $this->asignacion_asig_id = $asignacion_asig_id;
    }
    public function setDetasigHoraIni($detasig_hora_ini)
    {
        $this->detasig_hora_ini = $detasig_hora_ini;
    }
    public function setDetasigHoraFin($detasig_hora_fin)
    {
        $this->detasig_hora_fin = $detasig_hora_fin;
    }
    public function setDetasigId($detasig_id)
    {
        $this->detasig_id = $detasig_id;
    }
    //crud
    public function create()
    {
        $query = "INSERT INTO detalle_asignacion (asignacion_asig_id, detasig_hora_ini, detasig_hora_fin) 
        VALUES (:asignacion_asig_id, :detasig_hora_ini, :detasig_hora_fin)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':asignacion_asig_id', $this->asignacion_asig_id);
        $stmt->bindParam(':detasig_hora_ini', $this->detasig_hora_ini);
        $stmt->bindParam(':detasig_hora_fin', $this->detasig_hora_fin);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    public function read()
    {
        $sql = "SELECT * FROM detalle_asignacion WHERE asignacion_asig_id = :asignacion_asig_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':asignacion_asig_id' => $this->asignacion_asig_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM detalle_asignacion";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update()
    {
        $query = "UPDATE detalle_asignacion SET asignacion_asig_id = :asignacion_asig_id, detasig_hora_ini = :detasig_hora_ini, detasig_hora_fin = :detasig_hora_fin WHERE detasig_id = :detasig_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':asignacion_asig_id', $this->asignacion_asig_id);
        $stmt->bindParam(':detasig_hora_ini', $this->detasig_hora_ini);
        $stmt->bindParam(':detasig_hora_fin', $this->detasig_hora_fin);
        $stmt->bindParam(':detasig_id', $this->detasig_id);
        $stmt->execute();
        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM detalle_asignacion WHERE detasig_id = :detasig_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':detasig_id', $this->detasig_id);
        $stmt->execute();
        return $stmt;
    }
}
