<?php
/**
 * User Model Class
 *
 * All functionality pertaining to the User Model.
 *
 * @package Model
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

if ($ajaxReq) {
    require_once "./models/mainModel.php";
} else {
    require_once "./mainModel.php";
}

/*--- Class User Model ---*/
class userModel extends mainModel {
    /*--- Model's function to add user ---*/
    protected static function add_user_model($data) {

        $sql = "INSERT INTO usuario (usuario_dni, usuario_nombre, usuario_apellido,
                usuario_telefono, usuario_direccion, usuario_email, usuario_usuario,
                usuario_clave, usuario_estado, usuario_privilegio)
                VALUES (:dni, :nombre, :apellido, :telefono, :direccion, :email,
                :usuario, :clave, :estado, :privilegio)";

        $query = mainModel::connection()->prepare($sql);

        $query->bindParam(":dni", $data['dni']);
        $query->bindParam(":nombre", $data['nombre']);
        $query->bindParam(":apellido", $data['apellido']);
        $query->bindParam(":telefono", $data['telefono']);
        $query->bindParam(":direccion", $data['direccion']);
        $query->bindParam(":email", $data['email']);
        $query->bindParam(":usuario", $data['usuario']);
        $query->bindParam(":clave", $data['clave']);
        $query->bindParam(":estado", $data['estado']);
        $query->bindParam(":privilegio", $data['privilegio']);

        $query->execute();

        return $query;
    }
}