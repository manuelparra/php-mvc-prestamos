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

/*--- Class User Controller ---*/
class userController extends userModel {

    /*--- Controller's function to add user ---*/
    public function add_user_controller() {
        $dni = mainModel::clean_string($_POST['usuario_dni_reg']);
        $nombre = mainModel::clean_string($_POST['usuario_nombre_reg']);
        $apellido = mainModel::clean_string($_POST['usuario_apellido_reg']);
        $telefono = mainModel::clean_string($_POST['usuario_telefono_reg']);
        $direccion = mainModel::clean_string($_POST['usuario_direccion_reg']);

        $usuario = mainModel::clean_string($_POST['usuario_usuario_reg']);
        $email = mainModel::clean_string($_POST['usuario_email_reg']);
        $clave1 = mainModel::clean_string($_POST['usuario_clave_1_reg']);
        $clave2 = mainModel::clean_string($_POST['usuario_clave_2_reg']);

        $privilegio = mainModel::clean_string($_POST['usuario_privilegio_reg']);

        /*== Check empty fields ==*/
        if ($dni == "" || $nombre == "" || $apellido == "" ||
            $usuario == "" || $email == "" || $clave1 == "" ||
            $clave2 == "") {

            $res = $this->message_with_parameters("simple", "error", "No has llenado todos los campos requeridos",
                                                  "Ocurrio un error inersperado");
            return $res;
        }

        /*==  Check data's ingrity ==*/

        /*== Check DNI ==*/
        if (mainModel::check_data("[0-9]{8}[-]{1}[TRWAGMYFPDXBNJZSQVHLCKE]{1}", $dni)) {
            $res = $this->message_with_parameters("simple", "error", "El DNI no coincide con el formato solicitado.",
                                                  "Formato de DNI erróneo");
            return $res;
        }

        /* Check first name */
        if (mainModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $nombre)) {
            $res = $this->message_with_parameters("simple", "error", "El Nombre no coincide con el formato solicitado.",
                                                  "Formato de Nombre erróneo");
            return $res;
        }

        /* Check last name */
        if (mainModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellido)) {
            $res = $this->message_with_parameters("simple", "error", "El Apellido no coincide con el formato solicitado.",
                                                  "Formato de Apellido erróneo");
            return $res;
        }

        /*== Check phone ==*/
        if ($telefono != "" && mainModel::check_data("[0-9()+]{9,20}", $telefono)) {
            $res = $this->message_with_parameters("simple", "error", "El Teléfono no coincide con el formato solicitado.",
                                                  "Formato de Teléfono erróneo");
            return $res;
        }

        /*== Check direction ==*/
        if ($direccion != "" && mainModel::check_data("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
            $res = $this->message_with_parameters("simple", "error", "La Dirección no coincide con el formato solicitado.",
                                                  "Formato de Dirección erróneo");
            return $res;
        }

        /*== Check user name ==*/
        if (mainModel::check_data("[a-zA-Z0-9]{1,35}", $usuario)) {
            $res = $this->message_with_parameters("simple", "error", "El Usuario no coincide con el formato solicitado.",
                                                  "Formato de Usuario erróneo");
            return $res;
        }

        /*== Check email ==*/
        if (mainModel::check_data("[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$", $email)) {
            $res = $this->message_with_parameters("simple", "error", "El Email no coincide con el formato solicitado.",
                                                  "Formato de Email erróneo");
            return $res;
        }

        /*== Check passwords ==*/
        if ($clave1 == $clave2) {
            if (mainModel::check_data("(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}", $clave1)) {
                $res = $this->message_with_parameters("simple", "error", "La Contraseña no coincide con el formato solicitado.",
                                                      "Formato de Contraseña erróneo");
                return $res;
            } else {
                $clave = mainModel::encryption($clave1);
            }
        } else {
            $res = $this->message_with_parameters("simple", "error", "Las Contraseñas deben coincidir",
                                                  "Contraseñas diferentes");
            return $res;
        }

        /*== Check Privilege ==*/
        if ($privilegio < 1 || $privilegio > 3) {
            $res = $this->message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                  "El Privilegio seleccionado no es valido");
            return $res;
        }

        /*==  Check DNI as unique data ==*/
        $query = mainModel::execute_simple_query("SELECT usuario_dni
                                                  FROM usuario
                                                  WHERE usuario_dni = '$dni'");
        if ($query->rowCount() > 0) {
            $res = $this->message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                  "¡El DNI ya se encuentra registrado en el sistema!");
            return $res;
        }

        /*==  Check USER as unique data ==*/
        $query = mainModel::execute_simple_query("SELECT usuario_usuario
                                                  FROM usuario
                                                  WHERE usuario_usuario = '$usuario'");
        if ($query->rowCount() > 0) {
            $res = $this->message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                  "¡El nombre de usuario ya se encuentra registrado en el sistema!");
            return $res;
        }

        /*==  Check EMAIL as unique data ==*/
        $query = mainModel::execute_simple_query("SELECT usuario_email
                                                  FROM usuario
                                                  WHERE usuario_email = '$email'");
        if ($query->rowCount() > 0) {
            $res = $this->message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                  "¡El nombre email ya se encuentra registrado en el sistema!");
            return $res;
        }

        $data_user_reg = [
            "dni" => $dni,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "email" => $email,
            "usuario" => $usuario,
            "clave" => $clave,
            "estado" => "Activa",
            "privilegio" => (int) $privilegio
        ];

        $query = userModel::add_user_model($data_user_reg);

        if ($query->rowCount() == 1) {
            $res = $this->message_with_parameters("clean", "success", "Usuario registrado",
                                                  "Los datos del usuario han sido registrado con exito");
            return $res;
        } else {
            $res = $this->message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                  "No hemos podido registrar el usuario");
            return $res;
        }
    }

    public function message_with_parameters($alert, $type, $title, $text) {
        return json_encode($alert_config = [
            "alert" => $alert,
            "type" => $type,
            "title" => $title,
            "text" => $text
        ]);
    }
}