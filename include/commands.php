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
                            default: return false;
                        }

                        $query = $db->prepare("UPDATE levels SET difficulty = :difficulty, rated = 1, rateDate = :time WHERE levelID = :levelID AND deleted = 0");
                        $query->execute([':difficulty' => $difficulty, ':time' => time(), ':levelID' => $levelID]);
                        $query = $db->prepare("INSERT INTO actions (type, value1, value2, IP, actionDate) VALUES (5, :levelID, :difficulty, :ip, :time)");
                        $query->execute([':levelID' => $levelID, ':difficulty' => $difficulty, ':ip' => $ip, ':time' => time()]);

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
