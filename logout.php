<?php
    session_start();
    include("./dbconnect/connect.php");
    if(!isset($_GET['specialkey'])){
        echo "The Page is not accessible";
    }
    else{
        if(isset($_SESSION['chat_user_id'])){
            $_SESSION['chat_user_id'] = NULL;
            $response['redirect'] = "index.php";
            $response['logoutStatus'] = TRUE;
            echo json_encode($response);
            //header('location: index.php');
        }
    }
?>