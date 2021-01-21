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

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

include_once "./models/userModel.php";

/*--- Class User Controller ---*/
class userController extends userModel {

    /*-- Controller's function for add user --*/
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

        // Check empty fields
        if ($dni == "" || $nombre == "" || $apellido == "" ||
            $usuario == "" || $email == "" || $clave1 == "" ||
            $clave2 == "") {

            $res = userModel::message_with_parameters("simple", "error", "Ocurrió un error inesperado",
                                                      "No has llenado todos los campos requeridos.");
            return $res;
        }

        // Check data's ingrity
        // Check DNI
        if (userModel::check_data("[0-9]{8}[-]{1}[TRWAechoGMYFPDXBNJZSQVHLCKE]{1}", $dni)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de DNI erróneo",
                                                      "El DNI no coincide con el formato solicitado.");
            return $res;
        }

        // Check first name
        if (userModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $nombre)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Nombre erróneo",
                                                      "El Nombre no coincide con el formato solicitado.");
            return $res;
        }

        // Check last name
        if (userModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellido)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Apellido erróneo",
                                                      "El Apellido no coincide con el formato solicitado.");
            return $res;
        }

        // Check phone
        if ($telefono != "" && userModel::check_data("[0-9()+]{9,20}", $telefono)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Teléfono erróneo",
                                                      "El Teléfono no coincide con el formato solicitado.");
            return $res;
        }

        //Check direction
        if ($direccion != "" && userModel::check_data("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Dirección erróneo",
                                                      "La Dirección no coincide con el formato solicitado.");
            return $res;
        }

        // Check Perfil as a record stored in database
        if ($perfil != "" && $perfil != "Seleccione") {
            $query = userModel::execute_simple_query("SELECT perfil_id
                                                      FROM perfil
                                                      WHERE perfil_nombre = '$perfil'");
            if ($query->rowCount() != 1) {
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

        // Check user name
        if (userModel::check_data("[a-zA-Z0-9]{1,35}", $usuario)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Usuario erróneo",
                                                      "El Usuario no coincide con el formato solicitado.");
            return $res;
        }

        // Check email
        if (userModel::check_data("[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$", $email)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Email erróneo",
                                                      "El Email no coincide con el formato solicitado.");
            return $res;
        }

        // Check passwords
        if ($clave1 == $clave2) {
            if ( userModel::check_data("^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,100}$", $clave1) ) {
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

        // Check Privilege
        if ($privilegio < 1 || $privilegio > 3) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "!El Privilegio seleccionado no es valido!");
            return $res;
        }

        // Check DNI as unique data
        $query = userModel::execute_simple_query("SELECT usuario_dni
                                                  FROM usuario
                                                  WHERE usuario_dni = '$dni'");
        if ($query->rowCount() > 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El DNI ya se encuentra registrado en el sistema!");
            return $res;
        }

        // Check user as unique data
        $query = userModel::execute_simple_query("SELECT usuario_usuario
                                                  FROM usuario
                                                  WHERE usuario_usuario = '$usuario'");
        if ($query->rowCount() > 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El nombre de usuario ya se encuentra registrado en el sistema!");
            return $res;
        }

        // Check email as unique data
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

    /*-- Controller's function for sent message user --*/
    public function message_user_controller($alert, $type, $title, $text) {
        return userModel::message_with_parameters($alert, $type, $title, $text);
    }

    /*-- Controller's function for users pagination --*/
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

        if ($total >= 1 && $page <= $nPages) {
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
                <tr class="text-center"
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

        if ($total >= 1 && $page <= $nPages) {
            $html .= '<p class="text-right">Mostrando usuario(s): ' . $start_record . ' al ' . $end_record . ' de un total de ' . $total . '</p>';

            $html .= userModel::pagination_tables($page, $nPages, $url, $total_buttons);
        }

        return $html;
    }

    /*-- Controller's function for delete user --*/
    public function delete_user_controller() {
        /* reciving user id */
        $id = userModel::decryption($_POST['usuario_id_del']);
        $id = userModel::clean_string($id);

        // Checking primary user
        if ($id == 1) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "No podemos eliminar el usuario principal del sistema.");
            return $res;
        }

        // Checking that the user exists in the database
        $sql = "SELECT usuario.usuario_id
                FROM usuario
                WHERE usuario.usuario_id = '$id'";
        $query = userModel::execute_simple_query($sql);

        if (!$query->rowCount() > 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El usuario que intenta eliminar no existe en el sistema!");
            return $res;
        }

        // Checking if the user has associated loan redords
        $sql = "SELECT prestamo.usuario_id
                FROM prestamo
                WHERE prestamo.usuario_id = '$id'
                LIMIT 1";
        $query = userModel::execute_simple_query($sql);
        if ($query->rowCount() > 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "No podemos eliminar el usuario seleccionado debido a que tiene prestamos asociados, recomendamos deshabilitar el usuario.");
            return $res;
        }

        // Checking privileges of current user
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

    /*-- Controller's function for query data user --*/
    public function query_data_user_controller($type, $id = NULL) {
        $type = userModel::clean_string($type);

        if (!is_null($id)) {
            $id = userModel::decryption($id);
            $id = userModel::clean_string($id);
        }
        return userModel::query_data_user_model($type, $id);
    }

    /*-- Controller's function for query perfil list user --*/
    public function query_perfil_list_user_model() {
        return userModel::perfil_list_user_model();
    }

    /*-- Controller's function update user data --*/
    public function update_user_data_controller() {
        // Receiving the id
        $id = userModel::decryption($_POST['usuario_id_upd']);
        $id = userModel::clean_string($id);
        $id = (int) $id;

        // Checking user id in the database
        $sql = "SELECT usuario.*
                FROM usuario
                WHERE usuario.usuario_id = '$id'";
        $query = userModel::execute_simple_query($sql);

        if ($query->rowCount() == 1) {
            $fields = $query->fetch();
        } else {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                      "El usuario no existe en base de datos, intente nuevamente.");
            return $res;
        }

        $dni = userModel::clean_string($_POST['usuario_dni_upd']);
        $nombre = userModel::clean_string($_POST['usuario_nombre_upd']);
        $apellido = userModel::clean_string($_POST['usuario_apellido_upd']);
        $telefono = userModel::clean_string($_POST['usuario_telefono_upd']);
        $direccion = userModel::clean_string($_POST['usuario_direccion_upd']);
        $perfil = userModel::clean_string($_POST['usuario_perfil_upd']);

        $usuario = userModel::clean_string($_POST['usuario_usuario_upd']);
        $email = userModel::clean_string($_POST['usuario_email_upd']);

        $estado = isset($_POST['usuario_estado_upd']) ? userModel::clean_string($_POST['usuario_estado_upd']) : $fields['usuario_estado'];

        $clave1 = userModel::clean_string($_POST['usuario_clave_nueva_1_upd']);
        $clave2 = userModel::clean_string($_POST['usuario_clave_nueva_2_upd']);

        $privilegio = isset($_POST['usuario_privilegio_upd']) ? userModel::clean_string($_POST['usuario_privilegio_upd']) : $fields['usuario_privilegio'];

        $admin_usuario = userModel::clean_string($_POST['usuario_admin']);
        $admin_clave = userModel::clean_string($_POST['clave_admin']);

        $account_type = userModel::clean_string($_POST['account_type']);

        // Check empty fields
        if ($dni == "" || $nombre == "" || $apellido == "" ||
            $usuario == "" || $email == "" || $admin_usuario == "" ||
            $admin_clave == "") {

            $res = userModel::message_with_parameters("simple", "error", "Ocurrió un error inesperado",
                                                      "No has llenado todos los campos requeridos.");
            return $res;
        }

        // Check data's ingrity
        // Check DNI
        if (userModel::check_data("[0-9]{8}[-]{1}[TRWAechoGMYFPDXBNJZSQVHLCKE]{1}", $dni)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de DNI erróneo",
                                                      "El DNI no coincide con el formato solicitado.");
            return $res;
        }

        // Check first name
        if (userModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $nombre)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Nombre erróneo",
                                                      "El Nombre no coincide con el formato solicitado.");
            return $res;
        }

        // Check last name
        if (userModel::check_data("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellido)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Apellido erróneo",
                                                      "El Apellido no coincide con el formato solicitado.");
            return $res;
        }

        // Check phone
        if ($telefono != "" && userModel::check_data("[0-9()+]{9,20}", $telefono)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Teléfono erróneo",
                                                      "El Teléfono no coincide con el formato solicitado.");
            return $res;
        }

        // Check direction
        if ($direccion != "" && userModel::check_data("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Dirección erróneo",
                                                      "La Dirección no coincide con el formato solicitado.");
            return $res;
        }

        // Check Perfil as a record stored in database
        $perfil_id = !is_null($fields['usuario_perfil_id']) ? (int) $fields['usuario_perfil_id'] : NULL;

        if ($perfil != "" && $perfil != "Seleccione") {
            $query = userModel::execute_simple_query("SELECT perfil_id
                                                      FROM perfil
                                                      WHERE perfil_id = '$perfil'");
            if ($query->rowCount() != 1) {
                $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                          "¡El perfil seleccionado no se encuentra registrado en el sistema!");
                return $res;
            } else {
                $row = $query->fetch();
                $perfil_id = (int) $row['perfil_id'];;
            }
        }

        // Check user name
        if (userModel::check_data("[a-zA-Z0-9]{1,35}", $usuario)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Usuario erróneo",
                                                      "El Usuario no coincide con el formato solicitado.");
            return $res;
        }

        // Check email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check email as unique data
            if ($email != $fields['usuario_email']) {
                $query = userModel::execute_simple_query("SELECT usuario_email
                                                          FROM usuario
                                                          WHERE usuario_email = '$email'");
                if ($query->rowCount() > 0) {
                    $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                              "¡El Email ya se encuentra registrado en el sistema!");
                    return $res;
                }
            }
        } else {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Email erróneo",
                                                      "El Email no coincide con el formato solicitado.");
            return $res;
        }

        // Check status
        if ($estado != "Activa" && $estado != "Deshabilitada") {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "!El Estado de la cuenta no es valido!");
            return $res;
        }

        // Check passwords
        if ($clave1 != "" || $clave2 != "") {
            if ($clave1 == $clave2) {
                if (userModel::check_data("^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,100}$", $clave1)) {
                    $res = userModel::message_with_parameters("simple", "error", "Formato de Contraseña erróneo",
                                                              "La Contraseña no coincide con el formato solicitado.");
                    return $res;
                }
                $clave = userModel::encryption($clave1);
            } else {
                $res = userModel::message_with_parameters("simple", "error", "Contraseñas diferentes",
                                                          "Las nuevas Contraseñas no coinciden.");
                return $res;
            }
        } else {
            $clave = $fields['usuario_clave'];
        }

        // Check Privilege
        $privilegio = (int) $privilegio;

        if ($privilegio < 1 || $privilegio > 3) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "!El Privilegio seleccionado no es valido!");
            return $res;
        }

        // Check admin user
        if (userModel::check_data("[a-zA-Z0-9]{1,35}", $admin_usuario)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Usuario erróneo",
                                                      "Tu usuario no coincide con el formato solicitado.");
            return $res;
        }

        // Check admin clave
        if (userModel::check_data("^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,100}$", $admin_clave)) {
            $res = userModel::message_with_parameters("simple", "error", "Formato de Clave erróneo",
                                                      "Tu clave de usuario no coincide con el formato solicitado.");
            return $res;
        }

        $admin_clave = userModel::encryption($admin_clave);

        // Check DNI as unique data
        if ($dni != $fields['usuario_dni']) {
            $query = userModel::execute_simple_query("SELECT usuario_dni
                                                      FROM usuario
                                                      WHERE usuario_dni = '$dni'");
            if ($query->rowCount() > 0) {
                $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                          "¡El DNI ya se encuentra registrado en el sistema!");
                return $res;
            }
        }

        // Check user as unique data
        if ($usuario != $fields['usuario_usuario']) {
            $query = userModel::execute_simple_query("SELECT usuario_usuario
                                                      FROM usuario
                                                      WHERE usuario_usuario = '$usuario'");
            if ($query->rowCount() > 0) {
                $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                        "¡El nombre de usuario ya se encuentra registrado en el sistema!");
                return $res;
            }
        }

        // Checking credentials to update data
        if ($account_type == "Propia") {
            $sql = "SELECT usuario_id
                    FROM usuario
                    WHERE usuario_usuario = '$admin_usuario'
                    AND usuario_clave = '$admin_clave'
                    AND usuario_id = '$id'";
            $query = userModel::execute_simple_query($sql);
        } else {
            session_start(['name' => 'SPM']);
            if ($_SESSION['privilegio_spm'] != 1) {
                $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                          "¡No tienes los permisos necesarios para realizar esta operación!");
                return $res;
            }

            $admin_id = (int) $_SESSION['id_spm'];

            $sql = "SELECT usuario_id
                    FROM usuario
                    WHERE usuario_usuario = '$admin_usuario'
                    AND usuario_clave = '$admin_clave'
                    AND usuario_id = $admin_id";
            $query = userModel::execute_simple_query($sql);
        }

        if ($query->rowCount() <= 0) {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "¡El Nombre o la Clave de Administrador no son validos!");
            return $res;
        }

        // Preparing data to send to the model
        $data = [
            "dni" => $dni,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "email" => $email,
            "usuario" => $usuario,
            "clave" => $clave,
            "estado" => $estado,
            "privilegio" => $privilegio,
            "perfil_id" => $perfil_id,
            "id" => $id
        ];

        // Sending data to update user model
        if (userModel::update_user_data_model($data)) {
            $res = userModel::message_with_parameters("reload", "success", "Datos Actualizados",
                                                      "Los datos han sido actualizados con éxito.");
        } else {
            $res = userModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                      "No se hemos podido actualizar los datos, por favor, intente nuevamente.");
        }

        return $res;
    }
}
