<?php
    set_time_limit(0);
    
    include dirname(__FILE__)."/../config/settings.php";

    $time = time();

    if(file_exists(dirname(__FILE__)."/include/logs.php")){
        include dirname(__FILE__)."/include/logs.php";

        $newTime = $time - $cronRun; // Run CRON only once every 5 minutes
        if($lastStart > $newTime) exit('Please wait '.($lastStart - $newTime).' seconds and start again.');
    }

    $file = fopen(dirname(__FILE__)."/include/logs.php", "w");
    fwrite($file, '<? $lastStart = '.$time.' ?>');
    fclose($file);

    echo "CRON started<hr>";

    include "include/fixRatedLevels.php";
    echo "<hr>"; ob_flush(); flush();

    include "include/autoCreatorPoints.php";
    echo "<hr>"; ob_flush(); flush();

    echo "CRON finished<br>";
?>