<?php
session_start();
$id=$_REQUEST["id"];
$pass=$_REQUEST["pass"];
$name=$_REQUEST["name"];
$nick=$_REQUEST["nick"];
$hp1=$_REQUEST["hp1"];
$hp2=$_REQUEST["hp2"];
$hp3=$_REQUEST["hp3"];
$email1=$_REQUEST["email1"];
$email2=$_REQUEST["email2"];
$hp=$hp1."-".$hp2."-".$hp3;
$email=$email1."@".$email2;
$regist_day=date("Y-m-d H:i:s");

require_once("../main/MYDB.php");
$pdo=db_connect();

try{
    // 수정,삭제,추가 작업을 할때는 beginTransaction을 추가해야 한다
    $pdo->beginTransaction();
    $sql="update freeboard_db.member set passwd=?, name=?, nickname=?, phonenumber=?,email=?,register_day=? where id=?";
    $stmh=$pdo->prepare($sql);
    $stmh->bindValue(1,$pass,PDO::PARAM_STR);
    $stmh->bindValue(2,$name,PDO::PARAM_STR);
    $stmh->bindValue(3,$nick,PDO::PARAM_STR);
    $stmh->bindValue(4,$hp,PDO::PARAM_STR);
    $stmh->bindValue(5,$email,PDO::PARAM_STR);
    $stmh->bindValue(6,$regist_day,PDO::PARAM_STR);
    $stmh->bindValue(7,$id,PDO::PARAM_STR);
    
    $_SESSION["nick"]=$nick;
    
    //echo ("<script> console.log( 'PHP_Console: " . $nick . "' );</script>");
    $stmh->execute();
    $pdo->commit();
    
    header("Location:http://localhost/freeboard/index.php");
}catch(PDOException $Exception){
    // 오류가 있으면 rollback 한다
    $pdo->rollBack();
    print "오류: ".$Exception->getMessage();
}



?>