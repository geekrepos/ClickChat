<?php
include("../dbconnect/connect.php");
$userid = NULL; $uname = NULL ; $pass = NULL;
if(!isset($_GET['specialkey'])){
    echo "The Page is not accessible";
}
else{
    if(isset($_GET['uid'])){
        $userid = trim($_GET['uid']);
    }
    if(isset($_GET['uname'])){
        $uname = trim($_GET['uname']);
    }
    if(isset($_GET['pass'])){
        $pass = trim($_GET['pass']);
    }
    if(!empty($userid) && !empty($uname) && !empty($pass)){
    $query = "SELECT * FROM `user_info` WHERE userid = '$userid'";
    $result = $db->query($query);
    if(!$result->num_rows){
        $query = "INSERT INTO `user_info`(`userid`, `username`, `password`) VALUES ('$userid','$uname','$pass')";
        if($db->query($query)){
            echo "ErrorCode1";
        }
    }
    else{
        echo "ErrorCode0";
    }}
    else{
        echo "ErrorCode2";
    }
}
    ?>