<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/levels.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();
    $ip = $f->getIP();

    // Data from version 1.0
    $levelID = $gp->getPost("levelID", "n"); if($levelID === "") exit("-1");
    $rating = $gp->getPost("rating", "n"); if($rating === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $query = $db->prepare("SELECT count(*) FROM actions WHERE type = 3 AND value1 = :levelID AND IP = :ip");
        $query->execute([":levelID" => $levelID, ":ip" => $ip]);
        if($query->fetchColumn() == 0){
            $query = $db->prepare("INSERT INTO actions (type, value1, value2, IP, actionDate) VALUES (3, :levelID, :rating, :ip, :time)");
            $query->execute([":levelID" => $levelID, ":rating" => $rating, ":ip" => $ip, ":time" => time()]);

            if($autoRate["use"] === true){
                $query = $db->prepare("SELECT value2 AS rate FROM actions WHERE type = 3 AND value1 = :levelID");
                $query->execute([":levelID" => $levelID]);
                $rates = $query->fetchAll();

                $ratesCount = 0; $ratesSum = 0;
                foreach($rates AS $rate){
                    $ratesSum += $rate["rate"];
                    $ratesCount++;
                }

                if($ratesCount >= $autoRate["count"]){
                    $totalDifficulty = floor($ratesSum/$ratesCount);

                    $query = $db->prepare("UPDATE levels SET difficulty = :difficulty WHERE levelID = :levelID");
                    $query->execute([":difficulty" => $totalDifficulty, ":levelID" => $levelID]);
                }
            }

            exit("1");
        } else exit("-1");
    } else exit("-1");
?>