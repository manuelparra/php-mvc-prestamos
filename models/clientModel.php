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
}
