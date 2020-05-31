  <?php
    $num=$_REQUEST["num"];
        
    require_once("../main/MYDB.php");
    $pdo = db_connect();
        
     try{
       $pdo->beginTransaction();
       $sql = "delete from freeboard_db.memo_ripple where num = ?";  //db만 수정 
       $stmh = $pdo->prepare($sql);
       $stmh->bindValue(1,$num,PDO::PARAM_STR);
       $stmh->execute();   
       $pdo->commit();
                
        header("Location:http://localhost/freeboard/memo/memo.php");
       } catch (Exception $ex) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
       }
  ?>
