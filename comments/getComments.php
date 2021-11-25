<?php
    chdir(dirname(__FILE__));

    include "../include/database.php";
    require_once "../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();

    $ip = $f->getIP();

    // Data - version 1.2
    $levelID = isset($_POST["levelID"]) ? $f->checkNum($_POST["levelID"]) : "";
    $page = isset($_POST["page"]) ? $f->checkNum($_POST["page"]) : 0;

    // Check data
    if($levelID === "") exit("-1");
    if($page === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $offset = $page*10;

        $string = " WHERE deleted = 0 AND levelID = :levelID ORDER BY uploadDate DESC LIMIT 10 OFFSET $offset";

        $query = $db->prepare("SELECT count(*) FROM comments".$string);
        $query->execute([':levelID' => $levelID]);
        $commentsCount = $query->fetchColumn();

        $query = $db->prepare("SELECT * FROM comments".$string);
        $query->execute([':levelID' => $levelID]);
        $comments = $query->fetchAll();

        foreach($comments AS $comment){
            $commentString .= "2~".base64_decode($comment['comment']); // Comment
            $commentString .= "~3~".$comment["userID"]; // userID
            $commentString .= "~4~".$comment["likes"]; // Likes on comment
            $commentString .= "~5~0"; // likes - count = likes on level
            $commentString .= "~6~".$comment["commentID"]; // commentID
            $commentString .= "|";

            $userString .= $comment['userID'].":".$f->getUserName($comment['userID'])."|";
        }

        $commentString = substr($commentString, 0, -1);
        $userString = substr($userString, 0, -1);
        echo $commentString."#".$userString;
        echo "#".$commentsCount.":".$offset.":10";
    } else exit("-1");
?>
