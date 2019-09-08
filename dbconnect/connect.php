<?php
    $db = new mysqli('localhost', 'root', '', 'clickchat');
    if(!$db){
        die("Not Connected" . $db->connect_error);
//        echo "Not connected.";
    }
    else{
        //echo $db;
    }
?>