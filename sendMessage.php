<?php
session_start();
include("dbconnect/connect.php");
$userid = NULL; $to = null;
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
    if(isset($_GET['to'])){
        $to = $_GET['to'];
    }
    if(isset($_GET['message'])){
        $message = $_GET['message'];
    }
    if(isset($_GET['toGroup'])){
        $query = "INSERT INTO `message_store`(`SenderID`, `ReceiverID`, `Message`, `toGroup`) VALUES ('$userid', '$to', '$message', 1)";
    }
    else{
        $query = "INSERT INTO `message_store`(`SenderID`, `ReceiverID`, `Message`) VALUES ('$userid', '$to', '$message')";
    }
    $result = $db->query($query);
    $query = "SELECT `MessageID` FROM `message_store` WHERE `SenderID` = '$userid' AND `ReceiverID` = '$to' ORDER BY `MessageID` DESC LIMIT 1;";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $messageid = $result->fetch_assoc();
        echo $messageid['MessageID'];
    }
    else{
        echo "ErrorCode0";
    }
}
?>