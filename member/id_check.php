<?php
include "../main/MYDB.php";
$pdo = db_connect();
if($_POST['userid'] != NULL){
try {
    $sql = "select * from freeboard_db.member where id='{$_POST['userid']}'";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    // member 테이블에 사용자가 입력한 id에 해당하는 데이터가 있는지 체크하여 데이터를 가져온다
    // count 변수는 member 테이블에서 가져온 유저에 해당하는 row가 몇개인지를 의미
    $count = $stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
if ($count < 1) {
    echo "사용가능한 아이디입니다.";
} else {
    echo "아이디가 중복됩니다.다른 아이디를 사용해 주세요.";
}
}

	
 ?>