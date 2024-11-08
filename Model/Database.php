<?php
header('Content-Type: text/html; charset=UTF-8');
class Database
{

    private $connection = null;
    public function __construct()
    {
        try {

            $host = "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE_NAME;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            ];
            $this->connection = new PDO($host, DB_USERNAME, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
            die();
        }
    }

    public function consultarRegistros($query, $campos = [])
    {
        $resdb = $this->connection->prepare($query);
        $resdb->execute($campos);
        $respuesta = [];
        if ($resdb->rowCount() > 0) {
            foreach ($resdb->fetchAll(PDO::FETCH_OBJ) as $key => $value) {
                $respuesta[$key] = $value;
            }
            return $respuesta;
        } else {
            return false;
        }
    }
    public function consultarRegistros2($query, $campos = [])
    {
        $resdb = $this->connection->prepare($query);
        $resdb->execute($campos);
        $respuesta = $salida = [];
        if ($resdb->rowCount() > 0) {
            foreach ($resdb->fetchAll(PDO::FETCH_OBJ) as $key => $value) {
                $respuesta[$key] = $value;
            }
            foreach ($respuesta as $key => $value) {
                $data = [];
                foreach ($value as $keyd => $valued) {
                    $data[$keyd] = $valued;
                }
                $salida[] = $data;
            }
            return $salida;
        } else {
            return [];
        }
    }

    public function consultarRegistro($query, $campos = [], $filtro = "")
    {
        try {
            //code...
            $resdb = $this->connection->prepare($query);
            $resdb->execute($campos);
        } catch (\Throwable $th) {
            imprimir([$query, $campos, $filtro, $th]);
        }
        $respuesta = [];
        if ($resdb->rowCount() > 0) {
            foreach ($resdb->fetch(PDO::FETCH_OBJ) as $key => $value) {
                $respuesta[$key] = $value;
            }
            if ($filtro != '') {
                return $respuesta[$filtro];
            } else {
                return $respuesta;
            }
        } else {
            return [];
        }
    }

    public function consultar($query, $campos)
    {
        $resdb = $this->connection->prepare($query);
        $resdb->execute($campos);
        if ($resdb->rowCount() > 0) {
            return $resdb;
        } else {
            return false;
        }
    }

    public function crudRegistro($query, $campos)
    {
        $resdb = $this->connection->prepare($query);
        return $resdb->execute($campos);
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
    public function insertarRegistro($tabla, $datos)
    {
        $i = 0;
        $campos = $valores = '';
        foreach ($datos as $key => $value) {
            $campos .= ($i == 0 ? $key : ", " . $key);
            $valores .= ($i == 0 ? ":" . $key : ", :" . $key);
            $i++;
        }
        $query = "INSERT INTO $tabla ($campos) VALUES ($valores)";
        $resdb = $this->connection->prepare($query);
        return $resdb->execute($datos);
    }
    public function actualizarRegistro($tabla, $datos, $datosCondicion)
    {
        //
        $i = 0;
        $campos = $camposCondicion = '';
        foreach ($datos as $key => $value) {
            $campos .= ($i == 0 ? "$key = :$key" : ", $key = :$key");
            $i++;
        }
        $i = 0;
        foreach ($datosCondicion as $key => $value) {
            $camposCondicion .= ($i == 0 ? "$key = :$key" : ", $key = :$key");
            $i++;
        }
        $query = "UPDATE $tabla SET $campos";
        $query .= " WHERE $camposCondicion";
        $resdb = $this->connection->prepare($query);
        return $resdb->execute(array_merge($datos, $datosCondicion));
    }
    public function eliminarRegistro($tabla, $datos)
    {
        $i = 0;
        $campos = '';
        foreach ($datos as $key => $value) {
            $campos .= ($i == 0 ? "$key = :$key" : ", $key = :$key");
            $i++;
        }
        $query = "DELETE FROM $tabla WHERE $campos";
        $resdb = $this->connection->prepare($query);
        return $resdb->execute($datos);
    }

    public function contarRegistros($query)
    {
        $nRows = $this->connection->query("SELECT COUNT(*) FROM $query")->fetchColumn();
        return $nRows;
    }
}
