<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    $ip = $f->getIP();

    // Data - version 1.0
    $levelID = isset($_POST["levelID"]) ? $f->checkNum($_POST["levelID"]) : "";
    // $inc = isset($_POST["inc"]) ? $f->checkNum($_POST["inc"]) : "";

    // Check data
    if($levelID === "") exit("-1");
    // if($inc === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $query = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID LIMIT 1");
        $query->execute([':levelID' => $levelID]);
        if($query->rowCount() != 0){
            $level = $query->fetch();
            if(!file_exists(dirname(__FILE__)."/data/$levelID")) exit("-1");

            $query = $db->prepare("SELECT count(*) FROM actions WHERE type = 1 AND value1 = :levelID AND IP = :ip");
            $query->execute([':levelID' => $levelID, ':ip' => $ip]);
            if($query->fetchColumn() == 0){
                $level['downloads'] = $level['downloads'] + 1;
                $query = $db->prepare("UPDATE levels SET downloads = :downloads WHERE levelID = :levelID");
                $query->execute([':downloads' => $level['downloads'], ':levelID' => $levelID]);
                $query = $db->prepare("INSERT INTO actions (type, value1, IP, actionDate) VALUES (1, :levelID, :ip, :time)");
                $query->execute([':levelID' => $levelID, ':ip' => $ip, ':time' => time()]);
            }

            $levelString = file_get_contents(dirname(__FILE__)."/data/$levelID");
            $levelDesc = base64_decode($level['levelDesc']);

            // Output - version 1.0
            $lvlString = "1:".$level["levelID"]; // level ID
            $lvlString .= ":2:".$level["levelName"]; // level name
            $lvlString .= ":3:".$levelDesc; // level description
            $lvlString .= ":4:".$levelString; // level data
            $lvlString .= ":5:".$level["levelVersion"]; // level version
            $lvlString .= ":6:".$level["userID"]; // user ID
            $lvlString .= ":8:10:9:".($level["difficulty"] * 10); // level difficulty
            $lvlString .= ":10:".$level["downloads"]; // downloads on level
            $lvlString .= ":12:".$level["audioTrack"]; // audiotrack on level
            $lvlString .= ":13:".$level["gameVersion"]; // game version
            $lvlString .= ":14:".$level["likes"]; // likes on level
            $lvlString .= ":15:".$level["levelLength"]; // level length
            $lvlString .= ":16:0"; // likes - count = likes on level
            // Output - version 1.3
            $lvlString .= ":17:".$level["demon"]; // is demon
            $lvlString .= ":18:".$level["stars"]; // stars

            echo $lvlString;
        } else exit("-1");
    } else exit("-1");
?>
