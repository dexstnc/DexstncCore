<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/settings.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();
    $ip = $f->getIP();

    // Data from version 1.0
    $levelID = $gp->getPost("levelID", "n"); if($levelID === "") exit("-1");
    $levelVersion = $gp->getPost("levelVersion", "n"); if($levelVersion === "") exit("-1");
    $gameVersion = $gp->getPost("gameVersion", "n"); if($gameVersion === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        if($checkGameVersion["use"] === true AND $gameVersion !== $checkGameVersion["value"]) exit("-1");

        $query = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID LIMIT 1");
        $query->execute([":levelID" => $levelID]);
        if($query->rowCount() == 1){
            $level = $query->fetch();
            if($levelVersion === $level["levelVersion"]) exit("-1");
            
            $query = $db->prepare("SELECT count(*) FROM actions WHERE type = 1 AND value1 = :levelID AND IP = :ip");
            $query->execute([":levelID" => $levelID, ":ip" => $ip]);
            if($query->fetchColumn() == 0){
                $level["downloads"] = $level["downloads"] + 1;
                $query = $db->prepare("UPDATE levels SET downloads = :downloads WHERE levelID = :levelID");
                $query->execute([":downloads" => $level["downloads"], ":levelID" => $levelID]);
                $query = $db->prepare("INSERT INTO actions (type, value1, IP, actionDate) VALUES (1, :levelID, :ip, :time)");
                $query->execute([":levelID" => $levelID, ":ip" => $ip, ":time" => time()]);
            }

            if(file_exists(dirname(__FILE__)."/../../data/levels/$levelID")){
                $levelString = file_get_contents(dirname(__FILE__)."/../../data/levels/$levelID");
            } else $levelString = "kS1,50,kS2,110,kS3,255,kS4,50,kS5,110,kS6,255;";

            $levelDesc = base64_decode($level["levelDesc"]);

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