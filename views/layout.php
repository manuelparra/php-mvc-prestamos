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

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}
?>

<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title><?php echo COMPANY; ?></title>

        <!--=============================================
        =            Include CSS files           =
        ==============================================-->
        <?php include "./views/includes/links.php"; ?>
    </head>
    <body>
        <?php

        $ajaxReq = false;
        require_once "./controllers/viewController.php";
        $IV = new viewController(); // $IV, instanacia de la vista

        $view = $IV->get_view_controller();

        if ($view == "login" || $view == "404" ) {
            require_once "./views/contents/" . $view . "-view.php";
        } else {
            session_start(['name'=>'SPM']);

            require_once "./controllers/loginController.php";

            $insLoginController = new loginController();

            if (!isset($_SESSION['token_spm']) || !isset($_SESSION['usuario_spm'])
            || !isset($_SESSION['privilegio_spm']) || !isset($_SESSION['id_spm'])) {
                echo $insLoginController->force_close_session_controller();
                exit;
            }

        ?>
        <!-- Main container -->
        <main class="full-box main-container">
            <!-- Nav lateral -->
            <?php include  "./views/includes/navLateral.php"; ?>
            <!-- Page content -->
            <section class="full-box page-content">
                <!-- Nav Bar and the View -->
                <?php
                    include "./views/includes/navBar.php";
                    include $view;
                ?>
            </section>
        </main>
        <?php
            include "./views/includes/logout.php";
        }
        ?>
        <!--=============================================
        =            Include JavaScript files           =
        ==============================================-->
        <?php include "./views/includes/scripts.php"; ?>
    </body>
</html>
