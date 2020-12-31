<?php
/**
 * View Controller
 *
 * All functionality pertaining to View Controller.
 *
 * @package Controller
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

require_once "./models/viewModel.php";

class viewController extends viewModel
{
    /*--- Controller to get template ---*/
    public function get_template_controller() {
        return require_once "./views/layout.php";
    }

    /*--- Controller to get view ---*/
    public function get_view_controller() {
        if (isset($_GET['view'])) {
            $route = explode("/", $_GET['view']);
            $response = viewModel::get_view_model($route[0]);
        } else {
            $response = "login";
        }
        error_log(print_r($response . "\n", true), 3, ERROR_DIR . "my-errors.log");
        return $response;
    }
}
