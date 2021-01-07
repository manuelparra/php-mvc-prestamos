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

// Local Enviroment, comment this lines if you put this files in a production environment
const SERVER_URL = "http://prestamos.com/";
const ERROR_DIR = "/var/www/html/learning/fullstack/php/sistemas/prestamos/logs/";

// Production Enviroment, comment this lines if you put this files in a developer environment
// const SERVER_URL = "https://prestamos.desliate.com/";
// const ERROR_DIR = "/var/www/desliate.com/prestamos/logs/";

const COMPANY = "Sistema de Prestamos";
const MONEDA = "€";

date_default_timezone_set("Europe/Madrid");