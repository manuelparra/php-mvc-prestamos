<?php
/**
 * Ajax Search Engine Script
 *
 * All functionality pertaining to the Ajax Search Engine requests.
 *
 * @package Ajax Request
 * @author Manuel Parra
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

session_start(['name' => 'SPM']);

require_once "./config/app.php";

if (isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || isset($_POST['busqueda_fecha_inicial']) || isset($_POST['busqueda_fecha_final'])) {
    $url_reload = [
        "usuario" => "user-search",
        "cliente" => "client-search",
        "item" => "item-search",
        "prestamo" => "reservation-search"
    ];

    if (isset($_POST['modulo'])) {
        $modulo = $_POST['modulo'];

        if (!isset($url_reload[$modulo])) {
            echo json_encode([
                "alert" => "simple",
                "type" => "error",
                "title" => "Ocurrió un error inesperado",
                "text" => "No podemos continuar con la búsqueda debido a que no se encontró el modulo de origen de la solicitud de búsqueda."
            ]);
            exit;
        }
    } else {
        echo json_encode([
            "alert" => "simple",
            "type" => "error",
            "title" => "Ocurrió un error inesperado",
            "text" => "No podemos continuar con la búsqueda debido a un error de configuración."
        ]);
        exit;
    }

    if ($modulo == "prestamo") {
        $fecha_inicial = "busqueda_fecha_inicial_" . $modulo;
        $fecha_final = "busqueda_fecha_final" . $modulo;

        // Begin search
        if (isset($_POST['busqueda_fecha_inicial']) || isset($_POST['busqueda_fecha_final'])) {
            if ($_POST['busqueda_fecha_inicial'] == "" || $_POST['busqueda_fecha_final'] == "") {
                echo json_encode([
                    "alert" => "simple",
                    "type" => "error",
                    "title" => "Ocurrió un error inesperado",
                    "text" => "Por favor, introduce una fecha inicial y una final para realizar la búsqueda."
                ]);
                exit;
            }

            $_SESSION[$fecha_inicial] = $_POST['busqueda_fecha_inicial'];
            $_SESSION[$fecha_final] = $_POST['busqueda_fecha_final'];
        }

        // Delete search
        if (isset($_POST['eliminar_busqueda'])) {
            unset($_SESSION[$fecha_inicial]);
            unset($_SESSION[$fecha_final]);
        }
    } else {
        $name_var = "busqueda_" . $modulo;

        // Begin search
        if (isset($_POST['busqueda_inicial'])) {
            if ($_POST['busqueda_inicial'] == "") {
                echo json_encode([
                    "alert" => "simple",
                    "type" => "error",
                    "title" => "Ocurrió un error inesperado",
                    "text" => "Por favor, introduce el termino de búsqueda para empezar."
                ]);
                exit;
            }
            $_SESSION[$name_var] = $_POST['busqueda_inicial'];
        }

        // Delete search
        if (isset($_POST['eliminar_busqueda'])) {
            unset($_SESSION[$name_var]);
        }
    }

    $url = $url_reload[$modulo];

    // Redirect
    echo json_encode([
        "alert" => "redirect",
        "url" => SERVER_URL . $url . "/"
    ]);
    exit;
} else {
    session_unset();
    session_destroy();
    header("Location: " . SERVER_URL . "login/");
    exit;
}
