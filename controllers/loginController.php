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

include_once "./models/loginModel.php";

/*--- Class Login Controller ---*/
class loginController extends loginModel {
    /*-- Controller's function for user login */
    public function login_controller() {
        $usuario = loginModel::clean_string($_POST['usuario_log']);
        $clave = loginModel::clean_string($_POST['clave_log']);

        /* Check empy fields */
        if ($usuario == "" || $clave == "") {
            echo '
            <script>
                Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "No has llenado todos los campos requeridos",
                    type: "error",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            </script>
            ';

            return;
        }

        /* Check data's integrity */
        /* Check user */
        if (loginModel::check_data("[a-zA-Z0-9]{1,35}", $usuario)) {
            echo '
            <script>
                Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "El Usuario no coincide con el formato solicitado.",
                    type: "error",
                    type: "warning",
                    confirmButtonText: "Aceptar"
                });
            </script>
            ';

            return;
        }

        /* Check password */
        if (loginModel::check_data("^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,100}$", $clave)) {
            echo '
            <script>
                Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "La Contraseña no coincide con el formato solicitado.",
                    type: "error",
                    icon: "warning",
                    confirmButtonText: "Aceptar"
                });
            </script>
            ';

            return;
        }

        $clave_encryted = loginModel::encryption($clave);

        $data_login = [
            "usuario" => $usuario,
            "clave" => $clave_encryted
        ];

        $query = loginModel::login_model($data_login);

        if ($query->rowCount() == 1) {
            $row = $query->fetch();

            session_start(['name'=>'SPM']);

            $_SESSION['id_spm'] = $row['usuario_id'];
            $_SESSION['nombre_spm'] = $row['usuario_nombre'];
            $_SESSION['apellido_spm'] = $row['usuario_apellido'];
            $_SESSION['usuario_spm'] = $row['usuario_usuario'];
            $_SESSION['privilegio_spm'] = $row['usuario_privilegio'];
            $_SESSION['perfil_spm'] = $row['perfil_nombre'];
            $_SESSION['token_spm'] = md5(uniqid(mt_rand(), true));

            return header("Location: " . SERVER_URL . "home/");
        } else {
            echo '
            <script>
                Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "El usuario o la clave son incorrectos.",
                    type: "error",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            </script>
            ';

            return;
        }
    }

    /* Controller's function for forse close session */
    public function force_close_session_controller() {
        session_unset();
        session_destroy();

        if (headers_sent()) { // If headers are being sent,
            return "<script> window.location.href='" . SERVER_URL . "login/'; </script>";
        } else {
            return header("Location: " . SERVER_URL . "login/");
        }
    }

    /* Controller's function for close session */
    public function close_session_controller() {
        session_start(['name' => 'SPM',]);

        $token = loginModel::decryption($_POST['token']);
        $usuario = loginModel::decryption($_POST['usuario']);

        if ($token == $_SESSION['token_spm'] && $usuario == $_SESSION['usuario_spm']) {
            session_unset();
            session_destroy();
            $res = loginModel::message_with_parameters("redirect", NULL, NULL, NULL, SERVER_URL . "login/");
        } else {
            $res = loginModel::message_with_parameters("simple", "error", "Ocurrió un error inesperado",
                                                       "No se puedo cerrar la sesión en el sistema!");
        }
        return $res;
    }

    public function encrypt_data($string) {
        return loginModel::encryption($string);
    }
}