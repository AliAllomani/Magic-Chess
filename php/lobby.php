<?php

/**
 * Game Lobby File
 * 
 * @author Allomani <info@allomani.com> 
 */
/**
 * Starting new Session and delete the old one 
 */
session_start();
@session_regenerate_id(true);
$_SESSION['table'] = '';
$_SESSION['player'] = '';

/**
 * Display Lobby Page 
 */
require('smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->setTemplateDir(".." . DIRECTORY_SEPARATOR . "templates");
$smarty->setCompileDir(".." . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "smarty");

$smarty->display('lobby.html');
?>


