<?php
    class Functions{
        public function checkNum($num){
            $num = trim($num);
            if(preg_replace("/[0-9]+/", "-", $num) === "-"){
                return $num;
            } else return "";
        }
        public function checkNumString($num){
            $num = trim($num);
            if(preg_replace("/[0-9,\-]+/", "-", $num) === "-"){
                return $num;
            } else return "";
        }
        public function checkDefaultString($str){
            $str = trim($str);
            if(preg_replace("/[A-Za-z0-9\s]+/", "-", $str) === "-"){
                return $str;
            } else return "";
        }
        public function checkString($str){
            $str = trim($str);
            if(preg_replace("/[A-Za-z0-9\s\-_,.]+/", "-", $str) === "-"){
                return $str;
            } else return "";
        }
        public function checkMultiString($str){
            $str = trim($str);
            if(preg_replace("/[A-Za-z0-9\s\-\._,:;\/\\%\?\!]+/", "-", $str) === "-"){
                return $str;
            } else return "";
        }
        public function getIP(){
            if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])){ 
                return $_SERVER['HTTP_CF_CONNECTING_IP'];
            } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND ipInRange::ipv4_in_range($_SERVER['REMOTE_ADDR'], '127.0.0.0/8')){
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
        }
        public function checkBanIP(){
            include dirname(__FILE__)."/database.php";

            $ip = $this->getIP();

            // сheck ban on the IP
            $query = $db->prepare("SELECT count(*) FROM bans WHERE IP = :ip");
            $query->execute([':ip' => $ip]);
            if($query->fetchColumn() != 0) exit("-1");
        }
        public function getUserID($accountID, $userName = "Unknown"){
            include dirname(__FILE__)."/database.php";
            include dirname(__FILE__)."/../../config/settings.php";

            $userName = trim($userName);
            $ip = $this->getIP();

            // Disabled names
            foreach($disabledNames AS $disabledName){
                if($userName == "") exit("-1");
                if(strtolower($userName) == strtolower(trim($disabledName))) exit("-1");
            }

            // User limit
            if($usersLimit === true){
                $query = $db->prepare("SELECT count(*) FROM users WHERE IP = :ip AND lastActive > :time");
                $query->execute([':ip' => $ip, ':time' => time()-$usersLimitTime]);
                if($query->fetchColumn() >= $usersLimitCount) exit("-1");
            }

            // Get userID
            $query = $db->prepare("SELECT userID FROM users WHERE accountID = :accountID LIMIT 1");
            $query->execute([':accountID' => $accountID]);
            if($query->rowCount() == 0){
                if($userName !== "Unknown"){
                    // Unique names
                    if($uniqueNames === true){
                        $query = $db->prepare("SELECT count(*) FROM users WHERE userName LIKE :userName");
                        $query->execute([':userName' => $userName]);
                        if($query->fetchColumn() != 0) exit("-1");
                    }
                    
                    $query = $db->prepare("INSERT INTO users (accountID, userName, lastActive, IP) VALUES (:accountID, :userName, :lastActive, :ip)");
                    $query->execute([':accountID' => $accountID, ':userName' => $userName, ':lastActive' => time(), ':ip' => $ip]);
                    return $db->lastInsertId();
                } else exit("-1");
            } else {
                $userID = $query->fetchColumn();
                if($userName !== "Unknown"){
                    $query = $db->prepare("UPDATE users SET userName = :userName, IP = :ip WHERE accountID = :accountID");
                    $query->execute([':userName' => $userName, ':ip' => $ip, ':accountID' => $accountID]);
                } else {
                    $query = $db->prepare("UPDATE users SET IP = :ip WHERE accountID = :accountID");
                    $query->execute([':ip' => $ip, ':accountID' => $accountID]);
                }
                return $userID;
            }
        }
        public function getUserName($userID){
            include dirname(__FILE__)."/database.php";

            $query = $db->prepare("SELECT userName FROM users WHERE userID = :userID LIMIT 1");
            $query->execute([':userID' => $userID]);
            if($query->rowCount() != 0){
                return $query->fetchColumn();
            } else return "Unknown";
        }
        public function checkPossibility($userID, $string){
            include dirname(__FILE__)."/database.php";

            $query = $db->prepare("SELECT roles.$string FROM roles INNER JOIN roleassign ON roles.roleID = roleassign.roleID WHERE roleassign.userID = :userID");
            $query->execute([':userID' => $userID]);
            if($query->rowCount() != 0){
                return $query->fetchColumn();
            } else return 0;
        }
    }
?>