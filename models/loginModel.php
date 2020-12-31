<?php
/**
 * Login Model Class
 *
 * All functionality pertaining to the Login Model.
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

/*--- Class Login Model ---*/
class loginModel extends mainModel {
    /*-- Login model --*/
    protected static function login_model($data) {
        $sql = "SELECT  *
                FROM usuario
                WHERE usuario_usuario = :usuario
                AND usuario_clave = :clave
                AND usuario_estado = 'Activa'";
        $query = mainModel::connection()->prepare($sql);
        $query->bindParam(":usuario", $data['usuario']);
        $query->bindParam(":clave", $data['clave']);
        $query->execute();
        return $query;
    }
}
