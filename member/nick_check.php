<?php
include "../main/MYDB.php";
$pdo = db_connect();
if($_POST['usernick'] != NULL){
try {
    $sql = "select * from freeboard_db.member where nickname='{$_POST['usernick']}'";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    // member 테이블에 사용자가 입력한 id에 해당하는 데이터가 있는지 체크하여 데이터를 가져온다
    // count 변수는 member 테이블에서 가져온 유저에 해당하는 row가 몇개인지를 의미
    $count = $stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
if ($count < 1) {
    echo "사용가능한 닉네임입니다.";
} else {
    echo "닉네임이 중복됩니다.다른 닉네임을 사용해 주세요.";
}
}

	
 ?>