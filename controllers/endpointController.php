<?php
/**
 * API Controller
 *
 * All functionality pertaining to API Controller.
 *
 * @package Controller
 * @author Manuel Parra
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

require_once "./models/endpointModel.php";

/*--- Endpoint Controller ---*/
class endpointController extends endpointModel {
    /*-- Controller's function for get ajax requests --*/
    public function get_endpoint_controller($endpoint) {
        $route = explode("/", $endpoint);
        $path = endpointModel::get_endpoint_model($route[1]);

        return $path;
    }
}
