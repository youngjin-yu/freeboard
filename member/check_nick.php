<meta charset="UTF-8">
<?php $nick = $_REQUEST["nick"];
if (!$nick) {
    print "닉네임을 입력하세요.<p>";
} else {
    // 외부의 파일을 현재 있는 파일로 불러오는 역할을 하는 것으로 MYDB.php가 없을 경우 에러를 표시하고 MYDB.php를 한번만 사용한다는 의미 
    require_once("../main/MYDB.php");
    $pdo = db_connect();

    try {
        $sql = "select * from freeboard_db.member where nickname = ? ";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $nick, PDO::PARAM_STR);
        $stmh->execute();
        // member 테이블에 사용자가 입력한 id에 해당하는 데이터가 있는지 체크하여 데이터를 가져온다
        // count 변수는 member 테이블에서 가져온 유저에 해당하는 row가 몇개인지를 의미
        $count = $stmh->rowCount();
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
    if ($count < 1) {
        print "사용가능한 닉네임입니다.<p>";
    } else {
        print "닉네임이 중복됩니다.<br>다른 닉네임을 사용해 주세요.<p>";
    }
}
print "<center><input type=button value=창닫기 onClick='self.close()'></center>";

?>