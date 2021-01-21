<?php
/**
 * Client Controller
 *
 * All functionality pertaining to User Controller.
 *
 * @package Controller
 * @author Manuel Parra
 * @version 1.0.0
 */

 if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
    exit; // Exit if accessed directly
 }

 include_once "./models/clientModel.php";

 /*--- Class Model Controller ---*/
 class clientController extends clientModel {
    /*-- Contoller's function for add client --*/
    public function add_client_controller() {
        $dni = clientModel::clean_string($_POST['cliente_dni_reg']);
        $nombre = clientModel::clean_string($_POST['cliente_nombre_reg']);
        $apellido = clientModel::clean_string($_POST['cliente_apellido_reg']);
        $telefono = clientModel::clean_string($_POST['cliente_telefono']);
        $direccion = clientModel::clean_string($_POST['cliente_direccion_reg']);

        // Check empty fields
        if ($dni == "" || $nombre == "") {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrió un error inesperado",
                                                        "No has llenado todos los campos requeridos");
            return $res;
        }
    }
 }
