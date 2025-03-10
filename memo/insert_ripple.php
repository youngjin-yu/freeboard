<?php session_start(); ?>
<meta charset="utf-8">
<?php
     if(!isset($_SESSION["userid"])) {
  ?>
<script>
    alert('로그인 후 이용해 주세요.');
    history.back();
</script>
<?php
  }

  $num=$_REQUEST["num"]; 
  $ripple_content=$_REQUEST["ripple_content"];
  
  require_once("../main/MYDB.php");
  $pdo = db_connect();

    try{
    $pdo->beginTransaction();   
    $sql = "insert into freeboard_db.memo_ripple(parent, id, name, nick, content, regist_day)";
    $sql.= "values(?, ?, ?, ?, ?,now())"; 
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->bindValue(2, $_SESSION["userid"], PDO::PARAM_STR);  
    $stmh->bindValue(3, $_SESSION["name"], PDO::PARAM_STR);   
    $stmh->bindValue(4, $_SESSION["nick"], PDO::PARAM_STR);
    $stmh->bindValue(5, $ripple_content, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit(); 
   
    header("Location:http://localhost/freeboard/memo/memo.php");
    } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
    }
   ?>