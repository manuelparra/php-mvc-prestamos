<?php
/**
 * Main Model Class
 *
 * All functionality pertaining to the Main Model.
 *
 * @package Model
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

require_once "./config/server.php";

class mainModel {
    /*--- Fuction to connect to a database ---*/
    protected static function connection() {
        try {
            $conn = new PDO(CONNECTIONSTRING, USER, PASSWORD);
        } catch (PDOException $e) {
            echo 'Falló la conexión a la base de datos: ' . $e->getMessage();
        }

        $conn->exec("SET NAMES utf8");
        return $conn;
    }

    /*--- Function to execute a simple query ---*/
    protected static function execute_simple_query($sql) {
        $query = self::connection()->prepare($sql);
        $query->execute();
        return $query;
    }

    /*--- Function to encrytion string ---*/
    protected static function encryption($string){
        $output=FALSE;
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
        $output=base64_encode($output);
        return $output;
    }

    /*--- Function to decryption string ---*/
    protected static function decryption($string){
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
        return $output;
    }

    /*--- Generate random codes ---*/
    protected static function generate_rendom_codes($letter, $longitude, $number) {
        for ($i = 1; $i <= $longitude; $i++) {
            $ramdom = rand(0, 9);
            $letter .= $ramdom;
        }
        return $letter . '-' . $number;
    }

    /*--- Function to clean string ---*/
    protected static function clean_string($string) {
        $string = str_ireplace("<script>", "", $string);
        $string = str_ireplace("</script>", "", $string);
        $string = str_ireplace("<script src", "", $string);
        $string = str_ireplace("<script type=", "", $string);
        $string = str_ireplace("SELECT * FROM", "", $string);
        $string = str_ireplace("DELETE FROM", "", $string);
        $string = str_ireplace("INSERT INTO", "", $string);
        $string = str_ireplace("DROP TABLE", "", $string);
        $string = str_ireplace("DROP DATABASE", "", $string);
        $string = str_ireplace("TRUNCATE TABLE", "", $string);
        $string = str_ireplace("SHOW TABLES", "", $string);
        $string = str_ireplace("SHOW DATABASE", "", $string);
        $string = str_ireplace("<?php", "", $string);
        $string = str_ireplace("?>", "", $string);
        $string = str_ireplace("__", "", $string);
        $string = str_ireplace(">", "", $string);
        $string = str_ireplace("<", "", $string);
        $string = str_ireplace("[", "", $string);
        $string = str_ireplace("]", "", $string);
        $string = str_ireplace("^", "", $string);
        $string = str_ireplace("==", "", $string);
        $string = str_ireplace(";", "", $string);
        $string = str_ireplace("::", "", $string);
        $string = stripcslashes($string);   // Delete backslash
        $string = trim($string); // Delete white space in strings

        return $string;
    }

    /*--- Function to check data ---*/
    protected static function check_data($filter, $string) {
        if (preg_match("/^" . $filter . "$/", $string)) {
            return false;
        } else {
            return true;
        }
    }

    /*--- Function to check date ---*/
    protected static function check_date($date) {
        $date_arr = explode("-", $date);

        if (count($date_arr) == 3 && checkdate($date_arr[1], $date_arr[2], $date_arr[0])) {
            return false;
        } else {
            return true;
        }
    }

    /*--- Function to generate alert message ---*/
    protected static function message_with_parameters($alert, $type, $title, $text, $url = NULL) {
        if ($alert == "redirect") {
            return json_encode([
                "alert" => $alert,
                "url" => $url
            ]);
        } else {
            return json_encode([
                "alert" => $alert,
                "type" => $type,
                "title" => $title,
                "text" => $text
            ]);
        }
    }

    /*--- Function to paginate tables ---*/
    protected static function pagination_tables($page, $num_page, $url, $buttons) {
        $html ='
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
        ';

        if ($page == 1) {
            $html .= '
            <li class="page-item disabled">
                <a class="page-link">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <li class="page-item disabled">
                <a class="page-link">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            ';
        } else {
            $html .= '
            <li class="page-item">
                <a class="page-link href="' . $url . '1/">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link href="' . $url . ($page - 1) . '/">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            ';
        }

        for ($i = $page, $ci = 0; $i <= $num_page; $i++, $ci++) {
            if ($ci >= $buttons)
                break;

            if ($page == $i) {
                $html .= '
                <li class="page-item">
                    <a class="page-link active" href="' . $url . $i . '/">' . $i . '</a>
                </li>
                ';
            } else {
                $html .= '
                <li class="page-item">
                    <a class="page-link" href="' . $url . $i . '/">' . $i . '</a>
                </li>
                ';
            }
        }

        if ($page == $num_page) {
            $html .= '
            <li class="page-item disabled">
                <a class="page-link">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <li class="page-item disabled">
                <a class="page-link">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
            ';
        } else {
            $html .= '
            <li class="page-item">
                <a class="page-link href="' . $url . ($page + 1) . '/">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link href="' . $url . $num_page . '/">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
            ';
        }

        $html .= '
            </ul>
        </nav>
        ';

        return $html;
    }
}
