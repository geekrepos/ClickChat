<?php
//$_POST = json_decode(file_get_contents("php://input"), true);
// print_r($_POST); 
include("../dbconnect/connect.php");

$base_dir = "../";

//echo $_FILES['uphoto']['name'];

    $userid = NULL; $uname = NULL ; $pass = NULL;
    if(!isset($_POST['specialkey'])){
        echo "The Page is not accessible";
    }
    else{
        if(isset($_POST['uid']) && $_POST['uname'] && $_POST['pass']){
            $userid = trim($_POST['uid']);
            $uname = trim($_POST['uname']);
            $pass = trim($_POST['pass']);    
            $query = "SELECT * FROM `user_info` WHERE userid = '$userid'";
            $result = $db->query($query);
            if(!$result->num_rows){
                $photo = basename($_FILES['uphoto']['name']);
                $tmp_name = $_FILES['uphoto']['tmp_name'];
                if(move_uploaded_file($tmp_name, $base_dir."uploads/photos/$userid.$photo")){
                    $query = "INSERT INTO `user_info`(`userid`, `username`, `password`, `photo_path`) VALUES ('$userid','$uname','$pass','$userid.$photo')";
                    if($db->query($query)){   
                        echo "ErrorCode1";
                    }
                    else{
                        echo "ErrorCode0";
                    }                    
                }
                else{
                    echo "ErrorCode0";
                }
            }
            else{
                echo "ErrorCode0";
            }
        } 
        else{
            echo "ErrorCode2";
        }
    }
?>