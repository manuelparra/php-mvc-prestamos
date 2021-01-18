<?php
/**
 * Contents of 404 view.
 *
 * Contents of the 404 page view.
 *
 * @package View
 * @author Manuel Parra
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    header("Location: /login/");
	exit; // Exit if accessed directly
}
?>

<div class="full-box container-404">
    <div>
        <p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
        <h1 class="text-center">ERROR 404</h1>
        <p class="lead text-center">Página no encontrada</p>
    </div>
</div>
