<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();

    // Data from version 1.1
    $levelID = $gp->getPost("levelID", "n"); if($levelID === "") exit("-1");
    $page = $gp->getPost("page", "n"); if($page === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $offset = $page*10;

        $query = $db->prepare("SELECT count(*) FROM comments WHERE levelID = :levelID");
        $query->execute([":levelID" => $levelID]);
        $commentsCount = $query->fetchColumn();

        $query = $db->prepare("SELECT * FROM comments WHERE levelID = :levelID ORDER BY uploadDate DESC LIMIT 10 OFFSET $offset");
        $query->execute([":levelID" => $levelID]);
        $comments = $query->fetchAll();

        $commentString = "";
        foreach($comments AS $comment){
            $commentText = base64_decode($comment["comment"]);

            // Output for version 1.1
            $commentString .= "2~".$commentText; // Comment
            $commentString .= "~3~".$comment["userID"]; // userID
            $commentString .= "~4~".$comment["likes"]; // Likes on comment
            $commentString .= "~5~0"; // likes - count = likes on comment
            $commentString .= "~6~".$comment["commentID"]; // commentID
            $commentString .= "|";

            $userString .= $comment["userID"].":".$f->getUserName($comment["userID"])."|";
        }

        echo substr($commentString, 0, -1)."#".substr($userString, 0, -1)."#".$commentsCount.":".$offset.":10";
    } else exit("-1");
?>