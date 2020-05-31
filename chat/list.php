<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="../css/common.css">

        <script defer="defer" src="http://localhost:3000/socket.io/socket.io.js"></script>
        <script defer="defer" src="./script.js"></script>
        <style>
            /* body {
                padding: 0;
                margin: 0;
                display: flex;
                justify-content: center;
            } */

            #message-container div {
                background-color: #CCC;
                padding: 5px;
            }

            #message-container div:nth-child(2n) {
                background-color: #FFF;
            }

            #send-container {
                position: fixed;
                padding-bottom: 30px;
                bottom: 0;
                background-color: white;
                width: 1000px;
                display: flex;
            }

            #message-input {
                flex-grow: 1;
            }
        </style>

    </head>

    <body>
        
        <div id="wrap">
            <div id="usernickname"><?php echo $_SESSION["nick"]?></div>
        
            <div id="header">
                <!-- 상대 경로 지정 -->
                <?php include "../main/top_login2.php"; ?>
            </div>
            <!-- end of header -->
            <p></p>
            <div id="menu">
                <!-- 상대 경로 지정 -->
                <?php include "../main/top_menu2.php"; ?>
            </div>
            <!-- end of menu -->

            
            <div id="message-container"></div>
            

            <form id="send-container">
                <input type="text" id="message-input">
                <button type="submit" id="send-button">보내기</button>
            </form>
        </div>
        <!-- end of wrap -->
    </body>
</html>