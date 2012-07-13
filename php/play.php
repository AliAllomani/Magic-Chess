<?php
/**
 *  Game Play File 
 * 
 * @author Allomani <info@allomani.com>
 */

/** 
 *  Make sure that player have table id and player data or return it to lobby 
 */
session_start();
if (!$_SESSION['player'] || !$_SESSION['table']) {
    header('Location: lobby');
}

/** 
 * Generate Random Peices List  
 */
$list = array("ADVISER", "ADVISER",
    "BISHOPS", "BISHOPS",
    "CANNONS", "CANNONS",
    "KNIGHTS", "KNIGHTS",
    "ROOKS", "ROOKS",
    "KING",
    "PAWNS", "PAWNS", "PAWNS", "PAWNS", "PAWNS");

$pieces_list = array();

foreach ($list as $item) {
    $pieces_list[] = array("piece" => $item, "player" => "black");
    $pieces_list[] = array("piece" => $item, "player" => "red");
}

shuffle($pieces_list);

/**
 * Assign peices list to template and show play page
 */
require('smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->setTemplateDir("..".DIRECTORY_SEPARATOR."templates");
$smarty->setCompileDir("..".DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."smarty");
$smarty->assign('pieces_list',$pieces_list);
$smarty->display('play.html');
?>