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

    /*-- Controller's function for client pagination --*/
    public function paginator_client_controller($page, $records, $privilege, $url, $search) {
        $page = clientModel::clean_string($page);
        $records = clientModel::clean_string($records);
        $privilege = clientModel::clean_string($privilege);

        $url = clientModel::clean_string($url);
        $url = SERVER_URL . $url . "/";

        $search = clientModel::clean_string($search);

        $table = "";
        $html = "";

        $page = (isset($page) && $page > 0) ? (int) $page : 1;

        $start = $page > 0 ? (($page * $records) - $records) : 0;

        if (isset($search) && $search != "") {
            $sql = "SELECT SQL_CALC_FOUND_ROWS *
                    FROM cliente
                    WHERE cliente_dni LIKE '%$search%'
                    OR cliente_nombre LIKE '%$search%'
                    OR cliente_apellido LIKE '%$search%'
                    OR cliente_telefono LIKE '%$search%'
                    OR cliente_direccion LIKE '%$search%'
                    ORDER BY cliente_nombre ASC
                    LIMIT $start, $records";

        } else {
            $sql = "SELECT SQL_CALC_FOUND_ROWS *
                    FROM cliente
                    ORDER BY cliente_nombre ASC
                    LIMIT $start, $records";
        }

        $dbcnn = clientModel::connection();

        $query = $dbcnn->query($sql);
        $rows = $query->fetchAll();

        $total = $dbcnn->query("SELECT FOUND_ROWS()");
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
                        <th>TELEFONO</th>
                        <th>DIRECCIÓN</th>';

        if ($privilege == 1 || $privilege == 2) {
            $table .= '<th>ACTUALIZAR</th>';
        }

        if ($privilege == 1) {
            $table .= '<th>ELIMINAR</th>';
        }

        $table .= ' </tr>
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
                    <td>' . $row['cliente_dni'] . '</td>
                    <td>' . $row['cliente_nombre'] . '</td>
                    <td>' . $row['cliente_apellido'] . '</td>
                    <td>' . $row['cliente_telefono'] . '</td>
                    <td>
                        <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
                        title="' . $row['cliente_nombre'] .' ' . $row['cliente_apellido'] . '" data-content="' . $row['cliente_direccion'] . '">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </td>';
                if ($privilege == 1 || $privilege == 2) {
                    $table .= '
                        <td>
                            <a href="' . SERVER_URL . 'client-update/' . clientModel::encryption($row['cliente_id'])  . '/" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </td>
                    ';
                }
                if ($privilege == 1) {
                    $table .= '
                        <td>
                            <form class="ajax-form"  action="' . SERVER_URL . 'endpoint/client-ajax/" method="POST" data-form="delete" autocomplete="off">
                                <input type="hidden" name="cliente_id_del" value="' . clientModel::encryption($row['cliente_id']) . '">
                                <button type="submit" class="btn btn-warning">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    ';
                }
                $table .= '</tr>';

                $count++;
            }

            $end_record = $count - 1;
        } else {
            if ($total >= 1) {
                $table .= '
                <tr class="text-center" >
                    <td colspan="9"><a href="' . $url . '" class="btn btn-primary btn-raised btn-sm">Haga clic aquí para recargar el listado</a></td>
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
            $html .= '<p class="text-right">Mostrando cliente(s): ' . $start_record . ' al ' . $end_record . ' de un total de ' . $total . '</p>';

            $html .= clientModel::pagination_tables($page, $nPages, $url, $total_buttons);
        }

        return $html;
    }

    /*-- Controller's function for delete client --*/
    public function delete_client_controller() {
        // reciving client id
        $id = clientModel::decryption($_POST['cliente_id_del']);
        $id = clientModel::clean_string($id);

        // Checking that the client exists in the database
        $sql = "SELECT cliente.cliente_id
                FROM cliente
                WHERE cliente.cliente_id = '$id'";
        $query = clientModel::execute_simple_query($sql);

        if (!$query->rowCount() > 0) {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                        "¡El cliente que intenta eliminar no existe en el sistema!");
            return $res;
        }

        // Cheching if the client has associated loan records
        $sql = "SELECT prestamo.cliente_id
                FROM prestamo
                WHERE prestamo.cliente_id = '$id'
                LIMIT 1";
        $query = clientModel::execute_simple_query($sql);

        if ($query->rowCount() > 0) {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                        "No podemos elminar el cliente del sistema porque tiene prestamos asociados");

            return $res;
        }

        // Checking privileges of current user
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] != 1) {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado",
                                                        "¡No tienes los permisos necesarios para realizar esta operación!");
            return $res;
        }

        // Deleting client of the system
        $query = clientModel::delete_client_model($id);

        if ($query->rowCount() == 1) {
            $res = clientModel::message_with_parameters("reload", "success", "Cliente eliminado",
                                                        "El cliente ha sido eliminado del sistema con exito.");
        } else {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                        "No hemos podido eliminar el cliente, por favor intentelo nuevamente");
        }

        return $res;
    }

    /*-- Controller's function for query client user --*/
    public function query_data_client_controller($type, $id = NULL) {
        $type = clientModel::clean_string($type);

        if (!is_null($id)) {
            $id = clientModel::decryption($id);
            $id = clientModel::clean_string($id);
        }

        return clientModel::query_data_client_model($type, $id);
    }

    /*-- Controller's function for sent message user --*/
    public function message_client_controller($alert, $type, $title, $text) {
        return clientModel::message_with_parameters($alert, $type, $title, $text);
    }

    /*-- Controller's function update client data --*/
    public function update_client_data_controller() {
        // Recieving the id
        $id = clientModel::decryption($_POST['cliente_id_upd']);
        $id = clientModel::clean_string($id);
        $id = (int) $id;

        // Checking client id in the database
        $sql = "SELECT cliente.*
                FROM cliente
                WHERE cliente.cliente_id = $id";
        $query = clientModel::execute_simple_query($sql);

        if ($query->rowCount() == 1) {
            $fields = $query->fetch();
        } else {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrío un error inesperado.",
                                                        "El cliente no existe en base de datos, intente nuevamente.");
            return $res;
        }

        $dni = clientModel::clean_string($_POST['cliente_dni_upd']);
        $nombre = clientModel::clean_string($_POST['cliente_nombre_upd']);
        $apellido = clientModel::clean_string($_POST['cliente_apellido_upd']);
        $telefono = clientModel::clean_string($_POST['cliente_telefono_upd']);
        $direccion = clientModel::clean_string($_POST['cliente_direccion_upd']);

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

        // Preparing data to send to the model
        $data = [
            "dni" => $dni,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "id" => $id
        ];

        // Sending data to update user model
        if (clientModel::update_client_data_model($data)) {
            $res = clientModel::message_with_parameters("reload", "success", "Datos Actualizados",
                                                        "Los datos han sido actualizados con éxito.");
        } else {
            $res = clientModel::message_with_parameters("simple", "error", "Ocurrio un error inesperado",
                                                        "No hemos podido actualizar los datos, por favor, intentelo nuevamente.");
        }

        return $res;
    }
 }
