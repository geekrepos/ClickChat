<?php
session_start();
include("dbconnect/connect.php");
$userid = NULL; $members = null; $gname = null;
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
    if(isset($_POST['members'])){
        $members = json_decode(stripslashes($_POST['members']));
    }
    if(isset($_GET['gname'])){
        $gname = $_GET['gname'];
    }
    $query = "INSERT INTO `groups_info`(`groupName`) VALUES ('$gname')";
    echo $query;
    $result = mysql_query($query);
    $query = "SELECT `groupID` FROM `groups_info` ORDER BY `groupID` DESC LIMIT 1";
    echo $query;
    $result = mysql_query($query);
    echo "Done";
    if (mysql_num_rows($result) > 0){
        $groupid = mysql_fetch_assoc($result);
        $groupid = $groupid['groupID'];
        foreach($members as $member){
            $query = "INSERT INTO `group_members`(`groupID`, `memberID`) VALUES ($groupid, '$member')";
            mysql_query($query);
        }
        $query = "INSERT INTO `group_members`(`groupID`, `memberID`) VALUES ($groupid, '$userid')";
        mysql_query($query);
        echo $groupid;
    }
    else{
        echo "Error";
    }
}
?>