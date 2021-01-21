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

if (!defined( 'ABSPATH')) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

require_once "./models/mainModel.php";

/*--- Class User Model ---*/
class userModel extends mainModel {
    /*-- Function for add user --*/
    protected static function add_user_model($data) {

        $sql = "INSERT INTO usuario (usuario_dni, usuario_nombre, usuario_apellido,
                usuario_telefono, usuario_direccion, usuario_perfil_id, usuario_email,
                usuario_usuario, usuario_clave, usuario_estado, usuario_privilegio)
                VALUES (:dni, :nombre, :apellido, :telefono, :direccion, :perfil,
                :email, :usuario, :clave, :estado, :privilegio)";

        $query = mainModel::connection()->prepare($sql);

        $query->bindParam(":dni", $data['dni']);
        $query->bindParam(":nombre", $data['nombre']);
        $query->bindParam(":apellido", $data['apellido']);
        $query->bindParam(":telefono", $data['telefono']);
        $query->bindParam(":direccion", $data['direccion']);
        $query->bindParam(":perfil", $data['perfil']);
        $query->bindParam(":email", $data['email']);
        $query->bindParam(":usuario", $data['usuario']);
        $query->bindParam(":clave", $data['clave']);
        $query->bindParam(":estado", $data['estado']);
        $query->bindParam(":privilegio", $data['privilegio']);

        $query->execute();

        return $query;
    }

    /*-- Function for delete user --*/
    protected static function delete_user_model($id) {

        $sql = "DELETE FROM usuario
                WHERE usuario.usuario_id = :id";
        $query = mainModel::connection()->prepare($sql);

        $query->bindParam(":id", $id);
        $query->execute();

        return $query;
    }

    /*-- Function for query user data --*/
    protected static function query_data_user_model($type, $id) {
        if ($type == "Unique") {
            $sql = "SELECT usuario.*, perfil.perfil_nombre
                    FROM prestamos.usuario
                    LEFT JOIN prestamos.perfil
                    ON usuario.usuario_perfil_id = perfil.perfil_id
                    WHERE usuario_id = :id";
            $query = mainModel::connection()->prepare($sql);
            $query->bindParam(":id", $id);
        } elseif ($type == "Count") {
            $sql = "SELECT usuario_id
                    FROM usuario
                    WHERE usuario_id != 1"; // id = 1 is because 1 is the main user id, is the first user registered in the system
            $query = mainModel::connection()->prepare($sql);
        }

        $query->execute();
        return $query;
    }

    /*-- Function for query profile list --*/
    protected static function perfil_list_user_model() {
        $sql = "SELECT perfil_id, perfil_nombre
                FROM perfil
                ORDER BY perfil_nombre DESC";
        $query = mainModel::connection()->prepare($sql);
        $query->execute();
        return $query;
    }

    /*-- Function update user data model --*/
    protected static function update_user_data_model($data) {
        $sql = "UPDATE usuario SET  usuario.usuario_dni = :dni, usuario.usuario_nombre = :nombre,
                usuario.usuario_apellido = :apellido, usuario.usuario_telefono = :telefono,
                usuario.usuario_direccion = :direccion, usuario.usuario_email = :email,
                usuario.usuario_usuario = :usuario, usuario.usuario_clave = :clave,
                usuario.usuario_estado = :estado, usuario.usuario_privilegio = :privilegio,
                usuario.usuario_perfil_id = :perfil_id
                WHERE usuario.usuario_id = :id ";

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
        $query->bindParam(":perfil_id", $data['perfil_id']);
        $query->bindParam(":id", $data['id']);

        $query->execute();

        return $query;
    }
}
