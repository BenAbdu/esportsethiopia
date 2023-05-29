<?php 

    namespace App\Functions;

    class Register { 

        public function create($name, $gamerTag, $gameID){

            $sessionID = bin2hex(openssl_random_pseudo_bytes(16));

            $sql  = "INSERT INTO `register` WHERE `` ";

        }

    }

?>