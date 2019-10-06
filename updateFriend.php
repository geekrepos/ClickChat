<?php
session_start();
include("dbconnect/connect.php");
$userid = NULL;
$response = array();
if(isset($_SESSION['chat_user_id'])) {
    $userid = $_SESSION['chat_user_id'];
}
else{
    header('Location: index.php');
}
if(!isset($_GET['specialkey'])){
    $response['error'] = TRUE;
    $response['message'] = "The Page is not accessible";
    echo json_encode($response);
}
else {
    $qry = "UPDATE `user_info` SET `lastUpdatedTime` = NOW() WHERE `userid` = '$userid'";
    $db->query($qry);
    $query = "SELECT user_info.userid, user_info.lastUpdatedTime, user_info.online, user_info.username, user_info.photo_path From user_info," .
        "(SELECT friends_info.FriendID FROM friends_info WHERE friends_info.UserID = '$userid') AS O " .
        "WHERE user_info.userid = O.friendID";
    //echo $query;
    $result = $db->query($query);
    if (!$result) {
    } else {
        //$response = "";
        while ($row = $result->fetch_assoc()) {
            $record['userid']          = $row['userid'];
            $record['photo_path']      = $row['photo_path'];
            $record['username']        = $row['username'];
            $record['lastUpdatedTime'] = $row['lastUpdatedTime'];
            $friends[] = $record;
//            $response .= $row['userid']."/&INFO&/". $row['username']."/&INFO&/".$row['photo_path']."/&INFO&/".$row['online']."/&INFO&/".$row['lastUpdatedTime']."/&FRD&/";
        }
        //echo $response;
        $response['error'] = FALSE;
        $response['friends'] = $friends;
        echo json_encode($response);
    }
}
?>