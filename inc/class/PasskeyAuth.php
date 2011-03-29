<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PasskeyAuth
 *
 * @author blakemesdag
 */
class PasskeyAuth {
    private $sqlSession;

    public function __construct()
    {
        global $sqlServer, $sqlUsername, $sqlPassword, $sqlDatabase;

        $this->sqlSession = new SQLSession($sqlServer, $sqlUsername, $sqlPassword, $sqlDatabase);
    }

    public function verifyPasskey($passkey)
    {
        $query = "SELECT * FROM KeyPairs WHERE passkey='$passkey'";

        return $this->sqlSession->query($query) > 0;
    }
}
?>
