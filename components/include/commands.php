<?php
    class Commands{
        public function checkCommand($userID, $levelID, $comment){
            include dirname(__FILE__)."/database.php";
            include dirname(__FILE__)."/../../config/settings.php";
            require_once dirname(__FILE__)."/functions.php";
            $f = new Functions();

            $ip = $f->getIP();
            $commentArray = explode(" ", $comment);

            switch($commentArray[0]){
                case "!delete":
                    $query = $db->prepare("SELECT userID FROM levels WHERE levelID = :levelID AND deleted = 0");
                    $query->execute([':levelID' => $levelID]);
                    if($query->fetchColumn() == $userID OR $f->checkPossibility($userID, "commandDelete")){
                        if(file_exists(dirname(__FILE__)."/../../data/levels/$levelID")){
                            rename(dirname(__FILE__)."/../../data/levels/$levelID", dirname(__FILE__)."/../../data/levels/deleted/$levelID");

                            $query = $db->prepare("UPDATE levels SET deleted = 1 WHERE levelID = :levelID AND deleted = 0");
                            $query->execute([':levelID' => $levelID]);
                            $query = $db->prepare("INSERT INTO actions (type, value1, userID, IP, actionDate) VALUES (4, :levelID, :userID, :ip, :time)");
                            $query->execute([':levelID' => $levelID, ':userID' => $userID, ':ip' => $ip, ':time' => time()]);

                            return true;
                        }
                    }
                break;
                case "!rate":
                    if($f->checkPossibility($userID, "commandRate")){
                        $demon = 0;
                        switch($commentArray[1]){
                            case "easy":
                                $difficulty = 1;
                                break;
                            case "normal":
                                $difficulty = 2;
                                break;
                            case "hard":
                                $difficulty = 3;
                                break;
                            case "harder":
                                $difficulty = 4;
                                break;
                            case "insane":
                                $difficulty = 5;
                                break;
                            case "demon":
                                $difficulty = 5;
                                $demon = 1;
                                break;
                            default: return false;
                        }

                        $stars = 0; $rated = 0; $rateDate = 0;
                        if(!empty($commentArray[2]) AND is_numeric($commentArray[2])){
                            $stars = $commentArray[2];
                            $rated = 1;
                            $rateDate = time();
                        }

                        // Command rate limit
                        if($commandRateLimit === true){
                            if($commandRateLimitCheckStars AND $stars > 0){
                                if($commentArray[1] == "easy" AND $stars != 2) return false;
                                if($commentArray[1] == "normal" AND $stars != 3) return false;
                                if($commentArray[1] == "hard" AND ($stars != 4 OR $stars != 5)) return false;
                                if($commentArray[1] == "harder" AND ($stars != 6 OR $stars != 7)) return false;
                                if($commentArray[1] == "insane" AND ($stars != 8 OR $stars != 9)) return false;
                                if($commentArray[1] == "demon" AND $stars != 10) return false;
                            }

                            if($commandRateLimitStars === true){
                                if($commandRateLimitMinStars <= $commandRateLimitMaxStars){
                                    if($stars < $commandRateLimitMinStars OR $stars > $commandRateLimitMaxStars) return false;
                                }
                            }
                        }

                        $query = $db->prepare("UPDATE levels SET difficulty = :difficulty, demon = :demon, stars = :stars, rated = :rated, rateDate = :rateDate WHERE levelID = :levelID AND deleted = 0");
                        $query->execute([':difficulty' => $difficulty, ':demon' => $demon, ':stars' => $stars, ':rated' => $rated, ':rateDate' => $rateDate, ':levelID' => $levelID]);
                        $query = $db->prepare("INSERT INTO actions (type, value1, value2, value3, value4, userID, IP, actionDate) VALUES (5, :levelID, :difficulty, :demon, :stars, :userID, :ip, :time)");
                        $query->execute([':levelID' => $levelID, ':difficulty' => $difficulty, ':demon' => $demon, ':stars' => $stars, ':userID' => $userID, ':ip' => $ip, ':time' => time()]);

                        if($commandRateAutoCPs === true){
                            include dirname(__FILE__)."/../../cron/include/withoutEcho/autoCreatorPoints.php";
                        }

                        return true;
                    }
                break;
                case "!unrate":
                    if($f->checkPossibility($userID, "commandRate")){
                        include dirname(__FILE__)."/../../config/settings.php";

                        $query = $db->prepare("UPDATE levels SET difficulty = 0, rated = 0, rateDate = 0 WHERE levelID = :levelID AND deleted = 0");
                        $query->execute([':levelID' => $levelID]);
                        $query = $db->prepare("INSERT INTO actions (type, value1, userID, IP, actionDate) VALUES (6, :levelID, :userID, :ip, :time)");
                        $query->execute([':levelID' => $levelID, ':userID' => $userID, ':ip' => $ip, ':time' => time()]);

                        if($commandRateAutoCPs === true){
                            include dirname(__FILE__)."/../../cron/include/withoutEcho/autoCreatorPoints.php";
                        }

                        return true;
                    }
                break;
                case "!suggest":
                    if($f->checkPossibility($userID, "commandSuggets")){
                        $demon = 0;
                        switch($commentArray[1]){
                            case "easy":
                                $difficulty = 1;
                                break;
                            case "normal":
                                $difficulty = 2;
                                break;
                            case "hard":
                                $difficulty = 3;
                                break;
                            case "harder":
                                $difficulty = 4;
                                break;
                            case "insane":
                                $difficulty = 5;
                                break;
                            case "demon":
                                $difficulty = 5;
                                $demon = 1;
                                break;
                            default: return false;
                        }

                        if(!empty($commentArray[2]) AND is_numeric($commentArray[2])){
                            $stars = $commentArray[2];
                        } else $stars = 0;

                        // Command rate limit
                        if($commandRateLimit === true){
                            if($commandRateLimitCheckStars AND $stars > 0){
                                if($commentArray[1] == "easy" AND $stars != 2) return false;
                                if($commentArray[1] == "normal" AND $stars != 3) return false;
                                if($commentArray[1] == "hard" AND ($stars != 4 OR $stars != 5)) return false;
                                if($commentArray[1] == "harder" AND ($stars != 6 OR $stars != 7)) return false;
                                if($commentArray[1] == "insane" AND ($stars != 8 OR $stars != 9)) return false;
                                if($commentArray[1] == "demon" AND $stars != 10) return false;
                            }

                            if($commandRateLimitStars === true){
                                if($commandRateLimitMinStars <= $commandRateLimitMaxStars){
                                    if($stars < $commandRateLimitMinStars OR $stars > $commandRateLimitMaxStars) return false;
                                }
                            }
                        }

                        $query = $db->prepare("SELECT suggestDate FROM suggests WHERE levelID = :levelID AND userID = :userID LIMIT 1");
                        $query->execute([':levelID' => $levelID, ':userID' => $userID]);
                        if($query->rowCount() == 1){
                            if($query->fetchColumn() > (time() - 300)) return false;

                            $query = $db->prepare("UPDATE suggests SET difficulty = :difficulty, demon = :demon, stars = :stars, suggestDate = :suggestDate, IP = :ip WHERE levelID = :levelID AND userID = :userID");
                            $query->execute([':difficulty' => $difficulty, ':demon' => $demon, ':stars' => $stars, ':suggestDate' => time(), ':ip' => $ip, ':levelID' => $levelID, ':userID' => $userID]);
                        } else {
                            $query = $db->prepare("INSERT INTO suggests (levelID, difficulty, demon, stars, suggestDate, userID, IP) VALUES (:levelID, :difficulty, :demon, :stars, :suggestDate, :userID, :ip)");
                            $query->execute([':levelID' => $levelID, ':difficulty' => $difficulty, ':demon' => $demon, ':stars' => $stars, ':suggestDate' => time(), ':userID' => $userID, ':ip' => $ip]);
                        }

                        $query = $db->prepare("INSERT INTO actions (type, value1, value2, value3, value4, userID, IP, actionDate) VALUES (7, :levelID, :difficulty, :demon, :stars, :userID, :ip, :time)");
                        $query->execute([':levelID' => $levelID, ':difficulty' => $difficulty, ':demon' => $demon, ':stars' => $stars, ':userID' => $userID, ':ip' => $ip, ':time' => time()]);

                        return true;
                    }
                break;
                case "!setacc":
                    if($f->checkPossibility($userID, "commandSetacc")){
                        if(empty($commentArray[1]) OR mb_strlen($commentArray[1]) < 2) return false;
                        $userName = $commentArray[1];

                        $query = $db->prepare("SELECT userID FROM users WHERE userName LIKE :userName ORDER BY userID ASC LIMIT 1");
                        $query->execute([':userName' => $userName]);
                        if($query->rowCount() == 1){
                            $newUserID = $query->fetchColumn();
                            $query = $db->prepare("UPDATE levels SET userID = :newUserID WHERE levelID = :levelID AND deleted = 0");
                            $query->execute([':newUserID' => $newUserID, ':levelID' => $levelID]);
                            $query = $db->prepare("INSERT INTO actions (type, value1, value2, userID, IP, actionDate) VALUES (8, :levelID, :newUserID, :userID, :ip, :time)");
                            $query->execute([':levelID' => $levelID, ':newUserID' => $newUserID, ':userID' => $userID, ':ip' => $ip, ':time' => time()]);

                            return true;
                        } else return false;
                    }
                break;
            }

            return false;
        }
    }
?>