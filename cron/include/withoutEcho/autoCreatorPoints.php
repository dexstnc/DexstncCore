<?php
    include dirname(__FILE__)."/../../../components/include/database.php";
    include dirname(__FILE__)."/../../../config/settings.php";

    // Remove creatore points
    $query = $db->prepare("SELECT userID FROM users WHERE creatorPoints > 0");
    $query->execute();
    $users = $query->fetchAll();
    $newUsers = "";
    foreach($users AS $user) $newUsers .= $user["userID"].', ';
    if($newUsers != ""){
        $newUsers = substr($newUsers, 0, -2);
        $query = $db->prepare("SELECT userID, count(*) AS count FROM levels WHERE userID IN ($newUsers) GROUP BY userID");
        $query->execute();
        $users = $query->fetchAll();
        $newUsers = "";
        foreach($users AS $user) if($user["count"] == 0) $newUsers .= $user["userID"].', ';
        if($newUsers != ""){
            $newUsers = substr($newUsers, 0, -2);
            $query = $db->prepare("UPDATE users SET creatorPoints = 0 WHERE userID IN ($newUsers)");
            $query->execute();
        }
    }

    // Add creatore points
    $query = $db->prepare("SELECT levels.userID AS userID, users.creatorPoints AS creatorPoints, sum(levels.rated) AS rated FROM levels INNER JOIN users ON levels.userID = users.userID GROUP BY userID");
    $query->execute();
    $users = $query->fetchAll();
    $newUsers = "";
    foreach($users AS $user){
        $creatorPoints = 0;
        $creatorPoints += $user["rated"] * $ratedCPs;
        if($creatorPoints != $user["creatorPoints"]) $newUsers .= $user["userID"].":".$creatorPoints.", ";
    }
    if($newUsers != ""){
        $newUsers = substr($newUsers, 0, -2);
        $users = explode(", ", $newUsers);
        $newUsers = "";
        foreach($users AS $user){
            $user = explode(":", $user);
            if($user[1] > 0){
                $query = $db->prepare("UPDATE users SET creatorPoints = :cps WHERE userID = :userID");
                $query->execute([':cps' => $user[1], ':userID' => $user[0]]);
            } else $newUsers .= $user[0].', ';
        }
        if($newUsers != ""){
            $newUsers = substr($newUsers, 0, -2);
            $query = $db->prepare("UPDATE users SET creatorPoints = 0 WHERE userID IN ($newUsers)");
            $query->execute();
        }
    }
?>