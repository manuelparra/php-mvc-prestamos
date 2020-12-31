<?php
/**
 * Server Config
 *
 * All functionality pertaining to Server Config.
 *
 * @package Config
 * @author Manuel Parra
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    echo "Acceso no autorizado.";
	exit; // Exit if accessed directly
}

const SERVER = "localhost";
const DB = "prestamos";
const USER = "manuel";
const PASSWORD = "Guti.1712*";

const CONNECTIONSTRING = "mysql:host=" . SERVER . ";dbname=" . DB;

const METHOD = "AES-256-CBC"; //AES-256-CBC
const SECRET_KEY = 'gUt1@17*Pr35tam0s';
const SECRET_IV = "301597";
