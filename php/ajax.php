<?php

/**
 * Ajax Requests Handler file
 * 
 * @author Allomani <info@allomani.com> 
 */

require('config.php');
require("includes/class_DB.php");
require("includes/class_DataManager.php");

header("Content-Type: text/html;charset=utf-8");
session_start();

/**
 * Game End by Session ID Request 
 * 
 *  @see DataManager::game_end()
 */
if ($_REQUEST['action'] == 'game_end') {
    $obj = new DataManager();
    $obj->game_end(session_id());
    print "1";
}


/**
 * Update Player's alive Connection time request
 * 
 * @see DataManager::update_connection() 
 */
if ($_REQUEST['action'] == 'update_connection') {

    $obj = new DataManager();
    print ($obj->update_connection($_SESSION['player'], $_SESSION['table'], session_id()) ? "1" : "0");
    $obj->delete_inactive_games($inactive_game_timeout);
}


/**
 * Setting Peices Data Request
 * 
 * POST : @var string cur_player Current Player Turn (black | red)
 *        @var array  peices     Peices Data
 *   
 */
if ($_REQUEST['action'] == 'set') {

    $obj = new DataManager();
    $obj->set_peices($_POST['cur_player'], $_POST['pieces'], $_SESSION['table']);
}

/** 
 *  Get Peices Data Request 
 */
if ($_REQUEST['action'] == "get") {
    $obj = new DataManager();
    print $obj->get_peices($_SESSION['table']);
}


/**
 *  Check Lobby Tables Players Request 
 */
if ($_REQUEST['action'] == "check_tables") {

    $obj = new DataManager();
    $obj->update_connection($_SESSION['player'], $_SESSION['table'], session_id());
    $obj->delete_inactive_games($inactive_game_timeout);
    print $obj->get_tables_data(session_id());
}


/**
 * New Player Request 
 * 
 * POST : @var integer table Table ID
 */
if ($_REQUEST['action'] == "new_player") {
    $table = (int) $_POST['table'];
    $obj = new DataManager();
    print ($obj->new_player($table, session_id()) ? "1" : "0");
}
