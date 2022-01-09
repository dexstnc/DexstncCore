<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/scores.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();

    // Data from version 1.3
    $udid = $gp->getPost("udid", "su"); if($udid === "") exit("-1");
    $userName = $gp->getPost("userName", "s"); if($userName === "") exit("-1");
    $stars = $gp->getPost("stars", "n"); if($stars === "") exit("-1");
    $demons = $gp->getPost("demons", "n"); if($demons === "") exit("-1");
    $icon = $gp->getPost("icon", "n"); if($icon === "") exit("-1");
    $color1 = $gp->getPost("color1", "n"); if($color1 === "") exit("-1");
    $color2 = $gp->getPost("color2", "n"); if($color2 === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        if($stars < $demons * 10) exit("-1");
        $userID = $f->getUserID($udid, $userName);

        if($updateScoreLimit["use"] === true){
            $query = $db->prepare("SELECT sum(stars) AS stars, sum(demon) AS demons FROM levels");
            $query->execute(); $levelsInfo = $query->fetch();

            $maxStars = $updateScoreLimit["maxStars"] + $levelsInfo["stars"];
            if($stars > $maxStars) exit("-1");

            $maxDemons = $updateScoreLimit["maxDemons"] + $levelsInfo["demons"];
            if($demons > $maxDemons) exit("-1");
        }

        $query = $db->prepare("UPDATE users SET icon = :icon, color1 = :color1, color2 = :color2, stars = :stars, demons = :demons WHERE userID = :userID");
        $query->execute([":icon" => $icon, ":color1" => $color1, ":color2" => $color2, ":stars" => $stars, ":demons" => $demons, ":userID" => $userID]);

        $query = $db->prepare("INSERT INTO actions (type, value1, value2, value3, value4, actionDate, userID, IP) VALUES (10, 'Update score', :userName, :stars, :demons, :time, :userID, :ip)");
        $query->execute([":userName" => $userName, ":stars" => $stars, ":demons" => $demons, ":time" => $time, ":userID" => $userID, ":ip" => $ip]);

        echo $userID;
    } else exit("-1");
?>