<?php
/**
 * App Config
 *
 * All functionality pertaining to App Config.
 *
 * @package Config
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

const SERVER_URL = "http://prestamos.com/";
const ERROR_DIR = "/var/www/html/learning/fullstack/php/sistemas/prestamos/logs/";
const COMPANY = "SISTEMA DE PRESTAMOS";
const MONEDA = "€";

date_default_timezone_set("Europe/Madrid");