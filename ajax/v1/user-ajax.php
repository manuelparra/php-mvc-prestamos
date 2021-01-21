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

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

if (isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_del']) || isset($_POST['usuario_id_upd'])) {

    // Instance to user controller
    require_once "./controllers/userController.php";
    $insUser = new userController();

    // Update user
    if (isset($_POST['usuario_id_upd'])) {
        echo $insUser->update_user_data_controller();
        exit;
    }

    // Delete user
    if (isset($_POST['usuario_id_del'])) {
        echo $insUser->delete_user_controller();
        exit;
    }

    // Add user
    if ((isset($_POST['usuario_dni_reg']) && !empty($_POST['usuario_dni_reg'])) &&
        (isset($_POST['usuario_nombre_reg']) && !empty($_POST['usuario_nombre_reg'])) &&
        (isset($_POST['usuario_usuario_reg']) && !empty($_POST['usuario_usuario_reg'])) &&
        (isset($_POST['usuario_email_reg']) && !empty($_POST['usuario_email_reg'])) &&
        (isset($_POST['usuario_clave_1_reg']) && !empty($_POST['usuario_clave_1_reg'])) &&
        (isset($_POST['usuario_clave_1_reg']) && !empty($_POST['usuario_clave_1_reg'])) &&
        (isset($_POST['usuario_privilegio_reg']) && !empty($_POST['usuario_privilegio_reg']))) {

        echo $insUser->add_user_controller();
        exit;
    } else {
        echo $insUser->message_user_controller("simple", "error", "Ocurrió un error inesperado",
                                               "No has llenado todos los campos requeridos.");
        exit;
    }
} else {
    session_start(['name' => 'SPM']);
    session_unset();
    session_destroy();
    header("Location: " . SERVER_URL . "login/");
    exit;
}
