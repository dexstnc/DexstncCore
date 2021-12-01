<?php
    function listdir($dir){
        $string = "";
        $files = scandir($dir);
        foreach($files as $file) {
            if(pathinfo($file, PATHINFO_EXTENSION) == "php" AND $file != "index.php"){
                $string .= '<li><a href="'.$dir.'/'.$file.'">'.substr($file, 0, -4).'</a></li>';
            }
        }
        return $string;
    }

    echo "<h2>Tools:</h2><ul>";
    echo listdir(".");
    echo "</ul>";
?>
