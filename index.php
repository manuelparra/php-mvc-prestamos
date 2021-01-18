<?php
/**
 * Index File
 *
 * Contents of the Index File.
 *
 * @package View
 * @author Manuel Parra
 * @version 1.0.0
 */

/*--- ABS Path of the proyect. ---*/
if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');

require_once "./config/app.php";

if (isset($_GET['endpoint'])) {
    require_once "./controllers/endpointController.php";

    $endopointreq = new endpointController();
    $endopoint = $endopointreq->get_endpoint_controller($_GET['endpoint']);

    require_once $endopoint;
}

require_once "./controllers/viewController.php";

$template = new viewController();
$template->get_template_controller();
