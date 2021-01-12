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
        $dni = userModel::clean_string($_POST['usuario_dni_reg']);
        $nombre = userModel::clean_string($_POST['usuario_nombre_reg']);
        $apellido = userModel::clean_string($_POST['usuario_apellido_reg']);
        $telefono = userModel::clean_string($_POST['usuario_telefono_reg']);
        $direccion = userModel::clean_string($_POST['usuario_direccion_reg']);
        $perfil = userModel::clean_string($_POST['usuario_perfil_reg']);

        $usuario = userModel::clean_string($_POST['usuario_usuario_reg']);
        $email = userModel::clean_string($_POST['usuario_email_reg']);
        $clave1 = userModel::clean_string($_POST['usuario_clave_1_reg']);
        $clave2 = userModel::clean_string($_POST['usuario_clave_2_reg']);

        $privilegio = userModel::clean_string($_POST['usuario_privilegio_reg']);

        /*== Check empty fields ==*/
        if ($dni == "" || $nombre == "" || $apellido == "" ||
            $usuario == "" || $email == "" || $clave1 == "" ||
            $clave2 == "") {

            $res = userModel::message_with_parameters("simple", "error", "Ocurrio un error inesperado",
                                                      "No has llenado todos los campos requeridos");
            return $res;
        }

        /*==  Check data's ingrity ==*/
        /*== Check DNI ==*/
        if (userModel::check_data("[0-9]{8}[-]{1}[TRWAechoGMYFPDXBNJZSQVHLCKE]{1}", $dni)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de DNI erróneo",
                                                      "El DNI no coincide con el formato solicitado.");
            return $res;
        }

        /* Check first name */
        if (userModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $nombre)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Nombre erróneo",
                                                      "El Nombre no coincide con el formato solicitado.");
            return $res;
        }

        /* Check last name */
        if (userModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellido)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Apellido erróneo",
                                                      "El Apellido no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check phone ==*/
        if ($telefono != "" && userModel::check_data("[0-9()+]{9,20}", $telefono)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Teléfono erróneo",
                                                      "El Teléfono no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check direction ==*/
        if ($direccion != "" && userModel::check_data("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Dirección erróneo",
                                                      "La Dirección no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check user name ==*/
        if (userModel::check_data("[a-zA-Z0-9]{1,35}", $usuario)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Usuario erróneo",
                                                      "El Usuario no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check email ==*/
        if (userModel::check_data("[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$", $email)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Email erróneo",
                                                      "El Email no coincide con el formato solicitado.");
            return $res;
        }

        /*== Check passwords ==*/
        if ($clave1 == $clave2) {
            if (userModel::check_data("^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,100}$", $clave1)) {
                $res = userModel::message_with_parameters("simple", "error", "Formato de Contraseña erróneo",
                                                          "La Contraseña no coincide con el formato solicitado.");
                return $res;
            } else {
                $clave = userModel::encryption($clave1);
            }
        } else {
            $res = userModel::message_with_parameters("simple", "error", "Contraseñas diferentes",
                                                      "Las Contraseñas deben coincidir.");
            return $res;
        }

        /*== Check Privilege ==*/
        if ($privilegio < 1 || $privilegio > 3) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "El Privilegio seleccionado no es valido");
            return $res;
        }

        /*== Check DNI as unique data ==*/
        $query = userModel::execute_simple_query("SELECT usuario_dni
                                                  FROM usuario
                                                  WHERE usuario_dni = '$dni'");
        if ($query->rowCount() > 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El DNI ya se encuentra registrado en el sistema!");
            return $res;
        }

        /*== Check Perfil as a record stored in database ==*/
        if ($perfil != "" && $perfil != "Seleccione") {
            $query = userModel::execute_simple_query("SELECT perfil_id
                                                      FROM perfil
                                                      WHERE perfil_nombre = '$perfil'");
            if ($query->rowCount() != 1 ) {
                $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
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
        $query = userModel::execute_simple_query("SELECT usuario_usuario
                                                  FROM usuario
                                                  WHERE usuario_usuario = '$usuario'");
        if ($query->rowCount() > 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El nombre de usuario ya se encuentra registrado en el sistema!");
            return $res;
        }

        /*==  Check EMAIL as unique data ==*/
        $query = userModel::execute_simple_query("SELECT usuario_email
                                                  FROM usuario
                                                  WHERE usuario_email = '$email'");
        if ($query->rowCount() > 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
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
            $res = userModel::message_with_parameters("clean", "success", "Usuario registrado",
                                                      "Los datos del usuario han sido registrado con exito.");
        } else {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                      "No hemos podido registrar el usuario.");
        }
        return $res;
    }

    /*--- Controller's function to sent message user ---*/
    public function message_user_controller($alert, $type, $title, $text) {
        return userModel::message_with_parameters($alert, $type, $title, $text);
    }

    /*--- Controller's function to user pagination ---*/
    public function paginator_user_controller($page, $records, $privilege, $id, $url, $search) {
        $page = userModel::clean_string($page);
        $records = userModel::clean_string($records);
        $privilege = userModel::clean_string($privilege);
        $id = userModel::clean_string($id);
        $url = userModel::clean_string($url);

        $url = SERVER_URL . $url . "/";

        $search = userModel::clean_string($search);

        $table = "";
        $html = "";

        $page = (isset($page) && $page > 0) ? (int) $page : 1;

        $start = $page > 0 ? (($page * $records) - $records) : 0;

        if (isset($search) && $search != "") {
            $sql = "SELECT SQL_CALC_FOUND_ROWS *
                    FROM usuario
                    WHERE ((usuario.usuario_id != $id
                    AND usuario.usuario_id != 1)
                    AND (usuario.usuario_dni LIKE '%$search%'
                    OR usuario.usuario_nombre LIKE '%$search%'
                    OR usuario.usuario_apellido LIKE '%$search%'
                    OR usuario.usuario_telefono LIKE '%$search%'
                    OR usuario.usuario_usuario LIKE '%$search%'
                    OR usuario.usuario_email LIKE '%$search%'))
                    ORDER BY usuario.usuario_nombre ASC
                    LIMIT $start, $records";

        } else {
            $sql = "SELECT SQL_CALC_FOUND_ROWS *
                    FROM usuario
                    WHERE usuario.usuario_id != $id
                    AND usuario.usuario_id != 1
                    ORDER BY usuario.usuario_nombre ASC
                    LIMIT $start, $records";
        }

        $db_cnn = userModel::connection();

        $query = $db_cnn->query($sql);
        $rows = $query->fetchAll();

        $total = $db_cnn->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $nPages = ceil($total / $records);

        $table .= '
        <div class="table-responsive">
            <table class="table table-dark table-sm">
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>#</th>
                        <th>DNI</th>
                        <th>NOMBRE</th>
                        <th>APELLIDO</th>
                        <th>TELÉFONO</th>
                        <th>USUARIO</th>
                        <th>EMAIL</th>
                        <th>ACTUALIZAR</th>
                        <th>ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>
        ';

        if ( $total >= 1 && $page <= $nPages ) {
            $count = $start + 1;
            $start_record = $start + 1;

            foreach ($rows as $row) {
                $table .= '
                <tr class="text-center" >
                    <td>' . $count . '</td>
                    <td>' . $row['usuario_dni'] . '</td>
                    <td>' . $row['usuario_nombre'] . '</td>
                    <td>' . $row['usuario_apellido'] . '</td>
                    <td>' . $row['usuario_telefono'] . '</td>
                    <td>' . $row['usuario_usuario'] . '</td>
                    <td>' . $row['usuario_email'] . '</td>
                    <td>
                        <a href="' . SERVER_URL . 'user-update/' . userModel::encryption($row['usuario_id'])  . '/" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </td>
                    <td>
                        <form class="ajax-form"  action="' . SERVER_URL . 'endpoint/user-ajax/" method="POST" data-form="delete" autocomplete="off">
                            <input type="hidden" name="usuario_id_del" value="' . userModel::encryption($row['usuario_id']) . '">
                            <button type="submit" class="btn btn-warning">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                ';
                $count++;
            }

            $end_record = $count - 1;
        } else {
            if ($total >= 1) {
                $table .= '
                <tr class="text-center" >
                    <td colspan="9"><a href="' . $url . '" class="btn btn-primary btn-raised btn-sm">Haga clic aca para recargar el listado</a></td>
                </tr>
                ';
            } else {
                $table .= '
                <tr class="text-center" >
                    <td colspan="9">No hay registros en el sistema</td>
                </tr>
                ';
            }
        }

        $table .= '
                </tbody>
            </table>
        </div>
        ';

        $buttons = 5;
        $total_buttons = $nPages >= $buttons ?  $buttons : $nPages;

        $html = $table;

        if ( $total >= 1 && $page <= $nPages ) {
            $html .= '<p class="text-right">Mostrando usuario(s): ' . $start_record . ' al ' . $end_record . ' de un total de ' . $total . '</p>';

            $html .= userModel::pagination_tables($page, $nPages, $url, $total_buttons);
        }

        return $html;
    }

    /*--- Controller's function to delete user ---*/
    public function delete_user_controller() {
        /* reciving user id */
        $id = userModel::decryption($_POST['usuario_id_del']);
        $id = userModel::clean_string($id);

        /* Checking primary user  */
        if ($id == 1) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡No podemos eliminar el usuario principal del sistema!");
            return $res;
        }

        /* Checking that the user exists in the database */
        $sql = "SELECT usuario.usuario_id
                FROM usuario
                WHERE usuario.usuario_id = '$id'";
        $query = userModel::execute_simple_query($sql);

        if ( !$query->rowCount() > 0 ) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El usuario que intenta eliminar no existe en el sistema!");
            return $res;
        }

        /* Checking if the user has associated loan redords */
        $sql = "SELECT prestamo.usuario_id
                FROM prestamo
                WHERE prestamo.usuario_id = '$id'
                LIMIT 1";
        $query = userModel::execute_simple_query($sql);
        if ( $query->rowCount() > 0 ) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡No podemos eliminar el usuario seleccionado debido a que tiene prestamos asociados, recomendamos deshabilitar el usuario!");
            return $res;
        }

        /* Checking privileges of current user */
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] != 1) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡No tienes los permisos necesarios para realizar esta operación!");
            return $res;
        }

        $query = userModel::delete_user_model($id);
        if ($query->rowCount() == 1) {
            $res = userModel::message_with_parameters("reload", "success", "Usuario eliminado",
                                                      "El usuario ha sido eliminado del sistema exitosamente.");
        } else {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                      "No hemos podido eliminar el usuario, por favor, intente nuevamente.");
        }
        return $res;
    }
}
