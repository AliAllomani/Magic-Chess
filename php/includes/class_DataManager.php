<?php

/**
 * Handle Data Requests
 *
 * @author Allomani <info@allomani.com>
 */
class DataManager {

    /**
     * Update player's alive connection time
     * 
     * @param string $player Player's Name (black | red) 
     * @param integer $table Table ID
     * @param string $sid Session ID
     * @return boolean 
     */
    public function update_connection($player, $table, $sid) {

        if ($player && $table) {
            $db = new DB();
            $db->connect();
            $db->query("update games set {$player}_time='" . time() . "' where {$player}_sid like '" . $sid . "' and table_id='" . $table . "'");

            return ($db->affected_rows() ? true : false);
        } else {
            return true;
        }
    }

    /**
     *  Delete inactive player's games
     * 
     * @param integer $timout
     * @return void 
     */
    public function delete_inactive_games($timeout = 10) {
        $time = time() - $timeout;
        $db = new DB();
        $db->connect();

        $db->query("delete from games where (red_sid not like '' and red_time < $time) or (black_sid not like '' and black_time < $time)");
    }

    /**
     *  End a Game by session id 
     * 
     * @param string $sid Player Session ID 
     * @return void
     */
    public function game_end($sid) {
        $db = new DB();
        $db->connect();
        $db->query("delete from games where red_sid like '" . $sid . "' or black_sid like '" . $sid . "'");
    }

    /**
     * Connect a new player to table
     * 
     * @param integer $table Table ID
     * @param string $sid Session ID
     * @return boolean 
     */
    public function new_player($table, $sid) {

        $db = new DB();
        $db->connect();

        $qr = $db->query("select * from games where table_id = '$table'");
        if ($db->num_rows($qr)) {
            $data = $db->fetch($qr);

            if ($data['black_sid'] && $data['red_sid']) {
                return false;
            } else {
                $db->query("update games set red_sid='" . $sid . "',red_time='" . time() . "' where table_id = '$table'");
                $_SESSION['table'] = $table;
                $_SESSION['player'] = 'red';
                return true;
            }
        } else {
            $db->query("insert into games (table_id,black_sid,black_time) values ('$table','" . $sid . "','" . time() . "')");
            $_SESSION['table'] = $table;
            $_SESSION['player'] = 'black';
            return true;
        }
    }

    /**
     * Return lobby tables players data 
     * 
     * @param string $sid   Session ID
     * @return JSON 
     */
    public function get_tables_data($sid) {

        $db = new DB();
        $db->connect();


        $qr = $db->query("select * from games");
        if ($db->num_rows($qr)) {
            while ($data = $db->fetch($qr)) {


                if ($data['black_sid'] && $data['red_sid']) {
                    if ($data['black_sid'] == $sid || $data['red_sid'] == $sid) {

                        /* delete old game file */
                        @unlink("../cache/table_{$data['table_id']}");

                        /* tell the browser to redirect to play page */
                        return json_encode(array(0 => array("redirect" => 1)));
                    }
                }

                $resp[] = array("id" => $data['table_id'], "black_sid" => $data['black_sid'], "red_sid" => $data['red_sid'], "redirect" => 0);
            }
        } else {
            $resp[] = array("id" => 0, "black_sid" => 0, "red_sid" => 0, "redirect" => 0);
        }

        return json_encode($resp);
    }

    /**
     * Store Peices Data in JSON Format 
     * 
     * @param string $cur_player Current player turn (black | red)
     * @param array $peices_data Peices Data
     * @param integer $table Table ID
     * @return void
     */
    public function set_peices($cur_player, $peices_data, $table) {
        $array = array("cur_player" => $cur_player, "pieces" => $peices_data);

        /* Saving data in JSON format to temp file , using disk for tmp files is faster than database since we dont need any sql here 
         * we can also use better memory variables solutions like memcache or xcahce
         */
        file_put_contents("../cache/table_" . $table, json_encode($array));

        /*  if you want to try to save to database ..
         *
         * $db = new DB();
         * $db->connect();
         * $db->query("update games set data='".$db->escape(json_encode($array))."' where table_id = '" . $table . "'");
         * 
         */
    }

    /**
     *  Retrieve Peices Data in JSON format
     * 
     * @param integer $table
     * @return JSON 
     */
    public function get_peices($table) {

        $data = file_get_contents("../cache/table_" . $table);

        return $data;

        /* if you want to use database store..
         * 
         * $db = new DB();
         * $db->connect();
         * $data  = $db->fetch($db->query("select data from games where table_id = '".$table."'"));
         * return $data['data'];
         * 
         */
    }

}

?>
