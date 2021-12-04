<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/settings.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    // Data - version 1.0
    $levelID = isset($_POST["levelID"]) ? $f->checkNum($_POST["levelID"]) : "";
    $levelVersion = isset($_POST["levelVersion"]) ? $f->checkNum($_POST["levelVersion"]) : "";
    $gameVersion = isset($_POST["gameVersion"]) ? $f->checkNum($_POST["gameVersion"]) : "";

    // Check data
    if($levelID === "") exit("-1");
    if($levelVersion === "") exit("-1");
    if($gameVersion === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        if($checkGameVersion AND $gameVersion != $totalGameVersion) exit("-1");

        $query = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID LIMIT 1");
        $query->execute([':levelID' => $levelID]);
        if($query->rowCount() != 0){
            $level = $query->fetch();
            if($level["levelVersion"] == $levelVersion) exit("-1");

            if(file_exists(dirname(__FILE__)."/../../data/levels/$levelID")){
                $levelString = file_get_contents(dirname(__FILE__)."/../../data/levels/$levelID");
            } else $levelString = "";

            $levelDesc = base64_decode($level['levelDesc']);

            // Output - version 1.0
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
            // Output - version 1.3
            $lvlString .= ":17:".$level["demon"]; // is demon
            $lvlString .= ":18:".$level["stars"]; // stars
            // Output - version 1.6
            $lvlString .= ":19:".$level["featured"]; // featured
            $lvlString .= ":25:".$level["auto"]; // is auto

            echo $lvlString;
        } else exit("-1");
    } else exit("-1");
?>