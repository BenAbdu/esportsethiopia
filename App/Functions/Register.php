<?php 

    namespace App\Functions;
    use PDO;

    class Register { 

        public $db;

        public function __construct($db){
            $this->db = $db;
        }

        public function create($name, $gamerTag, $gameID, $phone){

            $sessionID = bin2hex(openssl_random_pseudo_bytes(16));

            $sql  = "INSERT INTO `registerations` (`sessionID`,`fullName`,`gamerTag`,`gameID`,`phone`,`status`) 
            VALUES (\"$sessionID\",\"$name\",\"$gamerTag\",\"$gameID\",\"$phone\",\"unpaid\") ";

            $this->db->query($sql);

            return $sessionID;
        }

    }

?>