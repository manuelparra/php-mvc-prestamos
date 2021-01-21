<?php
/**
 * Ajax Login Script
 *
 * All functionality pertaining to the Ajax Login requests.
 *
 * @package Ajax Request
 * @author Manuel Parra
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

if (isset($_POST['token']) && isset($_POST['usuario'])) {

    // Instance to Login controller
    require_once "./controllers/loginController.php";
    $insLogin = new loginController();

    echo $insLogin->close_session_controller();
    exit;
} else {
    session_start(['name' => 'SPM']);
    session_unset();
    session_destroy();
    header("Location: " . SERVER_URL . "login/");
    exit;
}
