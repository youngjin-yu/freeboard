<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/common.css">
        <link rel="stylesheet" type="text/css" href="../css/member.css?after">
        <script>
            function login_check(){
                if(!document.login_form.id.value){
                    alert("아이디를 입력하세요.");
                    document.login_form.id.focus();
                    return;
                }
                if(!document.login_form.pass.value){
                    alert("비밀번호를 입력하세요.");
                    document.login_form.pass.focus();
                    return;
                }
                document.login_form.submit();
            }
        </script>
</head>
<body>
    <div id="wrap">
        <div id="header">
            <?php include "../main/top_login2.php"; ?>
        </div>
        
        <div id="menu">
            <?php include "../main/top_menu2.php"; ?>
        </div>

        <div id="content">
            <!-- del 2020.05.12 <div id="col1">
                <div id="left_menu">
                    ?php include "../main/left_menu.php"; ?>
                </div>
            </div> -->
            <div id ="col2">
                <form name="login_form" method="post" action="login_result.php">
                    <div id="title">
                        로그인
                    </div>

                    <div id="login_form">
                        
                
                        <div id="login">
                            <img src="../img/login_key.png">
                        </div>
                        <div id ="login2">
                            <div id ="id_input_button">
                                <div id ="id_pw_title">
                                    <ul>
                                        <li><div>아이디</div></li>
                                        <li><div>패스워드</div></li>
                                    </ul>
                                </div>
                                <div id="id_pw_input">
                                    <ul>
                                        <li><input type="text" name="id" class="login_input" required></li>
                                        <li><input type="password" name ="pass" class="login_input" required></li>
                                        <li><input type="checkbox" name ="chbox" value="yes">로그인 상태유지</li>
                                    </ul>
                                </div>
                                <div id="login_button" style="margin-top: 22px">
                                    <!-- <input type="button" style="HEIGHT:47pt"  value="로그인" onclick="document.member_form.submit()"> -->
                                
                                    <input type="image" src="../img/login_button.gif" onclick="document.member_form.submit()">
                                </div>
                                </div>
                            </div>
                            
                            <!-- <div id="login_line"></div> -->
                            <div id="join_button" style="margin-top: 50px;">
                                아직회원이 아니십니까?&nbsp;&nbsp;&nbsp;&nbsp;<a href="../member/insertForm.php"><input type="button" style="HEIGHT:20pt" value="회원가입"></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>