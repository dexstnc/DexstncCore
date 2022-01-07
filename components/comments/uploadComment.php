<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/limits.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    require_once dirname(__FILE__)."/../include/commands.php";
    $cmd = new Commands();
    
    $f->checkBanIP();
    $ip = $f->getIP();

    // Data from version 1.1
    $udid = $gp->getPost("udid", "su"); if($udid === "") exit("-1");
    $userName = $gp->getPost("userName", "s"); if($userName === "") exit("-1");
    $levelID = $gp->getPost("levelID", "n"); if($levelID === "") exit("-1");
    $comment = $gp->getPost("comment", "sc"); if($comment === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $comment = base64_encode($comment);
        $userID = $f->getUserID($udid, $userName);

        if($cmd->isCommand($levelID, $userID, base64_decode($comment))) exit("-1");

        if($uploadCommentLimit["use"] === true){
            $query = $db->prepare("SELECT count(*) FROM comments WHERE (IP = :ip OR userID = :userID) AND uploadDate > :time");
            $query->execute([':ip' => $ip, ':userID' => $userID, ':time' => time() - $uploadCommentLimit["time"]]);
            if($query->fetchColumn() >= $uploadCommentLimit["count"]) exit("-1");
        }

        $query = $db->prepare("INSERT INTO comments (levelID, comment, uploadDate, userID, IP) VALUES (:levelID, :comment, :time, :userID, :ip)");
        $query->execute([':levelID' => $levelID, ':comment' => $comment, ':time' => time(), ':userID' => $userID, ':ip' => $ip]);

        exit("1");
    } else exit("-1");
?>