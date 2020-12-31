<?php
/**
 * User Controller
 *
 * All functionality pertaining to User Controller.
 *
 * @package Controller
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

include_once "./models/userModel.php";

/*--- Class Login Controller ---*/
class loginController extends loginModel {
    /*-- Login Controller Function */
    public function login_controller() {

    }

}