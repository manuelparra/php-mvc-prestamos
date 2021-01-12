<?php
/**
 * API Model Class
 *
 * All functionality pertaining to the API Model.
 *
 * @package Model
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

class endpointModel {
    /*--- Function for get ajax requests ---*/
    protected static function get_endpoint_model($file) {
        $whiteListView = ["user-ajax", "login-ajax"];

        $req = "./ajax/v1/error.php";

        if (in_array($file, $whiteListView)) {
            if (is_file("./ajax/v1/" . $file . ".php")) {
                $req = "./ajax/v1/" . $file . ".php";
            }
        }

        return $req;
    }
}
