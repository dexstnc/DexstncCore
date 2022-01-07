<?php
    class Functions{
        public function getIP(){
            $keys = ["HTTP_CLIENT_IP", "HTTP_X_FORWARDED_FOR", "REMOTE_ADDR"];
            foreach($keys as $key) if(!empty($_SERVER[$key])){
                $ip = trim(end(explode(',', $_SERVER[$key])));
                if(filter_var($ip, FILTER_VALIDATE_IP)){
                    return $ip;
                }
            }

            exit("-12");
        }
        public function checkBanIP(){
            include dirname(__FILE__)."/database.php";
    
            $ip = $this->getIP();
    
            // IP ban check
            $query = $db->prepare("SELECT count(*) FROM bans WHERE IP = :ip");
            $query->execute([":ip" => $ip]);
            if($query->fetchColumn() != 0) exit("-12");
        }
        public function genString($length = 32){
            $length = floor($length);
            if($length > 0){
                $string = "";
                $symbols = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
    
                $symbolsLength = mb_strlen($symbols);
                for($i = mb_strlen($string); $i < $length; $i++){
                    $string .= $symbols[rand(0, $symbolsLength - 1)];
                }
    
                return $string;
            } else return "";
        }
        public function getUserID($udid, $userName = ""){
            include dirname(__FILE__)."/database.php";
            include dirname(__FILE__)."/../../config/settings.php";
            include dirname(__FILE__)."/../../config/users.php";
            include dirname(__FILE__)."/../../config/limits.php";
    
            $userName = trim($userName);
            $ip = $this->getIP();
    
            if(count($disabledUserNames) > 0) foreach($disabledUserNames AS $disabledUserName){
                if(strtolower($userName) === strtolower(trim($disabledUserName))) $userName = "";
            }
    
            $query = $db->prepare("SELECT userID FROM users WHERE udid = :udid LIMIT 1");
            $query->execute([":udid" => $udid]);
            if($query->rowCount() == 0){
                if($userName !== ""){
                    if($uniqueUsers === true){
                        $query = $db->prepare("SELECT count(*) FROM users WHERE userName LIKE :userName");
                        $query->execute([":userName" => $userName]);
                        if($query->fetchColumn() > 0) exit("-1");
                    }

                    if($createUserLimit["use"] === true){
                        $query = $db->prepare("SELECT count(*) FROM users WHERE IP = :ip AND uploadDate > :time");
                        $query->execute([":ip" => $ip, ":time" => time() - $createUserLimit["time"]]);
                        if($query->fetchColumn() >= $createUserLimit["count"]) exit("-1");
                    }
    
                    $query = $db->prepare("INSERT INTO users (udid, userName, lastActive, IP) VALUES (:udid, :userName, :lastActive, :ip)");
                    $query->execute([":udid" => $udid, ":userName" => $userName, ":lastActive" => time(), ":ip" => $ip]);
                    $userID = $db->lastInsertId();
    
                    if($checkUserCookies["secure"] === true AND !empty($_SERVER["HTTPS"])){ $cookieSecure = true; } else $cookieSecure = false;
                    $token = $this->genString(32);
                    setcookie("userToken", $token, ["expires" => 2147483647, "path" => "/", "secure" => $cookieSecure]);
                    if($_COOKIE["userToken"] === $token){
                        $query = $db->prepare("UPDATE users SET token = :token WHERE userID = :userID");
                        $query->execute([":token" => $token, ":userID" => $userID]);
                    } else { /* Cookies have not been installed */ }
    
                    return $userID;
                } else exit("-1");
            } else {
                $userID = $query->fetchColumn();
    
                if($checkUserCookies["use"] === true){
                    $query = $db->prepare("SELECT token FROM users WHERE userID = :userID");
                    $query->execute([":userID" => $userID]);
                    $token = $query->fetchColumn();
                    if($token != "" AND $token !== $_COOKIE["userToken"]) exit("-1");
                }
    
                if($userName !== ""){
                    $query = $db->prepare("UPDATE users SET userName = :userName, IP = :ip WHERE userID = :userID");
                    $query->execute([":userName" => $userName, ":ip" => $ip, ":userID" => $userID]);
                } else {
                    $query = $db->prepare("UPDATE users SET IP = :ip WHERE userID = :userID");
                    $query->execute([":ip" => $ip, ":userID" => $userID]);
                }
    
                return $userID;
            }
        }
        public function getUserName($userID){
            include dirname(__FILE__)."/database.php";

            $userName = "Unknown";
            $query = $db->prepare("SELECT userName FROM users WHERE userID = :userID LIMIT 1");
            $query->execute([":userID" => $userID]);
            if($query->rowCount() == 1) $userName = $query->fetchColumn();

            return $userName;
        }
        public function checkCapability($userID, $capability){
            include dirname(__FILE__)."/database.php";

            $query = $db->prepare("SELECT roles.$capability AS capability FROM roles JOIN roleassign ON roles.roleID = roleassign.roleID WHERE roleassign.userID = :userID LIMIT 1");
            $query->execute([":userID" => $userID]);
            if($query->rowCount() > 0){
                return $query->fetchColumn();
            } else return false;
        }
    }
?>