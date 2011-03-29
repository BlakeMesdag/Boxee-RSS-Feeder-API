<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQLSession
 *
 * @author blakemesdag
 */
class SQLSession {
    private $sqlConnection;

    public function __construct($server, $username, $password, $database = null)
    {
        $this->sqlConnection = mysql_connect($server, $username, $password) or die(mysql_error());

        $database != null ? mysql_select_db($database, $this->sqlConnection) or die(mysql_error()) : null;
    }

    public function query($query)
    {
        return mysql_num_rows(mysql_query($query)) or die(mysql_error());
    }

    public function __destruct()
    {
        mysql_close($this->sqlConnection) or die(mysql_error());
    }
}
?>
