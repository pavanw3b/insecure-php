<?php
/*
 * Author: pavanw3b
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$TITLE = "#NullHyd Humla by pavanw3b ";
$FOOTER = "This is a fictitious application created for Null Hyd Humla on SQL Injection by <a href=\"https://pavanw3b.com\">pavanw3b</a>";
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASSWORD = '';
$DB_NAME = "nullhyd_humla";

$PHP_VERSION = phpversion();

