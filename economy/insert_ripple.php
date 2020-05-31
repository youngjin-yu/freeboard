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

  // 댓글 번호
  $num=$_REQUEST["num"]; 
  // 댓글 내용
  $ripple_content=$_REQUEST["ripple_content"];
  
  require_once("../main/MYDB.php");
  $pdo = db_connect();

    try{
    $pdo->beginTransaction();   
    $sql = "insert into freeboard_db.economy_ripple(parent, id, name, nick, content, regist_day)";
    $sql.= "values(?, ?, ?, ?, ?,now())"; 
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->bindValue(2, $_SESSION["userid"], PDO::PARAM_STR);  
    $stmh->bindValue(3, $_SESSION["name"], PDO::PARAM_STR);   
    $stmh->bindValue(4, $_SESSION["nick"], PDO::PARAM_STR);
    $stmh->bindValue(5, $ripple_content, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit(); 
   
    // 해당 num(글 번호)의 view.php로 이동 
    header("Location:http://localhost/freeboard/economy/view.php?num=$num");
    
    } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
    }
   ?>