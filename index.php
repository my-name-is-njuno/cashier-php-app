<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




include_once 'system/helpers/sessions.php';
include_once 'system/config/config.php';
include_once 'system/helpers/helpers.php';
include_once 'system/libs/Main.php';
include_once 'system/libs/MainController.php';
include_once 'system/libs/MainModel.php';


require_once 'system/external/dompdf/autoload.inc.php';
include('system/external/upload/src/class.upload.php');



$main = new Main();
