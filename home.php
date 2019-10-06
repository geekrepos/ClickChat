<?php
    session_start();
    require_once('dbconnect/connect.php');
    $username = null;
    if(isset($_SESSION['chat_user_id'])) {
        $userid = $_SESSION['chat_user_id'];
//$userid = 'avi';
        $query = "Select * from `user_info` where `userid` = '$userid'";
//echo $query;
        $result = $db->query($query);

        if($result==TRUE){
            if($result->num_rows > 0){
               
                $row = $result->fetch_assoc();
                $username = $row['username'];
                //echo "Hey I got something".$username;
            }
        }
    }
    else{
        header('Location: index.php');
    }
?>

<html>
    <head>
        <title><?php //if(isset($userid)){echo $userid;}
                    echo $username." - Click Chat";
               ?></title>
        <link rel="stylesheet" href="./styles/fonts/font_apply.css" type="text/css">
        <script src="scripts/jquery-3.2.0.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="bootstrap-3.3.7/dist/css/bootstrap.css">
        <link rel="stylesheet" href="styles/customScroll.css" type="text/css">
        <script src="bootstrap-3.3.7/dist/js/bootstrap.js" type="text/javascript"></script>
<!--        <script src="scripts/script.js" type="text/javascript"></script>-->
        <script>
            var activeElement = 1;
            var activeFriend = null;
            var updateFriendLCounter, updateMessageCounter, updateGroupLCounter, updateGMessageCounter;
            $(document).ready(
                function(){
                    updateFriendLCounter = setInterval(updateFriendList, 2000);
                    updateMessageCounter = setInterval(loadMessagesFrom, 2000);
                    updateGroupLCounter  = setInterval(updateGroupList, 2000);
                    updateGMessageCounter = setInterval(loadMessagesFromGroup, 2000);
                }
            );
            function alterShadow(e){
                $($('.glyphicon').get(activeElement)).removeClass('shadow');
                activeElement = e;
                $($('.glyphicon').get(e)).addClass('shadow');
            }
            
            function logout(){
                $.ajax({
                    url: 'logout.php?specialkey=true',
                    success: (response)=>{
                        response = JSON.parse(response);
                        if(response.logoutStatus===true){
                            window.location.href = response.redirect+"?logoutmsg='You have been successfully logged out!'";
                        }
                    }
                });
            }
            function addFriend(e){
                var customURI = "addFriend.php?friendID="+e.parentElement.id+"&specialkey=true";
                $.ajax({
                    type: "GET",
                    url : customURI,
                    success: function (response) {
                        if(response==1){
                            //refreshMessageList();
                            alert("Friend added successfully");
                        }
                    }
                });
            }
            function viewUsersModel(){
                $('#message-list').css("display", "none");
                $('#groups-list').css("display", "none");
                $('#users-list').css("display", "block");
            }
            function viewGroupModel(){
                $('#message-list').css("display", "none");
                $('#users-list').css("display", "none");
                $('#groups-list').css("display", "block");
                $('.message-container').css("display", "none");
                $('.group-creator-container').css("display", "block");
            }
            function viewMessageModel(){
                $('#groups-list').css("display", "none");
                $('#users-list').css("display", "none");
                $('#message-list').css("display", "block");
                $('.group-creator-container').css("display", "none");
                $('.message-container').css("display", "block");
                $('.group-messages').css("display","none")
            }
            function selectFriend(e){
                currentActive = "friend";
                $('.group-messages').css("display", "none");
                $('.private-messages').css("display", "block");
                if(activeFriend!=null){
                    $(activeFriend).removeClass('active-friend');
                    $("#"+activeFriend.id+"message_container").css("display", "none");
                    activeFriend = e.children[0];
                    $(activeFriend).addClass('active-friend');
                    $("#"+activeFriend.id+"message_container").css("display", "block");
                    loadMessagesFrom();
                }
                else{
                    activeFriend = e.children[0].id;
                    $(activeFriend).addClass('active-friend');
                    loadMessagesFrom();
                }
                $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight;
            }
            custom = null;
            function loadMessagesFrom(){
                var lastMessageID = getLastMessageID();
                //var messages = null;
                console.log("last msg yogi: " + lastMessageID);
                var customURI = "getMessages.php?friendID="+activeFriend.id+"&messageAfter="+lastMessageID+"&specialkey=true" ;
                console.log(customURI);
                $.ajax({
                    type: "GET",
                    url: customURI,
                    success: function(response){
                        response = JSON.parse(response);
                        lastLoginTime = response.lastLogin;
                        isLogged = isUserLoggedIn(convertDate(lastLoginTime));
                        $('#'+activeFriend.id+'_status')[0].style.background = isLogged?"radial-gradient("+ '#00ff00'+ ", transparent)":"radial-gradient("+ '#ff000f'+ ", transparent)";

                        response = response.message;
                        //alert(response);
                        if(response.trim().length>0){
                            messages = response.split('/&MSG&/');
                            messages.pop();
                            console.log(messages);
                            for(var i =0; i< messages.length; i++){
                                message = messages[i];
                                updateMessageBox(message.split('/&DIFF&/')[0], message.split('/&DIFF&/')[1], message.split('/&DIFF&/')[2]);
                            }
                        }
                    }
                });
            }
            function getLastMessageID(g){
//                if(g==1){
//                    if($("#group"+$(activeGroup).attr('group-id')+"message_container")[0]!=undefined){
//                        if($("#group"+$(activeGroup).attr('group-id')+"message_container")[0].childElementCount>0){
//                            return $("#group"+$(activeGroup).attr('group-id')+"message_container").children().last().children().attr("messageid");
//                        }
//                        else{ return 0;}
//                    }
//                }
                //else{
                    if($('#'+activeFriend.id+"message_container")[0]!=null && $('#'+activeFriend.id+"message_container")[0]!=undefined){
                        if($('#'+activeFriend.id+"message_container")[0].childElementCount>0){
                            return $('#'+activeFriend.id+"message_container").children().last().children().attr("messageid");
                        }
                        else{
                            return 0;
                        }
                    }
            //    }
            }
            currentActive = null;
            function sendMessage(e){
                var message = $('#message-input').val().trim();
                var ErrorCodeArray = {ErrorCode0: "Message Was Not Sent!"};
                var customURI;
                if(currentActive=="friend"){
                    customURI = "sendMessage.php?to="+activeFriend.id+"&message="+encodeURI(message)+"&specialkey="+true;
                }
                else if(currentActive == "group"){
                    customURI = "sendMessage.php?to="+$(activeGroup).attr('group-id')+"&message="+encodeURI(message)+"&toGroup=true"+"&specialkey="+true;
                }
                console.log(customURI);
                $.ajax({
                    type : "GET",
                    url : customURI,
                    success : function (response) {
                        if(ErrorCodeArray[response]!=undefined){alert(ErrorCodeArray[response]);}
                        else{
                            $('#message-input')[0].value = "";
                            if(currentActive=="friend"){
                                updateMessageBox(response, decodeURI(encodeURI(message).toString()));
                            }
                            else if(currentActive=="group"){
                                customResponse = [response, 'you','You',encodeURI(message)];
                                updateGroupMessageBox(customResponse);
                            }
                        }
                    }
                });
                return false;
            }
            function updateMessageBox(e, m, s){
                if(s == "rec"){
                    var html = "<div class='message-wrapper'><message class='message received' messageID=" + e+ ">"+m+"</message></div>".toLocaleString();
                }
                else{
                    var html = "<div class='message-wrapper'><message class='message message-right' messageID=" + e+ ">"+m+"</message></div>".toLocaleString();
                }
                console.log(html);
                $('#'+activeFriend.id+'message_container').append(html);
                $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight;
            }
            function convertDate(date) {
                var sqlDateStr = date;
                sqlDateStr = sqlDateStr.replace(/:| /g,"-");
                var YMDhms = sqlDateStr.split("-");
                var sqlDate = new Date();
                sqlDate.setFullYear(parseInt(YMDhms[0]), parseInt(YMDhms[1])-1,
                    parseInt(YMDhms[2]));
                sqlDate.setHours(parseInt(YMDhms[3]), parseInt(YMDhms[4]),
                    parseInt(YMDhms[5]), 0/*msValue*/);
                return sqlDate;
            }
            function isUserLoggedIn(lastLogged) {
                date = new Date();
                // debugger;
                return date.getHours()-lastLogged.getHours()>0?
                    false:date.getMinutes()-lastLogged.getMinutes()>0?
                        false:date.getSeconds() - lastLogged.getSeconds() < 30;
            }
            function updateFriendList(){
                var myfriends, i =0;
                var friendList = $('#message-list');
                var customURI = "updateFriend.php?specialkey=true";
                console.log(customURI);
                $.ajax({
                    type : "GET",
                    url : customURI,
                    success : function (response) {
                        response = JSON.parse(response);
                        if(!response.error){
                            // myfriends = response.split('/&FRD&/');
                            // debugger;
                            // console.log(myfriends);
                            // myfriends.pop();
                            friends = response.friends;

                            // if(friendList[0].childElementCount>0) {
                            //     for (i = 0; i<friends.length; i++) {
                            //         friend = friends[i];
                            //         if (friendList.children().last().children().attr("id") == friend[0]) {
                            //             ++i;
                            //             break;
                            //         }
                            //     }
                            // }
                            if(friends.length){
                                friends.map(function (friend, index) {
                                    temp = friendList[0].childElementCount?friendList[0].children[index].children[0]:null;
                                    bool = temp?temp.getAttribute('id'):temp;
                                    isLoggedIn = isUserLoggedIn(convertDate(friend.lastUpdatedTime));
                                    if(bool!==friend['userid']){
                                        var html = "<a isOnline=false onclick='selectFriend(this)'><div class='user vertical-center' id="+friend['userid']+"><div class='user-image'><img style='max-width: inherit; max-height: inherit; width: inherit; height: inherit' src=uploads/photos/"+friend['photo_path']+" ></div><span class='user-name'>"+friend['username']+"</span>" +
                                            "<span id="+friend.userid+"_status style='position: relative; margin-left: 75px; width: 10px;height: 10px;border-radius: 100px;'></span>"+
                                            "</div></a>";
                                        html = ($(html)[0]);
                                        // html[0].setAttribute('isOnline', isLoggedIn);
                                        html.children[0].children[2].style.background = isLoggedIn?"radial-gradient("+ '#00ff00'+ ", transparent)":"radial-gradient("+'#ff0000'+", transparent)";
                                        friendList.append(html);
                                        html = "<div id="+friend['userid']+"message_container></div>";
                                        $('.private-messages').append(html)
                                    }
                                    // debugger;
                                })
                                // for (i; i<myfriends.length; i++) {
                                //     // friend = myfriends[i];
                                //     // friend = friend.split('/&INFO&/');
                                //     // var html = "<a onclick='selectFriend(this)'><div class='user vertical-center' id="+friend[0]+"><div class='user-image'><img style='max-width: inherit; max-height: inherit; width: inherit; height: inherit' src="+friend[2]+" ></div><span class='user-name'>"+friend[1]+"</span>" +
                                //     //     "<span style='position: relative; margin-left: 75px; width: 10px;height: 10px;border-radius: 100px;'></span>"+
                                //     //     "</div></a>";
                                //     // html = ($(html)[0]);
                                //     // html.children[0].children[2].style.background = friend.online?"radial-gradient("+ '#00ff00'+ ", transparent)":"radial-gradient("+'#ff0000'+", transparent)";
                                //     // friendList.append(html);
                                //     // html = "<div id="+friend[0]+"message_container></div>";
                                //     // $('.private-messages').append(html);
                                // }
                            }
                        }
                    }
                });
            }
            var fn = null;
            function showGroupCreator(){
                viewGroupModel();
                var gCreatorContainer = $('.group-creator-container');
                gCreatorContainer.css("display", "block");
                var gMembers = $('#group-members-list');
                var messageList = $('.message-list');
                var html = "<div class='checkbox-custom'><div class=''></div></div>";
                gMembers[0].innerHTML = "";
                if($(messageList).children().length>0){
                    html = "<img src='' style='width: 90px; height: inherit;'><span style='font-size: 25px;margin-left: 20px;'>Avinash</span>" +
                        "<div style='position: relative;display: inline-block;width: 30px;height: 30px;margin-left: 80px;border-color: white;border: 1px solid white;'>" +
                        "<div style='width: 18px;align-self: center;height: 18px;background: white;margin: 5px 0px 0px 5px;'></div></div>";
                    debugger;
                }
                // for(var i =0; i< messageList.children().length; i++){
                //     friendList.push($($(messageList.children()[i]).children()[0]).clone()[0]);
                // }
                // fn = friendList[0];
                // for(var i = 0; i < friendList.length; i++) {
                //     $(fn[0]).attr("onclick", "check(this)").attr("user-checked","0").append(html);
                //     debugger;
                //     gMembers.append(friendList[i].outerHTML);
                // }
                return true;
            }
            function stop(){
                clearInterval(updateFriendLCounter);
                clearInterval(updateMessageCounter);
                clearInterval(updateGroupLCounter);
                clearInterval(updateGMessageCounter);
                clear();
            }
            function check(e){
                if($(e).attr("user-checked") != "1"){
                    $($($(e).attr("user-checked", "1").children()[2]).children()[0]).addClass("checkbox-dot");
                }
                else{
                    $($($(e).attr("user-checked", "0").children()[2]).children()[0]).removeClass("checkbox-dot");
                }
            }
            function getSelectedMembers(){
                var selectedMembers = [];
                members = $('#group-members-list').children();
                for(var i =0 ;  i < members.length; i++){
                    if($(members[i]).attr("user-checked") == "1"){
                        selectedMembers.push($(members[i]).attr("id"));
                    }
                }
                return selectedMembers;
            }
            function createGroup(){
                selectedMembers = getSelectedMembers();
                if(selectedMembers.length>=2){
                    groupName = $('.g-name').val();
                    var customURI = "createGroup.php?gname="+encodeURI(groupName)+"&specialkey="+true;
                    console.log(customURI);
                    $.ajax({
                        type : "POST",
                        data : {members: JSON.stringify(selectedMembers)},
                        url : customURI,
                        success : function (response) {
                            console.log(response);
                            //s ,ufktucktxxhgdjjfdjr
                        }
                    });
                }
                else{
                    alert("Group should have atleast 2 Members!");
                }
            }
            function getGroupMessages(){

            }
            var _0x3d50=["\x6C\x65\x6E\x67\x74\x68","\x70\x75\x73\x68"];
            function arr_diff(_0xd30cx2,_0xd30cx3){
                for(var _0xd30cx4=[],_0xd30cx5=[],_0xd30cx6=0;_0xd30cx6< _0xd30cx2[_0x3d50[0]];_0xd30cx6++){
                    _0xd30cx4[_0xd30cx2[_0xd30cx6]]=  !0
                };
                for(var _0xd30cx6=0;_0xd30cx6< _0xd30cx3[_0x3d50[0]];_0xd30cx6++){
                    _0xd30cx4[_0xd30cx3[_0xd30cx6]]? delete _0xd30cx4[_0xd30cx3[_0xd30cx6]]:_0xd30cx4[_0xd30cx3[_0xd30cx6]]=  !0
                };
                for(var _0xd30cx7 in _0xd30cx4){
                    _0xd30cx5[_0x3d50[1]](_0xd30cx7)
                };
                return _0xd30cx5
            }
            var myGroups = null;
            var groupr;
            function updateGroupList(){
                var  i =0;
                var customURI = "updateGroup.php?specialkey=true";
                console.log(customURI);
                groupsList = $('.groups-list');
                $.ajax({
                    type : "POST",
                    url : customURI,
                    dataType : 'json',
                    success : function (response) {
                        if(response!="false"){
                            if(myGroups){
                                response = arr_diff(response, myGroups);
                                if(response.length>0){
                                    for(i=0; i< response.length; i++){
                                        var group = response[i];
                                        groupID = group[0];
                                        groupName = group[1];
                                        groupMembers = group[2];
                                        if (parseInt(groupsList.children().last().attr("group-id"))<parseInt(groupID)) {
                                            groupr = this.group;
                                            updateGroupBox(groupID, groupName, groupMembers);
                                        }
                                    }
                                }
                            }
                            else{
                                myGroups = response;
                                for(i=0; i< response.length; i++){
                                    group = response[i];
                                    groupID = group[0];
                                    groupName = group[1];
                                    groupMembers = group[2];
                                    updateGroupBox(groupID, groupName, groupMembers);
                                }
                            }
                        }
                    }
                });
            }
            function updateGroupBox(gID, gName, gMembers){
                var groupsList = $('#groups-list');
                var html = "<div style='cursor:pointer' group-id="+gID+" onclick='selectGroup(this)' class='group-default vertical-center'>"
                    + "<div class='user-image group-create-image'>"
                    + "<img style='max-width: inherit; max-height: inherit; width: inherit; height: inherit' src='images/plus-7-xxl.png'></div>"
                    + "&nbsp;<span class='group-create-text'>"
                    +  gName + "</span><br><span class='member-list-group'> (" + gMembers.toString() + ", You)<span></div>";
                groupsList.append(html);
                html = "<div id=group"+gID+"message_container></div>";
                $('.group-messages').append(html);
            }
            var activeGroup = null;
            function selectGroup(e){
                currentActive = "group";
                $('.group-messages').css("display", "block");
                $('.private-messages').css("display", "none");
                if(activeGroup!=null){
                    $(activeGroup).removeClass('active-friend');
                    $("#group"+$(activeGroup).attr('group-id')+"message_container").css("display", "none");
                    activeGroup = e;
                    $(activeGroup).addClass('active-friend');
                    $("#group"+$(activeGroup).attr('group-id')+"message_container").css("display", "block");
                }
                else{
                    activeGroup = e;
                    $(activeFriend).addClass('active-friend');
                }
                $('.group-creator-container').css("display", "none");
                $('.message-container').css("display", "block");
                loadMessagesFromGroup();
                $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight;
            }
            function loadMessagesFromGroup(){
                var lastMessageID = getLastMessageID(1);
                //var messages = null;
                console.log("last msg yogi: " + lastMessageID);
                var customURI = "getMessages.php?groupID="+$(activeGroup).attr('group-id')+"&messageAfter="+lastMessageID+"&isGroup=true"+"&specialkey=true";
                console.log(customURI);
                $.ajax({
                    type: "GET",
                    url: customURI,
                    success: function(response){
                        response = JSON.parse(response);
                        if(typeof(response)=="object"){
                            for(var i = 0; i < response.length; i++){

                                updateGroupMessageBox(response[i]);
                            }
                        }
                    }
                });
            }
            function updateGroupMessageBox(response){
                m = response[3];
                s = response[2];
                sid = response[1];
                mid = response[0];
                messageBox = $("#group"+$(activeGroup).attr('group-id')+"message_container");
                html = "<div class='message-wrapper'>" +
                    "<message class='message' messageid="+mid+">" +
                    "<sender class='grp_message_sender' id="+sid+">"+s+"</sender>"+
                    "<msg>"+m+"</msg></message></div>";
                messageBox.append(html);
            }
            function getLastMessageIDGroup(){
                if($('#'+activeFriend.id+"message_container")[0].childElementCount>0){
                    return $('#'+activeFriend.id+"message_container").children().last().children().attr("messageid");
                }
                else{
                    return 0;
                }
            }
        </script>
        <style>
            body{
                width: 100%;
                height: 100%;
                margin: 0px;
                background: url(images/background.jpeg) no-repeat center center fixed;
                background-size: cover;
            }
            .main-content{
                width: 100%;
                height: 100%;
            }
            .vertical-center {
                min-height: inherit;
                min-width: inherit;
                display: -webkit-flex;
                display: -moz-flex;
                display: flex;
                align-items: center;
            }
            .content-wrapper{
                margin: 0 auto;
                background-color: rgba(0, 0, 0, 0.32);
                width: 65%;
                height: 80%;
                visibility: hidden;
                border-radius: 5px;
            }
            .vcenter {
                display: flex;
                align-items: center;
            }
            .sidebar-left{
                padding: 0px;
                visibility: visible;
                background-color: inherit;
                border-top-left-radius: 5px;
                border-bottom-left-radius: 5px;
                height: inherit;
                border-right: 1px solid rgba(0, 0, 0, 0.18);
            }
            .sidebar-right{
                padding: 0px;
                visibility: visible;
                background-color: inherit;
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;
                height: inherit;
            }
            .sidebar-left-header, .sidebar-right-header{
                padding: inherit;
                width: 100%;
                height: 20%;
                border-bottom: 1px solid rgba(0, 0, 0, 0.18);
            }
            .sidebar-left-content, .sidebar-right-content{
                width: 100%;
                padding: inherit;
                height: 80%;
            }
            .sidebar-left-header{
                text-align: center;
                margin: 0px auto;
                align-items:center;

            }
            span{
                text-decoration: none;
            }
            .glyphicon{
                font-size: 25px;
                padding: 12px;
                color: white;
            }
            .icon-list{

                width: 100%;
                margin: 0px;
            }
            .shadow{
                box-shadow: inset 0 0 10px #000000;
            }
            .users-list{
                display: block;
                width: 100%;
                height: 100%;
            }
            .user{
                width: inherit;
                height: 60px;
                cursor: pointer;
                border-bottom: 1px solid rgba(0, 0, 0, 0.18);
            }
            .user-image{
                display: inline-block;
                width: 50px;
                margin-left: 10px;
                border-radius: 100%;
                height: 50px;
            }
            .user-name{
                margin-left: 12px;
                font-size: 18px;
                color: white;
            }
            .btn{
                outline: none;
                color : white;
            }
            .btn.outline {
                background: none;
                padding: 7px 15px;
            }
            .btn-primary.outline {
                border: 2px solid rgba(0, 0, 0, 0.18);
                color: white;
                outline: none;
            }
            .btn-primary.outline:hover, .btn-primary.outline:focus, .btn-primary.outline:active, .btn-primary.outline.active, .open > .dropdown-toggle.btn-primary {
                color: rgba(0, 0, 0, 0.49);
                border-color: rgba(0, 0, 0, 0.18);
            }
            .btn-primary.outline:active, .btn-primary.outline.active {
                border-color: rgba(0, 0, 0, 0.18);
                color: rgba(0, 0, 0, 0.18);
                box-shadow: none;
            }
            .btn-primary:active{
                background-color: rgba(0, 0, 0, 0.18);
            }
            .add-button{
                position: absolute;
                right: 15px;
            }
            .disabled{
                background-color: rgba(0, 0, 0, 0.18);
            }
            .users-list{
                display: none;
            }
            .message-list{
                display: block;
            }
            .groups-list{
                display: none;
            }
            a:hover{
                text-decoration: none;
            }
            .message-container{
                width: inherit;
                height: 100%;
                display: block;
            }
            .messages{
                display: block;
                width: inherit;
                margin: 0;
                height: 285px;
            }
            .input-box{
                display: inline-block;
                width: inherit;
                padding: 10px;
            }
            .placeholder-color::-webkit-input-placeholder{
                color: #a5a5a5;
            }
            .message-send-btn{
                position: absolute;
                margin-left: 10px;
            }
            .input-group{
                width: inherit;
                width: 87%;
                margin: 0px;
            }
            .active-friend{
                background-color : rgba(255, 255, 255, 0.28);
            }
            .message-wrapper{
                width: inherit;
                height: auto;
                padding: 20px;
                margin-bottom: 12px;
            }
            .message{
                max-width: 70%;
                background: #ffffff;
                padding: 10px 15px 10px 15px;
                border-radius: 5px;
                float: left;
            }
            .message-right{
                float: right;
            }
            .group-create-text{
                font-size: 20px;
                color: white;
            }
            .group-create-image{
                height: 45px;
                width: 45px;;
            }
            .received{
                background-color: rgb(220, 186, 138);
            }
            .member-list-group{
                max-width: 150px;
                overflow: hidden;
                color: white;
                white-space: nowrap;
                text-overflow: ellipsis;
            }
        </style>
    </head>
    <body>
        <div class="main-content vcenter">
            <div class="content-wrapper row">
                <div class="sidebar-left col-sm-4">
                    <div class="sidebar-left-header vcenter">
                        <ul class="icon-list list-inline">
                            <li onclick="alterShadow(0), viewUsersModel()"><span class="glyphicon " ><img src="./images/add-user-2-32.png"></span> </li>
                            <li onclick="alterShadow(1), viewMessageModel()"><span class="glyphicon shadow"><img src="./images/message-2-32.png"></span> </li>
                            <li onclick="alterShadow(2), viewGroupModel()"><span class="glyphicon"><img src="./images/group-32.png"></span> </li>
                            <li onclick="alterShadow(3), logout()"><span class="glyphicon"><img src="./images/logout.svg"></span> </li>
                        </ul>
                    </div>
                    <div class="sidebar-left-content scrollbar" id="style-1">
                        <div class="users-list force-overflow" id="users-list">
                            <?php
                                $query = "SELECT * FROM `user_info` WHERE 1";
                                $result = $db->query($query);
                                if(!$result){}
                                else {
                                    while ($row = $result->fetch_assoc()) {
                                        if($row['userid']!=$userid) {
                                            echo "<div class='user vertical-center' id=" . $row['userid'] . ">
                                            <div class='user-image'>
                                                <img style='max-width: inherit; max-height: inherit; width: inherit; height: inherit' src='uploads/photos/user.svg' >   
                                            </div>
                                            <span class='user-name'>" . $row['username'] . "</span>
                                            <button class='add-button btn btn-primary btn-sm outline' onclick='addFriend(this)'>Add</button>
                                            </div>";
                                        }
                                    }
                                }
                            ?>
                        </div>
                        <div class="message-list force-overflow" id="message-list">

                        </div>
                        <style>
                            .group-default{
                                height: 60px;
                                border-bottom: 1px solid rgba(0, 0, 0, 0.18);
                                cursor: pointer;
                            }
                            .group-creator-container{
                                width: inherit;
                                height: inherit;
                                display: none;
                            }
                            .g-name{
                                background: transparent;
                                border: 0px;
                                width: 70%;
                                text-align: center;
                                color: white;
                                font-size: 30px;
                                outline: none;
                                margin: 0 auto;
                            }
                            .line{
                                margin: 0 auto;
                                width: 70%;
                                border: 1px solid rgba(255, 255, 255, 0.17);
                            }
                            .g-name:focus + .line{
                                border-color: rgba(255, 255, 255, 0.82);
                            }
                            .g-name::-webkit-input-placeholder{
                                color: rgba(255, 255, 255, 0.47);
                            }
                            .creator-group-name{
                                display: flex;
                                flex-wrap: wrap;
                                align-items: center;
                            }
                            .member-list{
                                margin-left: 50px;
                                height: 70%;
                                width: 55%;
                                border: 1px solid rgba(255, 255, 255, 0.27);;
                            }
                            h3{
                                margin-left: 50px;
                            }
                            .checkbox-custom{
                                display: flex;
                                flex-wrap: wrap;
                                border: 1px solid white;
                                width: 30px;
                                height: 30px;
                                margin-left: 140px;
                            }
                            .checkbox-dot{
                                background: white;
                                width: 15px;
                                height: 15px;
                                margin: 0 auto;
                                margin-top: 6px;
                            }
                            .participants{

                            }
                            .create-group-btn{
                                height: 65px;
                                width: 65px;
                                border-radius: 100%;
                                position: absolute;
                                left: 75%;
                                top: 60%;
                            }
                            .grp_message_sender{
                                display: block;
                                font-size: 11px;
                                margin-left: -8px;
                                margin-top: -5px;
                                width: inherit;
                                color: red;
                            }
                        </style>
                        <div class="groups-list force-overflow" id="groups-list">
                            <div onclick="showGroupCreator()" class="group-default vertical-center">
                                <div class="user-image group-create-image">
                                    <img style="max-width: inherit; max-height: inherit; width: inherit; height: inherit" src="images/plus-7-xxl.png">
                                </div>
                                &nbsp;
                                <span class="group-create-text">Create Group ...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-right col-sm-8">
                    <div class="sidebar-right-header">

                    </div>
                    <div class="sidebar-right-content">
                        <div class="message-container">
                            <div class="messages scrollbar" id="style-1">
                                <div class="private-messages"></div>
                                <div class="group-messages"></div>
                            </div>
                            <div class="input-box">
                                <form class="input-group" onsubmit="return sendMessage()">
                                    <input required class="message-input form-control form-group-lg placeholder-color"
                                           placeholder="Type message here..." id="message-input">
                                    <input type="submit" style="display: none;">
                                    <button class="message-send-btn btn btn-primary btn-sm outline" onclick="return sendMessage()">Send</button>
                                </form>
                            </div>
                        </div>
                        <div class="group-creator-container">
                            <div class="creator-group-name">
                                <input placeholder="Group Name" class="g-name"><br>
                                <div class="line" ></div>
                            </div>
                            <div class="participants">
                                <h3>Select Members: </h3>
                                <div class="member-list scrollbar" id="style-1">
                                    <div class="force-overflow" id="group-members-list">

                                    </div>
                                </div>

                                <button onclick="createGroup()" class="btn create-group-btn">Create</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>