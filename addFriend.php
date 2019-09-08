<?php
    session_start();
    include("dbconnect/connect.php");
    $userid = NULL; $friendID = null;
    if(isset($_SESSION['chat_user_id'])) {
        $userid = $_SESSION['chat_user_id'];
    }
    else{
        header('Location: index.php');
    }
    if(!isset($_GET['specialkey'])){
        echo "The Page is not accessible";
    }
    else{
        if(isset($_GET['friendID'])){
            $friendID = $_GET['friendID'];
        }
        $query = "INSERT INTO `friends_info` (`UserID`, `FriendID`) VALUES ('$userid', '$friendID')";
        //echo $query."                   ...............       ";
        
        $result = $db->query($query);
        $query = "INSERT INTO `friends_info` (`UserID`, `FriendID`) VALUES ('$friendID', '$userid')";
    //    echo $query;
        $result = $db->query($query);
        if(!$result){
            echo "ErrorCode0";
        }
        else{
            return 1;
        }
    }
?>