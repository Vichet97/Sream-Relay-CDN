<?php
    header("HTTP/1.1 401 Unauthorized");
    echo "<h1>Unauthorized</h1>";
    echo "The page that you have requested could not be proceed.";
    exit();
    
    