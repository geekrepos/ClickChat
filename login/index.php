<?php
    session_start();
    include("../dbconnect/connect.php");
    $userid = NULL; $uname = NULL ; $pass = NULL;
    if(!isset($_GET['specialkey'])){
        echo "The Page is not accessible";
    }
    else{
        if(isset($_GET['uid'])){
            $userid = $_GET['uid'];
        }
        if(isset($_GET['pass'])){
            $pass = $_GET['pass'];
        }
        $query = "SELECT * FROM `user_info` WHERE userid = '$userid'";
        $result = $db->query($query);
        $row   = $result->num_rows;
        if(!$row > 0){
            echo "ErrorCode0";
        }
        else{
            $row = $result->fetch_assoc();
            if(!strcmp($row['password'], $pass)){
                $_SESSION['chat_user_id'] = $userid;
                header('Location: ../home.php');
                echo "ErrorCode1";
                exit;
            }
            else{
                echo "ErrorCode1";
            }
        }
    }
?>