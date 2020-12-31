<?php
/**
 * Ajax User Class
 *
 * All functionality pertaining to the Ajax User request.
 *
 * @package Ajax Request
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

$ajaxReq = true;

if (isset($_POST['usuario_dni_reg'])) {
    /*--- Instance to user controller ---*/
    require_once "./controllers/userController.php";
    $insUser = new userController();

    /*--- Add user ---*/
    if ( (isset($_POST['usuario_dni_reg']) && !empty($_POST['usuario_dni_reg'])) &&
         (isset($_POST['usuario_nombre_reg']) && !empty($_POST['usuario_nombre_reg'])) &&
         (isset($_POST['usuario_usuario_reg']) && !empty($_POST['usuario_usuario_reg'])) &&
         (isset($_POST['usuario_email_reg']) && !empty($_POST['usuario_email_reg'])) &&
         (isset($_POST['usuario_clave_1_reg']) && !empty($_POST['usuario_clave_1_reg'])) &&
         (isset($_POST['usuario_clave_1_reg']) && !empty($_POST['usuario_clave_1_reg'])) &&
         (isset($_POST['usuario_privilegio_reg']) && !empty($_POST['usuario_privilegio_reg'])) ) {

        echo $insUser->add_user_controller();

        exit();
    } else {
        echo $insUser->message_with_parameters("simple", "error", "No has llenado todos los campos requeridos",
                                              "Ocurrio un error inersperado");
        exit();
    }
} else {
    session_start(['name' => 'SPM',]);
    session_unset();
    session_destroy();
    header("Location: " . SERVER_URL . "login/");
    exit();
}