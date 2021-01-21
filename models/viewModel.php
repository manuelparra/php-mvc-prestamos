<?php
/**
 * View Model Class
 *
 * All functionality pertaining to the View Model.
 *
 * @package Model
 * @author Manuel Parra
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

/*--- Class View Model ---*/
class viewModel {
    /*-- Function for get view model --*/
    protected static function get_view_model($view)
    {
        $whiteListView = ["home", "client-list", "client-new", "client-search",
        "client-update", "company", "item-list", "item-new", "item-search",
        "item-update", "reservation-list", "reservation-new", "reservation-pending",
        "reservation-reservation", "reservation-search", "reservation-update",
        "user-list", "user-new", "user-search", "user-update"];

        $content = "404";

        if (in_array($view, $whiteListView)) {
            if (is_file("./views/contents/" . $view . "-view.php")) {
                $content = "./views/contents/" . $view . "-view.php";
            }
        } elseif ($view == "login" || $view == "index") {
            $content = "login";
        }

        return $content;
    }
}
