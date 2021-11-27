<?php
    class Commands{
        public function checkCommand($userID, $levelID, $comment){
            chdir(dirname(__FILE__));
            include "database.php";
            require_once "functions.php";
            $f = new Functions();

            $ip = $f->getIP();
            $commentArray = explode(" ", $comment);

            switch($commentArray[0]){
                case "!delete":
                    $query = $db->prepare("SELECT userID FROM levels WHERE levelID = :levelID AND deleted = 0");
                    $query->execute([':levelID' => $levelID]);
                    if($query->fetchColumn() == $userID OR $f->checkPossibility($userID, "commandDelete")){
                        if(file_exists(dirname(__FILE__)."/../levels/data/$levelID")){
                            rename(dirname(__FILE__)."/../levels/data/$levelID", dirname(__FILE__)."/../levels/data/deleted/$levelID");

                            $query = $db->prepare("UPDATE levels SET deleted = 1 WHERE levelID = :levelID AND deleted = 0");
                            $query->execute([':levelID' => $levelID]);
                            $query = $db->prepare("INSERT INTO actions (type, value1, IP, actionDate) VALUES (4, :levelID, :ip, :time)");
                            $query->execute([':levelID' => $levelID, ':ip' => $ip, ':time' => time()]);

                            return true;
                        }
                    }
                    break;
                case "!rate":
                    if($f->checkPossibility($userID, "commandRate")){
                        require "../config/settings.php";

                        if(!empty($commentArray[2]) AND is_numeric($commentArray[2])){
                            $stars = $commentArray[2];
                        }

                        $demon = 0;
                        switch($commentArray[1]){
                            case "easy":
                                $difficulty = 1;
                                $stars = 2;
                                break;
                            case "normal":
                                $difficulty = 2;
                                $stars = 3;
                                break;
                            case "hard":
                                $difficulty = 3;
                                $stars = 4;
                                break;
                            case "harder":
                                $difficulty = 4;
                                $stars = 6;
                                break;
                            case "insane":
                                $difficulty = 5;
                                $stars = 8;
                                break;
                            case "demon":
                                $difficulty = 5;
                                $demon = 1;
                                $stars = 10;
                                break;
                            default: return false;
                        }

                        // Command rate limit
                        if($commandRateLimit === true){
                            if($commandRateLimitCheckStars){
                                if($commentArray[1] == "easy" AND $stars != 2) return false;
                                if($commentArray[1] == "normal" AND $stars != 3) return false;
                                if($commentArray[1] == "hard" AND ($stars != 4 OR $stars != 5)) return false;
                                if($commentArray[1] == "harder" AND ($stars != 6 OR $stars != 7)) return false;
                                if($commentArray[1] == "insane" AND ($stars != 8 OR $stars != 9)) return false;
                                if($commentArray[1] == "demon" AND $stars != 10) return false;
                            }
                        }
                        if($commandRateLimitStars === true){
                            if($commandRateLimitMinStars <= $commandRateLimitMaxStars){
                                if($stars < $commandRateLimitMinStars OR $stars > $commandRateLimitMaxStars) return false;
                            }
                        }

                        $query = $db->prepare("UPDATE levels SET difficulty = :difficulty, demon = :demon, stars = :stars, rated = 1, rateDate = :time WHERE levelID = :levelID AND deleted = 0");
                        $query->execute([':difficulty' => $difficulty, ':demon' => $demon, ':stars' => $stars, ':time' => time(), ':levelID' => $levelID]);
                        $query = $db->prepare("INSERT INTO actions (type, value1, value2, value3, IP, actionDate) VALUES (5, :levelID, :difficulty, :stars, :ip, :time)");
                        $query->execute([':levelID' => $levelID, ':difficulty' => $difficulty, ':stars' => $stars, ':ip' => $ip, ':time' => time()]);

                        return true;
                    }
                    break;
                case "!unrate":
                    if($f->checkPossibility($userID, "commandRate")){
                        $query = $db->prepare("UPDATE levels SET difficulty = 0, rated = 0, rateDate = 0 WHERE levelID = :levelID AND deleted = 0");
                        $query->execute([':levelID' => $levelID]);
                        $query = $db->prepare("INSERT INTO actions (type, value1, IP, actionDate) VALUES (6, :levelID, :ip, :time)");
                        $query->execute([':levelID' => $levelID, ':ip' => $ip, ':time' => time()]);

                        return true;
                    }
                    break;
            }

            return false;
        }
    }
?>
