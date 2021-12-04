<?php
    include dirname(__FILE__)."/../../components/include/database.php";
    include dirname(__FILE__)."/../../config/settings.php";

    echo "Auto Creator Points:<br>";

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
            $users = explode(", ", $newUsers);
            foreach($users AS $user) echo 'Creator points fixed from (userID: '.$user.')<br>';
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
    
                echo 'The (userID: '.$user[0].') was given '.$user[1].' creator point(-s)<br>';
            } else $newUsers .= $user[0].', ';
        }
        if($newUsers != ""){
            $newUsers = substr($newUsers, 0, -2);
            $query = $db->prepare("UPDATE users SET creatorPoints = 0 WHERE userID IN ($newUsers)");
            $query->execute();
            $users = explode(", ", $newUsers);
            foreach($users AS $user) echo 'Creator points removed from (userID: '.$user.')<br>';
        }
    }
?>