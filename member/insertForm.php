<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/common.css">
        <link rel="stylesheet" type="text/css" href="../css/member.css?after">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function (e) {
                $(".checkID").on("keyup", function () { //checkID라는 클래스에 입력을 감지
                    var self = $(this);
                    var userid;

                    if (self.attr("id") === "userid") {
                        userid = self.val();
                    }

                    $.post( //post방식으로 id_check.php에 입력한 userid값을 넘깁니다
                            "id_check.php", {
                        userid: userid
                    }, function (data) {
                        if (data) { //만약 data값이 전송되면
                            self
                                .parent()
                                .parent()
                                .find("div.checkID_result")
                                .html(data); //div태그 중 class가 checkID_result인 div를 찾아 html방식으로 data를 뿌려줍니다.
                            self
                                .parent()
                                .parent()
                                .find("div.checkID_result")
                                .css("color", "#F00"); //div태그 중 class가 checkID_result인 div를 찾아 css효과로 빨간색을 설정합니다
                        }
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function (e) {
                $(".checkNick").on("keyup", function () { //checkNick라는 클래스에 입력을 감지
                    var self2 = $(this);
                    var usernick;

                    // 2020.05.20 del 잘못된 부분 수정 if(self2.attr("nick") === "usernick"){ 	usernick =
                    // self2.val(); } else{     usernick = self2.val(); }
                    usernick = self2.val();
                    console.log(usernick);

                    $.post( //post방식으로 id_check.php에 입력한 userid값을 넘깁니다
                            "nick_check.php", {
                        usernick: usernick
                    }, function (data) {
                        if (data) { //만약 data값이 전송되면
                            self2
                                .parent()
                                .parent()
                                .find("div.checkNick_result")
                                .html(data); //div태그 중 class가 checkID_result인 div를 찾아 html방식으로 data를 뿌려줍니다.
                            
                        }

                        if(data==="사용가능한 닉네임입니다."){
                            self2
                                .parent()
                                .parent()
                                .find("div.checkNick_result")
                                .css("color", "#0F0"); //div태그 중 class가 checkID_result인 div를 찾아 css효과로 빨간색을 설정합니다
                        }else if(data==""){
                            self2
                                .parent()
                                .parent()
                                .find("div.checkNick_result")
                                .html("닉네임을 입력하세요.");
                            self2
                                .parent()
                                .parent()
                                .find("div.checkNick_result")
                                .css("color", "#F00");
                        }else{
                            self2
                                .parent()
                                .parent()
                                .find("div.checkNick_result")
                                .css("color", "#F00"); //div태그 중 class가 checkID_result인 div를 찾아 css효과로 빨간색을 설정합니다
                        }
                    });
                });
            });
        </script>

        <script>
            //아이디 중복 체크
            function check_id() {
                window.open(
                    "check_id.php?id=" + document.member_form.id.value,
                    "IDcheck",
                    "left=200,top=200,width=400,height=200,scrollbars=no,resizable = yes "
                );
            }

            //닉네임 중복 체크
            function check_nick() {
                window.open(
                    "check_nick.php?nick=" + document.member_form.nick.value,
                    "NICKcheck",
                    "left=200,top=200,width=400,height=200, scrollbars=no, resizable = yes "
                );
            }

            //비밀번호 체크
            function passwordCheck($_str) {
                $pw = $_str;
                $num = preg_match('/[0-9]/u', $pw);
                $eng = preg_match('/[a-z]/u', $pw);
                $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u", $pw);

                if (strlen($pw) < 8 || strlen($pw) > 16) {
                    return array(false, "비밀번호는 8~16자 영문 대 소문자, 숫자, 특수문자를 사용하세요.");
                    exit;
                }

                if (preg_match("/\s/u", $pw) == true) {
                    return array(false, "비밀번호는 공백없이 입력해주세요.");
                    exit;
                }

                if ($num == 0 || $eng == 0 || $spe == 0) {
                    return array(false, "영문, 숫자, 특수문자를 혼합하여 입력해주세요.");
                    exit;
                }

                return array(true);
            }

            function check_input() {
                //hp2 또는 hp3의 input중 하나라도 없다면 경고창을 보여준다
                if (!document.member_form.hp2.value || !document.member_form.hp3.value) {
                    alert("휴대폰 번호를 입력하세요");
                    document
                        .member_form
                        .nick
                        .focus();
                    return;
                }
                //password가 일치하지 않으면 경고창을 보여준다
                if (document.member_form.pass.value != document.member_form.pass_confirm.value) {
                    alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요.");
                    document
                        .member_form
                        .pass
                        .focus();
                    document
                        .member_form
                        .pass
                        .select();
                    return;
                }

                document
                    .member_form
                    .submit();

            }

            //사용자가 취소하기 버튼을 눌렀을 경우 실행되는 함수
            function reset_form() {
                document.member_form.id.value = "";
                document.member_form.pass.value = "";
                document.member_form.pass_confirm.value = "";
                document.member_form.name.value = "";
                document.member_form.nick.value = "";
                document.member_form.hp2.value = "";
                document.member_form.hp3.value = "";
                document.member_form.email1.value = "";
                document.member_form.email2.value = "";
                document
                    .member_form
                    .id
                    .focus();
                return;
            }

            // function search_address(){ }
        </script>
    </head>
    <body>

        <div id="wrap">
            <div id="header">
                <?php include "../main/top_login2.php"; ?>
            </div>
            <!-- end of header -->

            <div id="menu">
                <?php include "../main/top_menu2.php"; ?>
            </div>
            <!-- end of menu -->

            <div id="content">
                <!-- del 2020.05.12 <div id="col1"> <div id="left_menu"> ?php include
                "../main/left_menu.php"; ?> </div> </div> -->
                <!-- end of col1 -->

                <div id="col2">
                    <form name="member_form" method="post" action="insertMember.php">
                        <div id="title">
                            <img src="../img/title_join.gif">
                        </div>
                        <!-- end of title -->

                        <div id="form_join">
                            <div id="join1">
                                <!-- ul 태그: 순서가 필요 없는 목록-->
                                <ul>
                                    <li>* 아이디</li>
                                    <li>* 비밀번호</li>
                                    <li>* 비밀번호 확인</li>
                                    <li>* 이름</li>
                                    <li>* 닉네임</li>

                                    <li>* 휴대폰</li>
                                    <!-- &nbsp; : 빈 공백을 의미하는 특수 문자 -->
                                    <li>&nbsp;&nbsp;&nbsp;이메일</li>
                                </ul>
                            </div>
                            <div id="join2">
                                <ul>
                                    <li>
                                        <div id="user_id_wrapper">
                                            <!-- id="userid" class="checkID" placeholder="아이디" 추가 -->
                                            <div id="id1"><input
                                                type="text"
                                                name="id"
                                                id="userid"
                                                class="checkID"
                                                placeholder="아이디"
                                                required="required"></div>
                                            <!-- <div id="id2"><a href="#"><input type="button" style="HEIGHT:17pt"
                                            name="idBtn" value="중복검사" onclick="check_id()"></a></div> -->
                                            <div id="id_check" class="checkID_result">4~12자의 영문 소문자, 숫자와 특수기호(_)만 사용할 수 있습니다.</div>
                                            <!-- 2020.05.14 del id3 삭제 -->
                                        </div>
                                    </li>

                                    <li><input type="password" name="pass" required="required"></li>
                                    <li><input type="password" name="pass_confirm" required="required"></li>
                                    <li><input type="text" name="name" required="required"></li>
                                    <li>
                                        <div id="nick">
                                            <div id="nick1"><input
                                                type="text"
                                                name="nick"
                                                id="usernick"
                                                class="checkNick"
                                                placeholder="닉네임"
                                                required="required"></div>
                                            <!-- <div id="nick2"><a href="#"><input type="button" style="HEIGHT:17pt"
                                            name="idBtn" value="중복검사" onclick="check_nick()"></a></div> -->
                                            <div id="nick_check" class="checkNick_result" style="display: inline-block;">4~12자의 영문 소문자, 숫자와 특수기호(_)만 사용할 수 있습니다.</div>
                                        </div>
                                    </li>
                                    <li><input type="text" class="hp" name="hp1" value="010">
                                        -
                                        <input type="text" class="hp" name="hp2">
                                        -
                                        <input type="text" class="hp" name="hp3"></li>
                                    <li><input type="text" id="email1" name="email1">
                                        @
                                        <input type="text" name="email2"></li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                            <div id="must">
                                * 는 필수 입력 항목입니다.</div>
                        </div>

                        <div id="button">
                            <a href="#"><img src="../img/button_save.gif" onclick="check_input()"></a>&nbsp;&nbsp;
                            <a href="#"><img src="../img/button_reset.gif" onclick="reset_form()"></a>
                        </div>
                    </form>
                </div>
                <!-- end of col2 -->
            </div>
            <!-- end of content -->
        </div>
        <!-- end of wrap -->
    </body>
</html>