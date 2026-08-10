<?php
ini_set ('display_errors',1);
ini_set ('display_startup_errors',1);
error_reporting(E_ALL);

require_once dirname(__DIR__). "/controller/approvisionnement.controller.php";
$uri=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        
        affichages();
        break;
            case '/enregistrement':
        
        augmentationStickt();

        break;
    default:
        break;
}
