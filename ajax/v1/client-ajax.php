<?php
/**
 * Ajax Client Script
 *
 * All functionality pertaining to the Ajax User requests.
 *
 * @package Ajax Request
 * @author Manuel Parra
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
    exit; // Exit if accessed directly
}

if (isset($_POST['cliente_dni_reg']) || isset($_POST['cliente_id_del'])) {
    // Instance to client controller
    require_once "./controllers/clientController.php";
    $insClient = new clientController();

    // Add client
    if (isset($_POST['cliente_dni_reg']) && isset($_POST['cliente_nombre_reg'])) {
        echo $insClient->add_client_controller();
        exit;
    }

    // Delete client
    if (isset($_POST['cliente_id_del'])) {
        echo $insClient->delete_client_controller();
        exit;
    }
} else {
    session_start(['name' => "SPM"]);
    session_unset();
    session_destroy();
    header("Location: " . SERVER_URL . "login/");
    exit;
}
