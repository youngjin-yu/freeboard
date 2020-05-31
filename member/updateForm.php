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
        //아이디 중복 체크
        function check_id() {
            window.open("check_id.php?id=" + document.member_form.id.value, "IDcheck", "left=200,top=200,width=400,height=200,scrollbars=no,resizable = yes ");
        }

        //닉네임 중복 체크
        function check_nick() {
            window.open("check_nick.php?nick=" + document.member_form.nick.value, "NICKcheck", "left=200,top=200,width=400,height=200, scrollbars=no, resizable = yes ");
        }

        function check_input() {
            //hp2 또는 hp3의 input중 하나라도 없다면 경고창을 보여준다
            if (!document.member_form.hp2.value || !document.member_form.hp3.value) {
                alert("휴대폰 번호를 입력하세요");
                document.member_form.nick.focus();
                return;
            }
            //password가 일치하지 않으면 경고창을 보여준다
            if (document.member_form.pass.value != document.member_form.pass_confirm.value) {
                alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요.");
                document.member_form.pass.focus();
                document.member_form.pass.select();
                return;
            }

            document.member_form.submit();

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
            document.member_form.id.focus();
            return;
        }
    </script>
</head>
<?php
$id = $_REQUEST["id"];

require_once("../main/MYDB.php");
$pdo = db_connect();

try {
    $sql = "select * from freeboard_db.member where id = ?";
    $stmh = $pdo->prepare($sql);
    // id 할당하는 부분
    $stmh->bindValue(1, $id, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
if ($count < 1) {
    print "검색결과가 없습니다.<br>";
} else {
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $hp = explode("-", $row["phonenumber"]);
        $hp2 = $hp[1];
        $hp3 = $hp[2];

        $email = explode("@", $row["email"]);
        $email1 = $email[0];
        $email2 = $email[1];
?>

        <body>
            <div id="wrap">
                <div id="header">
                    <?php include "../main/top_login2.php"; ?>
                </div> <!-- end of header -->

                <div id="menu">
                    <?php include "../main/top_menu2.php"; ?>
                </div> <!-- end of menu -->

                <div id="content">
                    <!-- del 2020.05.12 <div id="col1">
                        <div id="left_menu">
                            ?php include "../main/left_menu.php"; ?>
                        </div>
                    </div>  -->
                    <!-- end of col1 -->
                    <div id="col2">
                        <form name="member_form" method="post" action="updateMember.php?id=<?= $id ?>">
                            <div id="title">
                                <img src="../img/title_member_modify.gif">
                            </div>
                            <div id="form_join">
                                <div id="join1">
                                    <ul>
                                        <li>* 아이디</li>
                                        <li>* 비밀번호</li>
                                        <li>* 비밀번호 확인</li>
                                        <li>* 이름</li>
                                        <li>* 닉네임</li>
                                        <li>* 휴대폰</li>
                                        <li>&nbsp;&nbsp;&nbsp;이메일</li>
                                    </ul>
                                </div>
                                <div id="join2">
                                    <ul>
                                        <li><?= $row["id"] ?></li>
                                        <li><input type="password" name="pass" value="<?= $row["passwd"] ?>" required></li>
                                        <li><input type="password" name="pass_confirm" value="<?= $row["passwd"] ?>" required></li>
                                        <li><input type="text" name="name" value="<?= $row["name"] ?>" required></li>
                                        <li>
                                            <div id="nick1" style="display: inline-block"><input type="text" name="nick" value="<?= $row["nickname"] ?>" required></div>
                                            <div id="nick2" style="display: inline-block"> <a href="#"><input type="button" style="HEIGHT:17pt" name="idBtn" value="중복검사" onclick="check_nick()"></a></div>
                                        
                                        </li>
                                        <li> <input type="text" class="hp" name="hp1" value="010">-<input type="text" class="hp" name="hp2" value="<?= $hp2 ?>">-<input type="text" class="hp" name="hp3" value="<?= $hp3 ?>"></li>
                                        <li> <input type="text" id="email1" name="email1" value="<?= $email1 ?>">@<input type="text" name="email2" value="<?= $email2 ?>"></li>

                                    </ul>
                                </div>
                        <?php
                    }
                } ?>
                        <div class="clear"></div>
                        <div id="must">* 는 필수 입력 항목입니다.</div>
                            </div>

                            <div id="button"><a href="#"><img src="../img/button_save.gif" onclick="check_input()"></a>&nbsp;&nbsp;<a href="#"><img src="../img/button_reset.gif" onclick="reset_form()"></a></div>

                        </form>
                    </div>
                </div>
            </div>
        </body>

</html>