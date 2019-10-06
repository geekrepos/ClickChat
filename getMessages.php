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
    if(isset($_GET['isGroup'])){
        if(isset($_GET['groupID']) && isset($_GET['messageAfter'])){
            $groupID = $_GET['groupID'];
            $messageFlag = $_GET['messageAfter'];
            $query = "Select o.MessageID,o.SenderID, o.Message, user_info.username FROM user_info, (SELECT MessageID,SenderID, Message FROM `message_store` WHERE ReceiverID = '$groupID' HAVING MessageID > $messageFlag) AS o WHERE user_info.userid = o.SenderID";
            $result = $db->query($query);
            $message = array();
            if($result){
                while($row = $result->fetch_assoc()){
                    $temp_message = array();
                    array_push($temp_message, $row['MessageID']);
                    array_push($temp_message, $row['SenderID']);
                    array_push($temp_message, $row['username']);
                    array_push($temp_message, $row['Message']);
                    array_push($message, $temp_message);
                }
            }
            echo json_encode($message);
        }
    }
    else{
        if(isset($_GET['friendID']) && isset($_GET['messageAfter'])){
            $friendID = $_GET['friendID'];
            $messageFlag = $_GET['messageAfter'];
            $messageFlag = $messageFlag=='undefined'?0:$messageFlag;
            $query = "SELECT MessageID, SenderID, Message FROM `message_store` WHERE SenderID = '$userid' AND ReceiverID = '$friendID' OR SenderID = '$friendID' AND ReceiverID = '$userid' HAVING MessageID > $messageFlag";
            //echo $query;
            $result = $db->query($query);
            $message = null;
            while($row = $result->fetch_assoc()){
                if($row['SenderID']!=$userid){
                    $message .= $row['MessageID'] . "/&DIFF&/" . $row['Message'] . "/&DIFF&/"."rec"."/&MSG&/";
                }
                else{
                    $message .= $row['MessageID'] . "/&DIFF&/" . $row['Message'] . "/&DIFF&/"."sen"."/&MSG&/";
                }
            }
            $qry =  "SELECT lastUpdatedTime FROM user_info WHERE userid = '$friendID'";
            $result = $db->query($qry);
            $response['lastLogin']  =   $result?$result->fetch_row()[0]:null;
            $response['message']    =   $message;
            echo json_encode($response);
        }
    }
}
?>