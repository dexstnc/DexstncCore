<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();
    $ip = $f->getIP();

    // Data from version 1.0
    $levelID = $gp->getPost("levelID", "n"); if($levelID === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $query = $db->prepare("SELECT count(*) FROM actions WHERE type = 2 AND value1 = :levelID AND IP = :ip");
        $query->execute([":levelID" => $levelID, ":ip" => $ip]);
        if($query->fetchColumn() == 0){
            $query = $db->prepare("SELECT likes FROM levels WHERE levelID = :levelID LIMIT 1");
            $query->execute([":levelID" => $levelID]);
            if($query->rowCount() == 1){
                $likes = $query->fetchColumn() + 1;
                $query = $db->prepare("UPDATE levels SET likes = :likes WHERE levelID = :levelID");
                $query->execute([":likes" => $likes, ":levelID" => $levelID]);
                $query = $db->prepare("INSERT INTO actions (type, value1, value2, IP, actionDate) VALUES (2, :levelID, 1, :ip, :time)");
                $query->execute([":levelID" => $levelID, ":ip" => $ip, ":time" => time()]);

                exit("1");
            } else exit("-1");
        } else exit("-1");
    } else exit("-1");
?>