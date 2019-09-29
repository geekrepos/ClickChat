<?php
    session_start();
    $msg = NULL;
    if(isset($_SESSION['chat_user_id'])){
        header('Location: home.php');
    }
    else{
        if(isset($_GET['logoutmsg'])){
            $msg = $_GET['logoutmsg'];
        }
    }
?>

<html>
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
        <!-- <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'> -->
        <link rel="stylesheet" href="./styles/fonts/font_apply.css" type="text/css">
        <script src="scripts/jquery-3.2.0.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="bootstrap-3.3.7/dist/css/bootstrap.css">
        <script src="bootstrap-3.3.7/dist/js/bootstrap.js"></script>
        <title>Click Chat</title>
        <style>
            body{
                width: 100%;
                height: 100%;
                margin: 0px;
                background: url(images/background.jpeg) no-repeat center center fixed;
                background-size: cover;
            }
            .login-form , .registration-form{
                /*border: 1px solid black;*/
                background-color: rgba(197, 197, 197, 0.19);
                border-radius: 100%;
                width: 350px;
                height: 350px;
                text-align: center;
                vertical-align: middle;
                line-height: 90px;
                margin: 0 auto;
            }
            .login-form{
                margin-right: 0px;
            }
            .vertical-center {
                min-height: 100%;
                min-width: 100%;
                display: -webkit-flex;
                display: -moz-flex;
                display: flex;
                align-items: center;
            }
            .registration-form{
                width: 420px;
                height:420px;
                margin-left: 0px;
                background-color: rgba(181, 181, 181, 0.27);
            }
            .password-group{
                margin-top: 10px;
            }
            .username, .password{
                background-color: rgba(0, 0, 0, 0.32);
                border:  1px solid rgba(81, 81, 81, 0.65);
                color: white;
            }
            .left-inner-addon {
                position: relative;
                display: flex;
                color: whitesmoke;
                margin: 0px auto;
                line-height: inherit;
            }
            .left-inner-addon input {
                padding-left: 37px;
            }
            .left-inner-addon i {
                position: absolute;
                padding: 10px 12px;
                pointer-events: none;
            }
            h2 {
                font: 60px/1.5 'Pacifico', Helvetica, sans-serif;
                color: #efefef;
                text-shadow: 3px 3px 0px rgba(0,0,0,0.1), 7px 7px 0px rgba(0,0,0,0.05);
                display: block;
                margin: 0 auto;
                margin-top: 20px;
            }
            .form-control:focus{
                border-color: #8c8c8c;
                outline: 0;
                box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(197, 197, 197, 0.6);
            }
            .glyphicon-arrow-right{
                color: white;
                font-size: 10px;
            }
            .submit-button{
                width: 65px;
                margin-top: 15px;
            }
            .register-text{
                margin-top: 40px;;
            }
            .placeholder-color::-webkit-input-placeholder{
                color: #a5a5a5;
            }
            .rg-user, .rg-pass{
                border: none;
                background-color: rgba(0, 0, 0, 0.38);
            }
            .rg-user-block{
                margin-top: 21px;
                display: block !important;
            }
            .rg-user:focus, .rg-pass:focus{
                border-color: #8c8c8c;
                outline: 0;
                box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(255, 255, 255, 0.6);
            }
            @-moz-document url-prefix() {
                .row{
                    margin-top: 25px;
                }
            }
        </style>
        <style>
            .no-js #loader { display: none;  }
            .js #loader { display: block; position: absolute; left: 100px; top: 0; }
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(images/loader/ring.gif) center no-repeat #fff;
            }

        </style>
        <script>
            var isIE = false || !!document.documentMode;
            //if(!isIE){
            window.onload = function (){
                somePromise = new Promise(function(resolve, reject){
                    setTimeout(function(){$(".se-pre-con").fadeOut("slow");}, 700);
                });
                //somePromise.then(function(){
                    <?php if($msg!=NULL && $msg!=''){echo "msg=".$msg. ";";echo "msg?alert(msg):null;";}?>
                //});
            }
            //}
        </script>
        <script>
//            window.onload = function(){
//                setTimeout(function(){$(".se-pre-con").fadeOut("slow").then();}, 700);
            var vart;
            function submitLogin(){
                var uid   = $('#login-userid');
                var pass  = $('#login-password');
                var errorCodeArray = {ErrorCode0: "Username does not exists.", ErrorCode1: "Password is incorrect."};
                var customURI = "login/index.php?uid="+uid.val()+"&pass="+pass.val()+"&specialkey=true";
                console.log(customURI);
                $.ajax({
                    type: "GET",
                    url : customURI,
                    success: function(response){
                        if(errorCodeArray[response] === undefined){
                            location.href = "home.php";
                        }
                        else{
                            alert(errorCodeArray[response]);
                            $("#log-form").trigger("reset");
                        }
                        return false;
                    }
                });
               return false;
            }
            function submitRegister(){
                var data = new FormData();
                var uid   = $.trim($('#register-userid').val());
                var pass  = $.trim($('#register-password').val());
                var uname = $.trim($('#register-username').val());
                var uphoto =  $('[name= "user_image_file"]')[0].files[0];
// debugger;
                data.append('uid', uid);
                data.append('pass', pass);
                data.append('uname', uname);
                data.append('specialkey', true);
                if(uphoto!=null && uphoto!=undefined){
                    data.append('uphoto', uphoto);
                    var errorCodeArray = {ErrorCode0: "Username already exists.", ErrorCode1: "User Registered Successfullly.", ErrorCode2: "Please check if fields are empty!", ErrorCode3: "User profile created but photo upload was unsuccessful!"};
                    console.log(data);
                    var customURI = "register/index.php"
                
                    if(!uid==''&&!pass==''&&!uname==''){
                        console.log(customURI);
                        $.ajax({
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            method: 'POST',
                            type: 'POST', // For jQuery < 1.9
                            url : customURI,
                            success: function(response){
                                console.log(response);
                                if(errorCodeArray[response] === undefined){
                                    
                                }
                                else{
                                    alert(errorCodeArray[response]);
                                }
                                return false;
                            }
                        });
                        return false;
                    }
                    else{
                        alert(errorCodeArray.ErrorCode2);
                        $("#reg-form").trigger("reset");
                        return false;
                    }
                }
                else{alert("Photo is a required field!");}
            }
        </script>
        
    </head>
<body>
 <div class="se-pre-con"></div>

    <div name="main-content" class="vertical-center">
        <form name="login-form" class="login-form form-inline container-table" id="log-form" onsubmit="return submitLogin()">
            <h2>Login</h2>
            <div class="left-inner-addon row form-group username-group">
                <i class="glyphicon glyphicon-user">  </i>
                <input type="text" id="login-userid" autofocus autocomplete="off"
                       class="form-control form-group-lg username placeholder-color"
                       placeholder="Username" />
            </div>
            <br>
            <div class="left-inner-addon row form-group password-group">
                <i class="glyphicon glyphicon-lock">  </i>
                <input type="password" id="login-password"
                       class="form-control form-group-lg password placeholder-color"
                       placeholder="Password" />
            </div>
            <div class="submit">
                <a href="#">
                    <input type="submit"
                           style="display: none; position: absolute; left: -9999px; width: 1px; height: 1px;"
                           tabindex="-1" />
                    <img  class="submit-button" src="images/right-arrow.png" onclick="return submitLogin()">
                </a>
            </div>
        </form>
        <h2>Click Chat</h2>
        <form name="registration-form" id="reg-form" class="registration-form form-inline" onsubmit="return submitRegister()">
            <h2 class="register-text">Register</h2>
            <div class="left-inner-addon row form-group username-group rg-user-block rub">
                <i class="glyphicon glyphicon-user">  </i>
                <input type="text" id="register-username"
                       class="form-control form-group-lg username rg-user placeholder-color"
                       placeholder="Name" />
            </div>
            <div class="left-inner-addon row form-group username-group">
                <i class="glyphicon">id</i>
                <input type="text" id="register-userid"
                       class="form-control form-group-lg username rg-user placeholder-color"
                       placeholder="Userid" />
            </div>
            <br>
            <div class="left-inner-addon row form-group password-group">
                <i class="glyphicon glyphicon-lock">  </i>
                <input type="password" id="register-password"
                       class="form-control form-group-lg password rg-pass placeholder-color"
                       placeholder="Password" />
            </div>
            <style>
            .inputfile + label {
	cursor: pointer; /* "hand" cursor */
}   
            .inputfile {
                line-height: 10px;
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }
            .inputfile + label {
    /* font-size: 1.25em;
    font-weight: 700; */
    padding: 10px;
    /* vertical-align: middle; */
    width: 200px;
    border-radius: 10px;
    line-height: initial;
    height: 10%;
    background-color: #00000085;
}

.inputfile:focus + label,
.inputfile + label:hover {
    background-color: #00000025;
}
            </style>
            <div class="left-inner-addon form-group password-group custom" style="display: block; margin-top: 10px">
                <input type="file" name="user_image_file" id="file" class="inputfile" accept=".gif,.jpg,.jpeg,.png"/>
                <label for="file">Select Photo</label>
            </div>
            <div class="submit">
                <a href="#">
                    <input type="submit"
                           style="position: absolute; left: -9999px; width: 1px; height: 1px;"
                           tabindex="-1" />
                    <img  class="submit-button" src="images/right-arrow.png" onclick="return submitRegister()">
                </a>
            </div>
        </form>
    </div>
</body>
</html>
