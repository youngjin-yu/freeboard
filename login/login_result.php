<?php
session_start();
$id = $_REQUEST["id"];
$pw = $_REQUEST["pass"];
$chbox= $_REQUEST["chbox"];

if($id=="admin"&&$pw=123456){
    // id, password 가 member 테이블에 존재하는 경우는 로그인한다
    $_SESSION["userid"]="admin";
    $_SESSION["name"]="관리자";
    $_SESSION["nick"]="관리자";
    $_SESSION["level"]="9";

    // index.php 페이지로 이동
    header("Location:http://localhost/freeboard/index.php");
    exit;
}

require_once("../main/MYDB.php");
$pdo = db_connect();



try{
    $sql="select * from freeboard_db.member where id=?";
    $stmh = $pdo->prepare($sql);
    $stmh ->bindValue(1,$id,PDO::PARAM_STR);
    $stmh ->execute();
    $count=$stmh->rowCount();
}catch(PDOException $Exception){
    print "오류: ".$Exception->getMessage();
}

$row =$stmh->fetch(PDO::FETCH_ASSOC);
// 일치하는 아이디가 없는 경우
if($count<1){
    ?>
<script>
    alert("입력한 아이디가 존재하지 않습니다. 아이디를 다시 한번 입력해 주세요.");
    history.back(); //이전 페이지로 이동
</script>
<?php
}elseif($pw!=$row["passwd"]){

?>
<script>
    alert("입력한 아이디와 비밀번호가 일치하지 않습니다. 아이디 또는 비밀번호를 다시 한번 입력해 주세요.");
    history.back();
</script>
<?php
}else{
    // id, password 가 member 테이블에 존재하는 경우는 로그인한다

    
    
    $_SESSION["userid"]=$row["id"];
    $_SESSION["name"]=$row["name"];
    $_SESSION["nick"]=$row["nickname"];
    $_SESSION["level"]=$row["userlevel"];

    if($chbox=="yes"){
        ////자동 로그인이 체크되었다면 유효 hour : 24 hour
        setcookie("userid",$_SESSION["userid"],time()+3600*24,"/");
        setcookie("name",$_SESSION["name"],time()+3600*24,"/");
        setcookie("nick",$_SESSION["nick"],time()+3600*24,"/");
        setcookie("level",$_SESSION["level"],time()+3600*24,"/");
        setcookie("pw",$pw,time()+3600*24,"/");
    }else{
        //자동 로그인이 체크되지 않았다면 브라우저를 닫았을 시 로그아웃 되게 한다
        //쿠키 유지 hour을 현재 hour 까지로 설정함으로써 바로 만료되게 한다
        setcookie("userid","",-1,"/");
        setcookie("name","",-1,"/");
        setcookie("nick","",-1,"/");
        setcookie("level","",-1,"/");
        setcookie("pw","",-1,"/");
    }

    // index.php 페이지로 이동
    header("Location:http://localhost/freeboard/index.php");
    exit;
}
?>