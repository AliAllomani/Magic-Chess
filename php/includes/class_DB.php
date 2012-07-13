<?php

/**
 * Database Connection and Operation Functions
 *
 * @author Allomani <info@allomani.com>
 */
class DB {

    /**
     * Connect to the database
     * 
     * @return void
     */
    public function connect() {
        global $db_host, $db_username, $db_password, $db_name;

        if (!mysql_connect($db_host, $db_username, $db_password)) {
            throw new Exception('Database Connection Error');
        }

        if (!mysql_select_db($db_name)) {
            throw new Exception('Invalid Database Name');
        }
    }

    /**
     * Database query
     * 
     * @param string $sql
     * @return resource 
     */
    public function query($sql) {
        return mysql_query($sql);
    }

    /**
     *  Fetch Data as Array
     * 
     * @param resource $qr
     * @return array 
     */
    public function fetch($qr) {
        return mysql_fetch_array($qr);
    }

    /**
     * Number of affected row of last query
     * 
     * @return integer 
     */
    public function affected_rows() {
        return mysql_affected_rows();
    }

    /**
     * Number of Rows
     *
     * @param resource $qr
     * @return integer 
     */
    public function num_rows($qr) {
        return mysql_num_rows($qr);
    }

    /**
     *  Escape String for SQL Query
     * 
     * @param string $str
     * @return string 
     */
    public function escape($str) {
        return mysql_real_escape_string($str);
    }

}

?>
