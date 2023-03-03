<?php

use JetBrains\PhpStorm\NoReturn;

function hasData (?string $str ): bool {

    if (is_null($str) ) {
        return false;
    }

    if (strlen ($str) == 0 ) {
        return false;
    }

    return true;
}

function  addErrorToLog(string $error): void {

    if (!array_key_exists('errors', $_SESSION) || is_null($_SESSION['errors'])){
        $_SESSION['errors'] = [];
    }

    $_SESSION['errors'] [] = $error;
}

function getLog():string {

    if (!array_key_exists('errors', $_SESSION)) {
        return '';
    }

    return implode('<br>',$_SESSION['errors']);
}

function dieWith404(): void {
    $headerContent = $_SERVER['SERVER_PROTOCOL'] . " 404 Not Found";

    header( $headerContent );

    include "404.php";

    die();
}
