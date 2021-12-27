<?php
    ini_set("memory_limit", "64M");
    ini_set("post_max_size", "1M");
    ini_set("upload_max_filesize", "1M");

    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/settings.php";
    include dirname(__FILE__)."/../../config/levels.php";
    include dirname(__FILE__)."/../../config/limits.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();
    $ip = $f->getIP();

    // Data from version 1.0 • Not used: levelID
    $udid = $gp->getPost("udid", "su"); if($udid === "") exit("-1");
    $userName = $gp->getPost("userName", "s"); if($userName === "") exit("-1");
    $levelName = $gp->getPost("levelName", "s+"); if($levelName === "") exit("-1");
    $levelDesc = $gp->getPost("levelDesc", "sld"); if($levelDesc === "") exit("-1");
    $levelString = $gp->getPost("levelString", "sls"); if($levelString === "") exit("-1");
    $levelVersion = $gp->getPost("levelVersion", "n"); if($levelVersion === "") exit("-1");
    $levelLength = $gp->getPost("levelLength", "n"); if($levelLength === "") exit("-1");
    $audioTrack = $gp->getPost("audioTrack", "n"); if($audioTrack === "") exit("-1");
    $gameVersion = $gp->getPost("gameVersion", "n"); if($gameVersion === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        if(substr($levelString, 0, 4) !== "kS1," OR substr($levelString, -1) !== ";") exit("-1");
        if($checkLevelLength["use"] === true AND ($levelLength < $checkLevelLength["min"] OR $levelLength > $checkLevelLength["max"])) exit("-1");
        if($checkAudioTrack["use"] === true AND $audioTrack > $checkAudioTrack["count"]) exit("-1");
        if($checkGameVersion["use"] === true AND $gameVersion !== $checkGameVersion["value"]) exit("-1");
        $levelDesc = str_replace(":", "-", $levelDesc);
        $levelDesc = base64_encode($levelDesc);
        $userID = $f->getUserID($udid, $userName);

        if($uploadLevelLimit["use"] === true){
            $query = $db->prepare("SELECT count(*) FROM levels WHERE (userID = :userID OR IP = :ip) AND uploadDate > :time");
            $query->execute([":userID" => $userID, ":ip" => $ip, ":time" => time() - $uploadLevelLimit["time"]]);
            if($query->fetchColumn() >= $uploadLevelLimit["count"]) exit("-1");
        }

        $levelID = 0; $deleted = 0;
        $query = $db->prepare("SELECT levelID, deleted FROM levels WHERE levelName = :levelName AND userID = :userID LIMIT 1");
        $query->execute([":levelName" => $levelName, ":userID" => $userID]);
        if($query->rowCount() == 1){
            $levelInfo = $query->fetch();
            $levelID = $levelInfo["levelID"];
            $deleted = $levelInfo["deleted"];
        }

        if($levelID == 0){
            $query = $db->prepare("INSERT INTO levels (levelName, levelDesc, levelVersion, levelLength, audioTrack, gameVersion, uploadDate, userID, IP) VALUES (:levelName, :levelDesc, :levelVersion, :levelLength, :audioTrack, :gameVersion, :uploadDate, :userID, :ip)");
            $query->execute([":levelName" => $levelName, ":levelDesc" => $levelDesc, ":levelVersion" => $levelVersion, ":levelLength" => $levelLength, ":audioTrack" => $audioTrack, ":gameVersion" => $gameVersion, ":uploadDate" => time(), ":userID" => $userID, ":ip" => $ip]);
            $levelID = $db->lastInsertId();
            file_put_contents(dirname(__FILE__)."/../../data/levels/$levelID", $levelString);
        } else {
            $query = $db->prepare("UPDATE levels SET levelDesc = :levelDesc, levelVersion = :levelVersion, levelLength = :levelLength, audioTrack = :audioTrack, gameVersion = :gameVersion, updateDate = :updateDate, IP = :ip, deleted = 0 WHERE levelName = :levelName AND userID = :userID");
            $query->execute([":levelDesc" => $levelDesc, ":levelVersion" => $levelVersion, ":levelLength" => $levelLength, ":audioTrack" => $audioTrack, ":gameVersion" => $gameVersion, ":updateDate" => time(), ":ip" => $ip, ":levelName" => $levelName, ":userID" => $userID]);
            file_put_contents(dirname(__FILE__)."/../../data/levels/$levelID", $levelString);
            if($deleted == 1){
                $query = $db->prepare("UPDATE levels SET difficulty = 0, featured = 0, downloads = 0, likes = 0 WHERE levelName = :levelName AND userID = :userID");
                $query->execute([":levelName" => $levelName, ":userID" => $userID]);
                if(file_exists(dirname(__FILE__)."/../../data/levels/deleted/$levelID")) unlink(dirname(__FILE__)."/../../data/levels/deleted/$levelID");
            }
        }

        echo $levelID;
    } else exit("-1");
?>