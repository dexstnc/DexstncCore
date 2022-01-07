<?php
    class Commands{
        public function isCommand($levelID, $userID, $comment){
            include dirname(__FILE__)."/database.php";
            require_once dirname(__FILE__)."/functions.php";
            $f = new Functions();

            $ip = $f->getIP();
            $time = time();
            $comment = explode(" ", $comment);
            $command = $comment[0];

            $query = $db->prepare("SELECT userID FROM levels WHERE levelID = :levelID AND deleted = 0");
            $query->execute([':levelID' => $levelID]);
            if($query->rowCount() == 0){ exit("-1"); return false; }
            $query->fetchColumn() == $userID ? $levelOwner = true : $levelOwner = false;

            switch($command){
                case "!delete":
                    if($levelOwner OR $f->checkCapability($userID, "cmdDelete")){
                        $query = $db->prepare("UPDATE levels SET deleted = 1 WHERE levelID = :levelID");
                        $query->execute([":levelID" => $levelID]);

                        if(file_exists(dirname(__FILE__)."/../../data/levels/$levelID")) rename(dirname(__FILE__)."/../../data/levels/$levelID", dirname(__FILE__)."/../../data/levels/deleted/$levelID");

                        $query = $db->prepare("INSERT INTO actions (type, value1, actionDate, itemID, userID, IP) VALUES (5, '!delete', :time, :levelID, :userID, :ip)");
                        $query->execute([":time" => $time, ":levelID" => $levelID, ":userID" => $userID, ":ip" => $ip]);

                        return true;
                    }
                break;
                case "!rate":
                    if($f->checkCapability($userID, "cmdRate")){
                        !empty($comment[1]) ? $difficulty = $comment[1] : $difficulty = "na";
                        if(!empty($comment[2]) AND ($comment[2] == 0 OR $comment[2] == 1)) $featured = $comment[2]; else $featured = 0;

                        switch($difficulty){
                            case "na":
                                $difficulty = 0;
                            break;
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

                        $query = $db->prepare("UPDATE levels SET difficulty = :difficulty, featured = :featured, rateDate = :time WHERE levelID = :levelID");
                        $query->execute([":difficulty" => $difficulty, ":featured" => $featured, ":time" => $time, ":levelID" => $levelID]);

                        $query = $db->prepare("INSERT INTO actions (type, value1, value2, value4, actionDate, itemID, userID, IP) VALUES (6, '!rate', :difficulty, :featured, :time, :levelID, :userID, :ip)");
                        $query->execute([":difficulty" => $difficulty, ":featured" => $featured, ":time" => $time, ":levelID" => $levelID, ":userID" => $userID, ":ip" => $ip]);

                        return true;
                    }
                break;
                case "!unrate":
                    if($f->checkCapability($userID, "cmdUnrate")){
                        $query = $db->prepare("UPDATE levels SET difficulty = 0, featured = 0, rateDate = 0 WHERE levelID = :levelID");
                        $query->execute([":levelID" => $levelID]);

                        $query = $db->prepare("INSERT INTO actions (type, value1, actionDate, itemID, userID, IP) VALUES (7, '!unrate', :time, :levelID, :userID, :ip)");
                        $query->execute([":time" => $time, ":levelID" => $levelID, ":userID" => $userID, ":ip" => $ip]);

                        return true;
                    }
                break;
                case "!featured":
                    if($f->checkCapability($userID, "cmdFeatured")){
                        $query = $db->prepare("SELECT featured FROM levels WHERE levelID = :levelID");
                        $query->execute([':levelID' => $levelID]);
                        $featured = $query->fetchColumn();
                        $featured == 0 ? $featured = 1 : $featured = 0;

                        $query = $db->prepare("UPDATE levels SET featured = :featured WHERE levelID = :levelID");
                        $query->execute([":featured" => $featured, ":levelID" => $levelID]);

                        $query = $db->prepare("INSERT INTO actions (type, value1, value2, actionDate, itemID, userID, IP) VALUES (8, '!featured', :featured, :time, :levelID, :userID, :ip)");
                        $query->execute([":featured" => $featured, ":time" => $time, ":levelID" => $levelID, ":userID" => $userID, ":ip" => $ip]);

                        return true;
                    }
                break;
                case "!rename":
                    if($levelOwner OR $f->checkCapability($userID, "cmdRename")){
                        $newLevelName = "";
                        if(!empty($comment[1])) for($i = 1; $i < count($comment); $i++) $newLevelName .= $comment[$i]." ";
                        $newLevelName = preg_replace("/[^a-zA-Z0-9\s]/", "", $newLevelName);

                        $query = $db->prepare("SELECT count(*) FROM levels WHERE levelName = :newLevelName");
                        $query->execute([":newLevelName" => $newLevelName]);
                        if($newLevelName != "" AND strlen($newLevelName) <= 32 AND $query->fetchColumn() == 0){
                            $query = $db->prepare("SELECT levelName, featured FROM levels WHERE levelID = :levelID");
                            $query->execute([":levelID" => $levelID]);
                            $levelInfo = $query->fetch();
                            if($newLevelName != $levelInfo["levelName"] AND $levelInfo["featured"] == 0){
                                $query = $db->prepare("UPDATE levels SET levelName = :newLevelName WHERE levelID = :levelID");
                                $query->execute([":newLevelName" => $newLevelName, ':levelID' => $levelID]);

                                $query = $db->prepare("INSERT INTO actions (type, value1, value2, actionDate, itemID, userID, IP) VALUES (9, '!rename', :newLevelName, :time, :levelID, :userID, :ip)");
                                $query->execute([":newLevelName" => $newLevelName, ":time" => $time, ":levelID" => $levelID, ":userID" => $userID, ":ip" => $ip]);

                                return true;
                            }
                        }
                    }
                break;
            }

            return false;
        }
    }
?>