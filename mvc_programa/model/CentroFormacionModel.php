<?php
require_once __DIR__ . '/../Conexion.php';
class CentroFormacionModel
{
    private $cent_id;
    private $cent_nombre;
    private $cent_correo;
    private $cent_password;
    private $db;

    public function __construct($cent_id, $cent_nombre, $cent_correo = null, $cent_password = null)
    {
        $this->setCentId($cent_id);
        $this->setCentNombre($cent_nombre);
        $this->setCentCorreo($cent_correo);
        $this->setCentPassword($cent_password);
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
    public function getCentCorreo()
    {
        return $this->cent_correo;
    }
    public function getCentPassword()
    {
        return $this->cent_password;
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
    public function setCentCorreo($cent_correo)
    {
        $this->cent_correo = $cent_correo;
    }
    public function setCentPassword($cent_password)
    {
        $this->cent_password = $cent_password;
    }
    //crud
    public function create()
    {
        try {
            $query = "INSERT INTO centro_formacion (cent_nombre, cent_correo, cent_password) 
            VALUES (:cent_nombre, :cent_correo, :cent_password)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cent_nombre', $this->cent_nombre);
            $stmt->bindParam(':cent_correo', $this->cent_correo);
            $stmt->bindParam(':cent_password', $this->cent_password);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en CentroFormacionModel::create - " . $e->getMessage());
            throw $e;
        }
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
        $query = "UPDATE centro_formacion 
                  SET cent_nombre = :cent_nombre, 
                      cent_correo = :cent_correo" . 
                      ($this->cent_password ? ", cent_password = :cent_password" : "") . 
                  " WHERE cent_id = :cent_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cent_nombre', $this->cent_nombre);
        $stmt->bindParam(':cent_correo', $this->cent_correo);
        $stmt->bindParam(':cent_id', $this->cent_id);
        if ($this->cent_password) {
            $stmt->bindParam(':cent_password', $this->cent_password);
        }
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
