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
        $perfil = mainModel::clean_string($_POST['usuario_perfil_reg']);

        $usuario = mainModel::clean_string($_POST['usuario_usuario_reg']);
        $email = mainModel::clean_string($_POST['usuario_email_reg']);
        $clave1 = mainModel::clean_string($_POST['usuario_clave_1_reg']);
        $clave2 = mainModel::clean_string($_POST['usuario_clave_2_reg']);

        $privilegio = mainModel::clean_string($_POST['usuario_privilegio_reg']);

        /*== Check empty fields ==*/
        if ($dni == "" || $nombre == "" || $apellido == "" ||
            $usuario == "" || $email == "" || $clave1 == "" ||
            $clave2 == "") {

            $res = mainModel::message_with_parameters("simple", "error", "Ocurrio un error inesperado",
                                                      "No has llenado todos los campos requeridos");
            return $res;
        }

        /*==  Check data's ingrity ==*/
        /*== Check DNI ==*/
        if (mainModel::check_data("[0-9]{8}[-]{1}[TRWAechoGMYFPDXBNJZSQVHLCKE]{1}", $dni)) {
            $res = mainModel::message_with_parameters("simple", "error", "Formato de DNI erróneo",
                                                      "El DNI no coincide con el formato solicitado.");
            return $res;
        }

        /* Check first name */
        if (mainModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $nombre)) {
            $res = mainModel::message_with_parameters("simple", "error", "Formato de Nombre erróneo",
                                                      "El Nombre no coincide con el formato solicitado.");
            return $res;
        }

        /* Check last name */
        if (mainModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellido)) {
            $res = mainModel::message_with_parameters("simple", "error", "Formato de Apellido erróneo",
                                                      "El Apellido no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check phone ==*/
        if ($telefono != "" && mainModel::check_data("[0-9()+]{9,20}", $telefono)) {
            $res = mainModel::message_with_parameters("simple", "error", "Formato de Teléfono erróneo",
                                                      "El Teléfono no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check direction ==*/
        if ($direccion != "" && mainModel::check_data("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
            $res = mainModel::message_with_parameters("simple", "error", "Formato de Dirección erróneo",
                                                      "La Dirección no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check user name ==*/
        if (mainModel::check_data("[a-zA-Z0-9]{1,35}", $usuario)) {
            $res = mainModel::message_with_parameters("simple", "error", "Formato de Usuario erróneo",
                                                      "El Usuario no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check email ==*/
        if (mainModel::check_data("[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$", $email)) {
            $res = mainModel::message_with_parameters("simple", "error", "Formato de Email erróneo",
                                                      "El Email no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check passwords ==*/
        if ($clave1 == $clave2) {
            if (mainModel::check_data("^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,100}$", $clave1)) {
                $res = mainModel::message_with_parameters("simple", "error", "Formato de Contraseña erróneo",
                                                          "La Contraseña no coincide con el formato solicitado.");
                return $res;
            } else {
                $clave = mainModel::encryption($clave1);
            }
        } else {
            $res = mainModel::message_with_parameters("simple", "error", "Contraseñas diferentes",
                                                      "Las Contraseñas deben coincidir.");
            return $res;
        }

        /*== Check Privilege ==*/
        if ($privilegio < 1 || $privilegio > 3) {
            $res = mainModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "El Privilegio seleccionado no es valido");
            return $res;
        }

        /*== Check DNI as unique data ==*/
        $query = mainModel::execute_simple_query("SELECT usuario_dni
                                                  FROM usuario
                                                  WHERE usuario_dni = '$dni'");
        if ($query->rowCount() > 0) {
            $res = mainModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El DNI ya se encuentra registrado en el sistema!");
            return $res;
        }

        /*== Check Perfil as a record stored in database ==*/
        if ($perfil != "" && $perfil != "Seleccione") {
            $query = mainModel::execute_simple_query("SELECT perfil_id
                                                      FROM perfil
                                                      WHERE perfil_nombre = '$perfil'");
            if ($query->rowCount() != 1 ) {
                $res = mainModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                          "¡El perfil seleccionado no se encuentra registrado en el sistema!");
                return $res;
            } else {
                $row = $query->fetch();
                $perfil_id = $row['perfil_id'];;
            }

        } else {
            $perfil_id = NULL;
        }
        /*==  Check User as unique data ==*/
        $query = mainModel::execute_simple_query("SELECT usuario_usuario
                                                  FROM usuario
                                                  WHERE usuario_usuario = '$usuario'");
        if ($query->rowCount() > 0) {
            $res = mainModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El nombre de usuario ya se encuentra registrado en el sistema!");
            return $res;
        }

        /*==  Check EMAIL as unique data ==*/
        $query = mainModel::execute_simple_query("SELECT usuario_email
                                                  FROM usuario
                                                  WHERE usuario_email = '$email'");
        if ($query->rowCount() > 0) {
            $res = mainModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El nombre email ya se encuentra registrado en el sistema!");
            return $res;
        }

        $data_user_reg = [
            "dni" => $dni,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "perfil" => is_null($perfil_id) ? $perfil_id : (int) $perfil_id,
            "email" => $email,
            "usuario" => $usuario,
            "clave" => $clave,
            "estado" => "Activa",
            "privilegio" => (int) $privilegio
        ];

        $query = userModel::add_user_model($data_user_reg);

        if ($query->rowCount() == 1) {
            $res = mainModel::message_with_parameters("clean", "success", "Usuario registrado",
                                                      "Los datos del usuario han sido registrado con exito.");
        } else {
            $res = mainModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                      "No hemos podido registrar el usuario.");
        }
        return $res;
    }

    public function message_user_controller($alert, $type, $title, $text) {
        return mainModel::message_with_parameters($alert, $type, $title, $text);
    }
}
