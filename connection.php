<?php

class Conn {
    public function __construct() {
        
    }

    public static function connect() {
        $user = "adminprogweb";
        $pass = "ProgWeb3";
        $db = "progweb3";
        $server = "127.0.0.1";
        $conn = mysqli_connect($server, $user, $pass, $db);
    
        if ($conn->connect_errno){
            die ("Erro de Conexão!" . $conn->connect_error);
        }else{
            return $conn;         
        }
    }
}


?>
