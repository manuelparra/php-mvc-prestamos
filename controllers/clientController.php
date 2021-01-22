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
        $telefono = clientModel::clean_string($_POST['cliente_telefono_reg']);
        $direccion = clientModel::clean_string($_POST['cliente_direccion_reg']);

        // Check empty fields
        if ($dni == "" || $nombre == "" || $apellido == "" ||
            $telefono == "" || $direccion == "") {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrió un error inesperado",
                                                        "No has llenado todos los campos requeridos");
            return $res;
        }

        // Check data's integrity
        // Check DNI
        if (clientModel::check_data("[0-9]{8}[-]{1}[TRWAechoGMYFPDXBNJZSQVHLCKE]{1}", $dni)) {
            $res = clientModel::message_with_parameters("simple", "error", "Formato de DNI erróneo",
                                                        "El DNI no coincide con el formato solicitado.");
            return $res;
        }

        // Check first name
        if (clientModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {
            $res = clientModel::message_with_parameters("simple", "error", "Formato de Nombre erróneo",
                                                        "El Nombre no coincide con el formato solicitado.");
            return $res;
        }

        // Check last name
        if (clientModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {
            $res = clientModel::message_with_parameters("simple", "error", "Formato de Apellido erróneo",
                                                        "El Apellido no coincide con el formato solicitado.");
            return $res;
        }

        // Check phone
        if (clientModel::check_data("[0-9()+]{9,20}", $telefono)) {
            $res = clientModel::message_with_parameters("simple", "error", "Formato de Telefono erróneo",
                                                        "El Telefono no coincide con el formato solicitado.");
            return $res;
        }

        // Check address
        if (clientModel::check_data("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)){
            $res = clientModel::message_with_parameters("simple", "error", "Formato de Dirección erróneo",
                                                        "La Dirección no coincide coon el formato solicitado.");
            return $res;
        }

        // Check DNI as unique data in database
        $query = clientModel::execute_simple_query("SELECT cliente_dni
                                                    FROM cliente
                                                    WHERE cliente_dni = '$dni'");
        if ($query->rowCount() > 0) {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                        "¡Ya existe un cliente con este DNI registrado en el sistema!");
            return $res;
        }

        $data_client_reg = [
            "dni" => $dni,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "telefono" => $telefono,
            "direccion" => $direccion
        ];

        $query = clientModel::add_client_model($data_client_reg);

        if ($query->rowCount() == 1) {
            $res = clientModel::message_with_parameters("clean", "success", "Cliente registrado",
                                                        "Los datos del usuario han sido registrados con éxito.");
        } else {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrio un error inesperado",
                                                        "No hemos podido registrar el cliente.");
        }
        return $res;
    }
 }
