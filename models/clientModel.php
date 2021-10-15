<?php
/**
 *
 * Client Model Class
 *
 * All functionality pertaining to the Client Model.
 *
 * @package Model
 * @author Manuel Parra
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
    exit; // Exit if accessed directly
}

require_once "./models/mainModel.php";

/*--- Class Client ---*/
class clientModel extends mainModel {
    /*-- Function for add client --*/
    protected static function add_client_model($data) {
        // SQL Query for insert client
        $sql = "INSERT INTO cliente (cliente_dni, cliente_nombre, cliente_apellido,
                cliente_telefono, cliente_direccion)
                VALUES (:dni, :nombre, :apellido, :telefono, :direccion)";
        $query = mainModel::connection()->prepare($sql);

        $query->bindParam(":dni", $data['dni']);
        $query->bindParam(":nombre", $data['nombre']);
        $query->bindParam(":apellido", $data['apellido']);
        $query->bindParam(":telefono", $data['telefono']);
        $query->bindParam(":direccion", $data['direccion']);

        $query->execute();

        return $query;
    }

    /*-- Function for delete client --*/
    protected static function delete_client_model($id) {
        $sql = "DELETE FROM cliente
                WHERE cliente.cliente_id = :id";
        $query = mainModel::connection()->prepare($sql);

        $query->bindParam(":id", $id);
        $query->execute();

        return $query;
    }

    /*-- Function for query client data--*/
    protected static function query_data_client_model($type, $id) {
        if ($type == "Unique") {
            $sql = "SELECT cliente.*
                    FROM cliente
                    WHERE cliente.cliente_id = :id";
            $query = mainModel::connection()->prepare($sql);
            $query->bindParam(":id", $id);
        } elseif ($type == "Count") {
            $sql = "SELECT cliente.cliente_id
                    FROM cliente";
            $query = mainModel::connection()->prepare($sql);
        }

        $query->execute();
        return $query;
    }

    /*-- Function for update client data model --*/
    protected static function update_client_data_model($data) {
        $sql = "UPDATE cliente SET
                cliente.cliente_dni = :dni,
                cliente.cliente_nombre = :nombre,
                cliente.cliente_apellido = :apellido,
                cliente.cliente_telefono = :telefono,
                cliente.cliente_direccion = :direccion
                WHERE cliente.cliente_id= :id";
        $query = mainModel::connection()->prepare($sql);

        $query->bindParam(":dni", $data['dni']);
        $query->bindParam(":nombre", $data['nombre']);
        $query->bindParam(":apellido", $data['apellido']);
        $query->bindParam(":telefono", $data['telefono']);
        $query->bindParam(":direccion", $data['direccion']);
        $query->bindParam(":id", $data['id']);

        $query->execute();

        return $query;
    }
}
