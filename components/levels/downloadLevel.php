<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();
    $ip = $f->getIP();

    // Data from version 1.0 • Not used: inc
    $levelID = $gp->getPost("levelID", "n"); if($levelID === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $query = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID LIMIT 1");
        $query->execute([":levelID" => $levelID]);
        if($query->rowCount() == 1){
            $level = $query->fetch();
            
            $query = $db->prepare("SELECT count(*) FROM actions WHERE type = 1 AND itemID = :levelID AND IP = :ip");
            $query->execute([":levelID" => $levelID, ":ip" => $ip]);
            if($query->fetchColumn() == 0){
                $level["downloads"]++;
                $query = $db->prepare("UPDATE levels SET downloads = :downloads WHERE levelID = :levelID");
                $query->execute([":downloads" => $level["downloads"], ":levelID" => $levelID]);
                $query = $db->prepare("INSERT INTO actions (type, value1, actionDate, itemID, IP) VALUES (1, 'Download level', :time, :levelID, :ip)");
                $query->execute([":time" => time(), ":levelID" => $levelID, ":ip" => $ip]);
            }

            if(file_exists(dirname(__FILE__)."/../../data/levels/$levelID")){
                $levelString = file_get_contents(dirname(__FILE__)."/../../data/levels/$levelID");
            } else $levelString = "kS1,50,kS2,110,kS3,255,kS4,50,kS5,110,kS6,255;";

            $levelDesc = base64_decode($level["levelDesc"]);
            $uploadDate = $level["uploadDate"];
            $updateDate = $level["updateDate"];
            if($level["updateDate"] <= $level["updateDate"]) $updateDate = "Level is don't have updates";

            // Output for version 1.0
            $lvlString = "1:".$level["levelID"]; // levelID
            $lvlString .= ":2:".$level["levelName"]; // level name
            $lvlString .= ":3:".$levelDesc; // level description
            $lvlString .= ":4:".$levelString; // level data
            $lvlString .= ":5:".$level["levelVersion"]; // level version
            $lvlString .= ":6:".$level["userID"]; // userID
            $lvlString .= ":8:10:9:".($level["difficulty"] * 10); // level difficulty
            $lvlString .= ":10:".$level["downloads"]; // downloads on level
            $lvlString .= ":12:".$level["audioTrack"]; // audiotrack on level
            $lvlString .= ":13:".$level["gameVersion"]; // game version
            $lvlString .= ":14:".$level["likes"]; // likes on level
            $lvlString .= ":15:".$level["levelLength"]; // level length
            $lvlString .= ":16:0"; // likes - count = likes on level

            echo $lvlString;
        } else exit("-1");
    } else exit("-1");
?>