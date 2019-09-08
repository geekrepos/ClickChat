<?php
session_start();
include("dbconnect/connect.php");
$userid = NULL;
if(isset($_SESSION['chat_user_id'])) {
    $userid = $_SESSION['chat_user_id'];
}
else{
    header('Location: index.php');
}
if(!isset($_GET['specialkey'])){
    echo "The Page is not accessible";
}
else {
    $query = "SELECT user_info.userid, user_info.online, user_info.username, user_info.photo_path From user_info," .
        "(SELECT friends_info.FriendID FROM friends_info WHERE friends_info.UserID = '$userid') AS O " .
        "WHERE user_info.userid = O.friendID";
    //echo $query;
    $result = $db->query($query);
    if (!$result) {
    } else {
        $response = "";
        while ($row = $result->fetch_assoc()) {
            $response .= $row['userid']."/&INFO&/". $row['username']."/&INFO&/".$row['photo_path']."/&INFO&/".$row['online']."/&FRD&/";
        }
        echo $response;
    }
}
?>