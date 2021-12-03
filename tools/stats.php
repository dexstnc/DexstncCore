<?php
    function getStats($select, $table, $where1 = "", $where2 = "", $where3 = ""){
        include "../components/include/database.php";

        $query = $db->prepare("SELECT $select FROM $table $where1 $where2 $where3");
        $query->execute();
        return $query->fetchColumn();
    }

    $time = time();

    $usersStats["count"] = getStats("count(*)", "users");
    $usersStats["maxStars"] = getStats("max(stars)", "users");
    $usersStats["maxDemons"] = getStats("max(demons)", "users");
    $usersStats["maxCreatorPoints"] = getStats("max(creatorPoints)", "users");

    $table = "<table><caption>Users Stats</caption><th>Count</th><th>MAX Stars</th><th>MAX Demons</th><th>MAX Creator Points</th>";
    $table .= '<tr><td>'.$usersStats["count"].'</td><td>'.$usersStats["maxStars"].'</td><td>'.$usersStats["maxDemons"].'</td><td>'.$usersStats["maxCreatorPoints"].'</td></tr>';
    echo $table .= "</table>";

    $levels["na"]["count"] = getStats("count(*)", "levels", "WHERE difficulty = 0");
    $levels["na"]["unrated"] = getStats("count(*)", "levels", "WHERE difficulty = 0", "AND rated = 0");
    $levels["na"]["rated"] = getStats("count(*)", "levels", "WHERE difficulty = 0", "AND rated = 1");

    $levels["easy"]["count"] = getStats("count(*)", "levels", "WHERE difficulty = 1");
    $levels["easy"]["unrated"] = getStats("count(*)", "levels", "WHERE difficulty = 1", "AND rated = 0");
    $levels["easy"]["rated"] = getStats("count(*)", "levels", "WHERE difficulty = 1", "AND rated = 1");

    $levels["normal"]["count"] = getStats("count(*)", "levels", "WHERE difficulty = 2");
    $levels["normal"]["unrated"] = getStats("count(*)", "levels", "WHERE difficulty = 2", "AND rated = 0");
    $levels["normal"]["rated"] = getStats("count(*)", "levels", "WHERE difficulty = 2", "AND rated = 1");

    $levels["hard"]["count"] = getStats("count(*)", "levels", "WHERE difficulty = 3");
    $levels["hard"]["unrated"] = getStats("count(*)", "levels", "WHERE difficulty = 3", "AND rated = 0");
    $levels["hard"]["rated"] = getStats("count(*)", "levels", "WHERE difficulty = 3", "AND rated = 1");

    $levels["harder"]["count"] = getStats("count(*)", "levels", "WHERE difficulty = 4");
    $levels["harder"]["unrated"] = getStats("count(*)", "levels", "WHERE difficulty = 4", "AND rated = 0");
    $levels["harder"]["rated"] = getStats("count(*)", "levels", "WHERE difficulty = 4", "AND rated = 1");

    $levels["insane"]["count"] = getStats("count(*)", "levels", "WHERE difficulty = 5");
    $levels["insane"]["unrated"] = getStats("count(*)", "levels", "WHERE difficulty = 5", "AND demon = 0", "AND rated = 0");
    $levels["insane"]["rated"] = getStats("count(*)", "levels", "WHERE difficulty = 5", "AND demon = 0", "AND rated = 1");

    $levels["demon"]["count"] = getStats("count(*)", "levels", "WHERE difficulty = 5");
    $levels["demon"]["unrated"] = getStats("count(*)", "levels", "WHERE difficulty = 5", "AND demon = 1", "AND rated = 0");
    $levels["demon"]["rated"] = getStats("count(*)", "levels", "WHERE difficulty = 5", "AND demon = 1", "AND rated = 1");

    $table = "<table><caption>Levels</caption><th>Difficulty</th><th>Count</th><th>Unrated</th><th>Rated</th>";
    foreach($levels AS $key => $value){
        $table .= '<tr><td>'.$key.'</td><td>'.$value["count"].'</td><td>'.$value["unrated"].'</td><td>'.$value["rated"].'</td></tr>';
    }
    echo $table .= "</table>";

    $levelsStats["count"] = getStats("count(*)", "levels");
    $levelsStats["minLikes"] = getStats("min(likes)", "levels");
    $levelsStats["maxLikes"] = getStats("max(likes)", "levels");
    $levelsStats["deleted"] = getStats("count(*)", "levels", "WHERE deleted = 1");
    $levelsStats["lastUpload"] = getStats("max(uploadDate)", "levels");

    $table = "<table><caption>Levels Stats</caption><th>Count</th><th>MIN Likes</th><th>MAX Likes</th><th>Last Upload</th><th>Deleted</th>";
    $table .= '<tr><td>'.$levelsStats["count"].'</td><td>'.$levelsStats["minLikes"].'</td><td>'.$levelsStats["maxLikes"].'</td><td>'.($time - $levelsStats["lastUpload"]).' seconds ago</td><td>'.$levelsStats["deleted"].'</td></tr>';
    echo $table .= "</table>";

    $commentsStats["count"] = getStats("count(*)", "comments");
    $commentsStats["minLikes"] = getStats("min(likes)", "comments");
    $commentsStats["maxLikes"] = getStats("max(likes)", "comments");
    $commentsStats["deleted"] = getStats("count(*)", "comments", "WHERE deleted = 1");
    $commentsStats["lastUpload"] = getStats("max(uploadDate)", "comments");

    $table = "<table><caption>Comments Stats</caption><th>Count</th><th>MIN Likes</th><th>MAX Likes</th><th>Last Upload</th><th>Deleted</th>";
    $table .= '<tr><td>'.$commentsStats["count"].'</td><td>'.$commentsStats["minLikes"].'</td><td>'.$commentsStats["maxLikes"].'</td><td>'.($time - $commentsStats["lastUpload"]).' seconds ago</td><td>'.$commentsStats["deleted"].'</td></tr>';
    echo $table .= "</table>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats</title>
    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        table{
            text-align: center;
            border: 1px solid black;
        }
        table:not(:first-of-type){
            margin-top: 20px;
        }
        caption, th, td{
            padding: 2px 5px;
        }
        caption{
            font-size: 20px;
            font-weight: 700;
            border: 1px solid black;
            margin-bottom: 2px;
        }
        td{
            border: 1px solid black;
        }
    </style>
</head>
<body>   
</body>
</html>