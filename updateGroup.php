<?php
session_start();
include("dbconnect/connect.php");
header('Content-type: application/json');
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
    $query = "Select groups_info.groupID, groups_info.groupName from ".
             "groups_info, (SELECT groupID FROM group_members WHERE group_members.memberID = '$userid') AS o ".
             "Where groups_info.groupID = o.groupID;";
    $result = mysql_query($query);
    if (!$result) {
    }
    else {
        $final_response = array();
        while ($row = mysql_fetch_assoc($result)) {
            $groupID = $row['groupID'];
            $response = array($groupID,$row['groupName']);
            $query = "SELECT * FROM `group_members` WHERE groupID = $groupID AND NOT memberID = '$userid'";
            $result2 = mysql_query($query);
            if($result2){
                $temp_response = array();
                while($row2 = mysql_fetch_assoc($result2)){
                    array_push($temp_response, $row2['memberID']);
                }
                array_push($response, $temp_response);
            }
            array_push($final_response, $response);
        }
        echo json_encode($final_response);
        //print$final_response);
    }
}
?>