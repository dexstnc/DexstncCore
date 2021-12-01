<?php
    include "../include/database.php";

    $query = $db->prepare("SELECT roleID, roleName FROM roles ORDER BY roleID ASC");
    $query->execute();
    $roles = $query->fetchAll();

    foreach($roles AS $role){
        echo '<h2>'.$role["roleName"].'\'s</h2><ul>';

        $query = $db->prepare("SELECT roleassign.userID AS userID, users.userName AS userName FROM roleassign INNER JOIN users ON roleassign.userID = users.userID WHERE roleassign.roleID = :roleID ORDER BY roleassign.userID ASC");
        $query->execute([':roleID' => $role["roleID"]]);
        $users = $query->fetchAll();

        foreach($users AS $user){
            echo '<li>'.$user["userName"].'</li>';
        }

        echo '</ul>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            margin: 0;
        }
        body{
            padding: 20px;
        }
        ul{
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
</body>
</html>